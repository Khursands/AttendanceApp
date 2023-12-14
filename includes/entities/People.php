<?PHP

namespace GM_HR {

    require 'vendor/autoload.php';

    class User {
        public $id = null;
        public $Username = null;
        public $Email = null;
        public $Password = null;
        public $Mobile = null;
        public $Phone = null;
        public $Address = null;
        public $EmploymentStatus = null;
        public $CreatedOn = null;
        public $CreatedBy = null;
        public $UpdatedOn = null;
        public $UpdatedBy = null;
        public $JoiningDate = null;
    }
    
    class UserDAL {

        public static function loadAll($conn) {
            try {
                $list = [];
    
                if ($result = $conn->query("SELECT * FROM user")) {
                    while ($row = $result->fetch_assoc()) {
                        $objUser = new User;
    
                        // Populate the User object with data from the database
                        // Assuming column names match property names in the User class
                        foreach ($row as $key => $value) {
                            $objUser->$key = $value;
                        }
    
                        $list[] = $objUser;
                    }
                    $result->free();
                }
    
                return $list;
            } catch (\Exception $e) {
                Logger::log($conn, "UserDAL::loadAll", "error", $e->getMessage());
            }
        }
    
        public static function loadById($conn, $id) {
            try {
                if ($result = $conn->query("SELECT * FROM user WHERE ID=" . $conn->escape($id))) {
                    $objUser = new User;
    
                    while ($row = $result->fetch_assoc()) {
                        // Populate the User object with data from the database
                        foreach ($row as $key => $value) {
                            $objUser->$key = $value;
                        }
                    }
    
                    $result->free();
    
                    return $objUser;
                }
            } catch (\Exception $e) {
                Logger::log($conn, "UserDAL::loadById", "error", $e->getMessage());
            }
        }

        public static function create($conn, $objUser) {
            $hashedPassword = password_hash($objUser->Password, PASSWORD_DEFAULT);
            try {
                $sql = "insert into user (Username, Email, Password, Mobile, Phone, Address, EmploymentStatus, CreatedBy, UpdatedBy) 
                        VALUES (" . "'" . $conn->escape($objUser->Username) . "'" . ",'"
                                        . $conn->escape($objUser->Email) . "'" . ",'"
                                        . $hashedPassword . "'" . ",'"
                                        . $conn->escape($objUser->Mobile) . "'" . ",'"
                                        . $conn->escape($objUser->Phone) . "'" . ",'"
                                        . $conn->escape($objUser->Address) . "'" . ",'"
                                        . $conn->escape($objUser->EmploymentStatus) . "'" . ",'"
                                        . $conn->escape($objUser->CreatedBy) . "'" . ",'"
                                        . $conn->escape($objUser->UpdatedBy) . "'" . ")";

                //Common::jsonSuccess(array("Query" => $sql));
                $conn->query($sql,"User::create", 0);
                $conn->close();
            } 
            catch (\Exception $e) {
                Logger::log($conn, "UserDAL::create", "error", $e->getMessage());
            }
        }
    
        public static function update($conn, $objUser) {
            $hashedPassword = password_hash($objUser->Password, PASSWORD_DEFAULT);

            try {
                $sql = "UPDATE user 
                        SET Username = '" . $conn->escape($objUser->Username) . "', 
                            Email = '" . $conn->escape($objUser->Email) . "', 
                            Password = '" . $hashedPassword . "', 
                            Mobile = '" . $conn->escape($objUser->Mobile) . "', 
                            Phone = '" . $conn->escape($objUser->Phone) . "', 
                            Address = '" . $conn->escape($objUser->Address) . "', 
                            EmploymentStatus = '" . $conn->escape($objUser->EmploymentStatus) . "', 
                            UpdatedBy = '" . $conn->escape($objUser->UpdatedBy) . "'
                        WHERE ID = '" . $conn->escape($objUser->id) . "'";
        
                    $conn->query($sql, "User::create", 0);
            } catch (\Exception $e) {
                Logger::log($e->getMessage());
            }
        }
        public static function delete($conn, $id) {
            try {
                $sql = "delete from user where id=" . $conn->escape($id);
                $conn->query($sql, "User::delete", 0);
                $conn->close();
            } catch (\Exception $e) {
                Logger::log($conn, "UserDAL::delete", "error", $e->getMessage());
            }
        }
        public static function login($conn, $email, $password) {
            try {
                // Fetch user by username
                $sql = "SELECT * FROM user WHERE Email = '" . $conn->escape($email) . "'";
                if ($result = $conn->query($sql)) {
                    $user = $result->fetch_assoc();
                    $result->free();
    
                    // Check if the user exists and verify the password
                    if ($user && password_verify($password, $user['Password'])) {
                        // User authenticated
                        $token = self::generateJwtToken($user); // You need to implement this method
    
                        // Set the token as a cookie
                        setcookie('auth_token', $token, time() + (24 * 60 * 60), '/'); // Adjust path, domain, and secure parameters as needed
    
                        // Return success
                        return Common::jsonSuccess(array("User Logged In "=> True));
                    }
                }
                // Authentication failed
                return Common::jsonSuccess(array("Incorrect Username or Password "=> False));
            } catch (\Exception $e) {
                Logger::log($e->getMessage());
                return false;
            }
        }
        public static function logout() 
        {
            if (isset($_COOKIE['auth_token'])) 
            {
                unset($_COOKIE['auth_token']);
                setcookie('auth_token', '', time() - 3600, '/'); // empty value and old timestamp
                return true;
            }
            // Return success
            return false;
        }
            
    
        private static function generateJwtToken($user) {
            $tokenId = base64_encode(random_bytes(32));
            $issuedAt = time();
            $expirationTime = $issuedAt + (24 * 60 * 60); // Token expiry time (e.g., 24 hours)
        
            $payload = [
                'id' => $tokenId,
                'iat' => $issuedAt,
                'exp' => $expirationTime,
                'sub' => $user['id'],
            ];
        
            $secretKey = 'd1cd453f02639be7540186ec35cf39a1e99f5027'; // Change this to a secure secret key
            $algorithm = 'HS256'; // Use a secure algorithm like HS256
        
            // Use the correct namespace for the JWT class
            return \Firebase\JWT\JWT::encode($payload, $secretKey, $algorithm);
        }
        
    }

    class People {

        public $id = null;
        public $Name = null;
        public $email = null;
        public $phone = null;
        public $role = null;

    }

    class PeopleDAL {

        public static function loadAll($conn) {
            try {
                $list = [];

                if ($result = $conn->query("select id,Name,email,phone,role from people")) {
                    while ($row = $result->fetch_assoc()) {
                        $objPeople = new People;

                        $objPeople->id = $row["id"];
                        $objPeople->Name = $row["Name"];
                        $objPeople->email = $row["email"];
                        $objPeople->phone = $row["phone"];
                        $objPeople->role = $row["role"];

                        $list[] = $objPeople;
                    }
                    $result->free();
                }
                return $list;
            } catch (\Exception $e) {
                Logger::log($conn, "PeopleDAL::loadAll", "error", $e->getMessage());
            }
        }
        
        public static function loadAllByType($conn,$role) {
            try {
                $list = [];

                if ($result = $conn->query("select id,Name,email,phone,role from people where role like '".$conn->escape($role)."'")) {
                    while ($row = $result->fetch_assoc()) {
                        $objPeople = new People;

                        $objPeople->id = $row["id"];
                        $objPeople->Name = $row["Name"];
                        $objPeople->email = $row["email"];
                        $objPeople->phone = $row["phone"];
                        $objPeople->role = $row["role"];

                        $list[] = $objPeople;
                    }
                    $result->free();
                }
                return $list;
            } catch (\Exception $e) {
                Logger::log($conn, "PeopleDAL::loadAll", "error", $e->getMessage());
            }
        }

        public static function loadById($conn, $id) {
            try {
                $list = [];
                //print_r("select id,Name,email,phone,role from people where id=" . $conn->escape($id));

                if ($result = $conn->query("select id,Name,email,phone,role from people where id=" . $conn->escape($id))) {
                    $objPeople = new People;

                    while ($row = $result->fetch_assoc()) {
                        $objPeople->id = $row["id"];
                        $objPeople->Name = $row["Name"];
                        $objPeople->email = $row["email"];
                        $objPeople->phone = $row["phone"];
                        $objPeople->role = $row["role"];
                    }
                    $result->free();
                    return $objPeople;
                }
            } catch (\Exception $e) {
                Logger::log($conn, "PeopleDAL::loadById", "error", $e->getMessage());
            }
        }
        
        public static function loadByRole($conn, $role) {
            try {
                $list = [];
                //print_r("select id,Name,email,phone,role from people where id=" . $conn->escape($id));

                if ($result = $conn->query("select id,Name,email,phone,role from people where role='" . $conn->escape($role)."'")) {
                    $objPeople = new People;

                    while ($row = $result->fetch_assoc()) {
                        $objPeople->id = $row["id"];
                        $objPeople->Name = $row["Name"];
                        $objPeople->email = $row["email"];
                        $objPeople->phone = $row["phone"];
                        $objPeople->role = $row["role"];
                    }
                    $result->free();
                    return $objPeople;
                }
            } catch (\Exception $e) {
                Logger::log($conn, "PeopleDAL::loadById", "error", $e->getMessage());
            }
        }

        public static function create($conn, $objPeople) {
            try {
                $sql = "insert into people(Name,email,phone,role) values(" . "'" . $conn->escape($objPeople->Name) . "'" . ",'" . $conn->escape($objPeople->email) . "'" . ",'" . $conn->escape($objPeople->phone) . "'" . ",'" . $conn->escape($objPeople->role) . "'" . ")";
                $conn->query($sql, "People::create", 0);
                $conn->close();
            } catch (\Exception $e) {
                Logger::log($conn, "PeopleDAL::create", "error", $e->getMessage());
            }
        }

        public static function update($conn, $objPeople) {

            try {
                $sql = "update people set " . "Name='" . $conn->escape($objPeople->Name) . "'" . ",email='" . $conn->escape($objPeople->email) . "'" . ",phone='" . $conn->escape($objPeople->phone) . "'" . ",role='" . $conn->escape($objPeople->role) . "'" . " where id=" . $conn->escape($objPeople->id) ;
                Common::jsonSuccess(array("query"=>$sql));
                $conn->query($sql, "People::create", 0);
                $conn->close();
            } catch (\Exception $e) {
                Logger::log($conn, "PeopleDAL::update", "error", $e->getMessage());
            }
        }

        public static function delete($conn, $id) {
            try {
                $sql = "delete from people  where id=" . $conn->escape($id);
                $conn->query($sql, "People::create", 0);
                $conn->close();
            } catch (\Exception $e) {
                Logger::log($conn, "PeopleDAL::update", "error", $e->getMessage());
            }
        }

    }

    class LeaveTypes {
        public $ID = null;
        public $type = null;
    }

    class LeaveTypesDAL {
        public static function loadAll($conn) {
            try {
                $list = [];
    
                if ($result = $conn->query("SELECT * FROM leavetypes")) {
                    while ($row = $result->fetch_assoc()) {
                        $obj = new LeaveTypes;
    
                        // Populate the Leavetypes object with data from the database
                        // Assuming column names match property names in the User class
                        foreach ($row as $key => $value) {
                            $obj->$key = $value;
                        }
    
                        $list[] = $obj;
                    }
                    $result->free();
                }
    
                return $list;
            } catch (\Exception $e) {
                Logger::log($conn, "LeaveTypesDAL::loadAll", "error", $e->getMessage());
            }
        }
        public static function create($conn, $obj) {
            try {
                $sql = "insert into leavetypes (type) 
                        VALUES (" . "'" . $conn->escape($obj->type) . "'" . ")";

                $conn->query($sql,"leavetypes::create", 0);
                $conn->close();
            } 
            catch (\Exception $e) {
                Logger::log($conn, "LeaveTypesDAL::create", "error", $e->getMessage());

            }
        }
        public static function update($conn, $obj) {
            try {
                $ID = $obj->ID;
                $leaveType = $obj->type;

                // Assuming your table has columns `LeaveType` and other necessary fields
                $sql = "UPDATE leavetypes SET type = '$leaveType' WHERE ID = $ID";
                $result = $conn->query($sql);

                if ($result) {
                    return true; // Success
                } else {
                    return false; // Failure
                }
            } catch (\Exception $e) {
                Logger::log($conn, "LeaveTypesDAL::update", "error", $e->getMessage());
                return false; // Failure
            }
        }

        public static function delete($conn, $id) {
            try {
                $sql = "DELETE FROM leavetypes WHERE ID=" . $conn->escape($id);
    
                $conn->query($sql);
            } catch (\Exception $e) {
                Logger::log($conn, "AttendanceDAL::delete", "error", $e->getMessage());
            }
        }

    }


    class LeaveStatus {
        public $ID = null;
        public $status = null;
    }

    class LeaveStatusDAL {
        public static function loadAll($conn) {
            try {
                $list = [];
    
                if ($result = $conn->query("SELECT * FROM leavestatus")) {
                    while ($row = $result->fetch_assoc()) {
                        $obj = new LeaveStatus;
    
                        // Populate the LeaveStatus object with data from the database
                        // Assuming column names match property names in the User class
                        foreach ($row as $key => $value) {
                            $obj->$key = $value;
                        }
    
                        $list[] = $obj;
                    }
                    $result->free();
                }
    
                return $list;
            } catch (\Exception $e) {
                Logger::log($conn, "LeaveStatusDAL::loadAll", "error", $e->getMessage());
            }
        }

        public static function read($conn, $ID) {
            try {
                // Assuming your table has columns `LeaveType` and other necessary fields
                $sql = "SELECT * FROM leavestatus WHERE ID = $ID";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $leaveStatus = new LeaveStatus();

                    // Populate the LeaveStatus object with data from the database
                    foreach ($row as $key => $value) {
                        $leaveStatus->$key = $value;
                    }

                    $result->free();
                    return $leaveStatus;
                } else {
                    return null; // No result found
                }
            } catch (\Exception $e) {
                Logger::log($conn, "LeaveStatusDAL::read", "error", $e->getMessage());
                return null; // Failure
            }
        }

        public static function create($conn, $leaveStatus) {
            try {
                $leaveStatus = $leaveStatus->Status;

                // Assuming your table has columns `LeaveType` and other necessary fields
                $sql = "INSERT INTO leavestatus (Status) VALUES ('$leaveStatus')";
                $result = $conn->query($sql);

                if ($result) {
                    return true; // Success
                } else {
                    return false; // Failure
                }
            } catch (\Exception $e) {
                Logger::log($conn, "LeaveStatusDAL::create", "error", $e->getMessage());
                return false; // Failure
            }
        }

        public static function update($conn, $leaveStatus) {
            try {
                $ID = $leaveStatus->ID;
                $leaveStatus = $leaveStatus->Status;

                // Assuming your table has columns `LeaveType` and other necessary fields
                $sql = "UPDATE leavestatus SET Status = '$leaveStatus' WHERE ID = $ID";
                $result = $conn->query($sql);

                if ($result) {
                    return true; // Success
                } else {
                    return false; // Failure
                }
            } catch (\Exception $e) {
                Logger::log($conn, "LeaveStatusDAL::update", "error", $e->getMessage());
                return false; // Failure
            }
        }

        public static function delete($conn, $ID) {
            try {
                // Assuming your table has a column named `ID`
                $sql = "DELETE FROM leavestatus WHERE ID = $ID";
                $result = $conn->query($sql);

                if ($result) {
                    return true; // Success
                } else {
                    return false; // Failure
                }
            } catch (\Exception $e) {
                Logger::log($conn, "LeaveStatusDAL::delete", "error", $e->getMessage());
                return false; // Failure
            }
        }
    }

    class Attendance {
        public $Id = null;
        public $UserId = null;
        public $AttendanceSource = null;
        public $AttendanceType = null;
        public $AttendanceOperation = null;
        public $Latitude = null;
        public $Longitude = null;
        public $CreatedBy = null;
        public $UpdatedBy = null;
        public $CreatedOn = null;
        public $UpdatedOn = null;
    }

    class AttendanceDAL {

        public static function loadAll($conn) {
            try {
                $list = [];
    
                if ($result = $conn->query("SELECT * FROM Attendance")) {
                    while ($row = $result->fetch_assoc()) {
                        $obj = new Attendance;
    
                        // Populate the Leavetypes object with data from the database
                        // Assuming column names match property names in the User class
                        foreach ($row as $key => $value) {
                            $obj->$key = $value;
                        }
    
                        $list[] = $obj;
                    }
                    $result->free();
                }
    
                return $list;
            } catch (\Exception $e) {
                Logger::log($conn, "AttendanceDAL::loadAll", "error", $e->getMessage());
            }
        }

        public static function loadById($conn, $id) {
            try {
                if ($result = $conn->query("SELECT * FROM Attendance WHERE ID=" . $conn->escape($id))) {
                    $objUser = new Attendance;
    
                    while ($row = $result->fetch_assoc()) {
                        // Populate the User object with data from the database
                        foreach ($row as $key => $value) {
                            $objUser->$key = $value;
                        }
                    }
    
                    $result->free();
    
                    return $objUser;
                }
            } catch (\Exception $e) {
                Logger::log($conn, "AttendanceDAL::loadById", "error", $e->getMessage());
            }
        }

        public static function create($conn, $obj) {
            try {
                $sql = "insert into Attendance (UserId, AttendanceSource, AttendanceType, AttendanceOperation, Latitude, Longitude, CreatedOn, CreatedBy, UpdatedOn, UpdatedBy) 
                        VALUES (" . "'" . $conn->escape($obj->UserId) . "'" . ",'"
                                        . $conn->escape($obj->AttendanceSource) . "'" . ",'"
                                        . $conn->escape($obj->AttendanceType) . "'" . ",'"
                                        . $conn->escape($obj->AttendanceOperation) . "'" . ",'"
                                        . $conn->escape($obj->Latitude) . "'" . ",'"
                                        . $conn->escape($obj->Longitude) . "'" . ",'"
                                        . $conn->escape($obj->CreatedOn) . "'" . ",'"
                                        . $conn->escape($obj->CreatedBy) . "'" . ",'"
                                        . $conn->escape($obj->UpdatedOn) . "'" . ",'"
                                        . $conn->escape($obj->UpdatedBy) . "'" . ")";



                $conn->query($sql,"Attendance::create", 0);
                $conn->close();
            } 
            catch (\Exception $e) {
                Logger::log($conn, "AttendanceDAL::create", "error", $e->getMessage());
            }
        }
        
        public static function update($conn, $obj) {
            try {
                $sql = "UPDATE Attendance 
                        SET UserId = '" . $conn->escape($obj->UserId) . "', 
                            AttendanceSource = '" . $conn->escape($obj->AttendanceSource) . "', 
                            AttendanceType = '" . $conn->escape($obj->AttendanceType) . "', 
                            AttendanceOperation = '" . $conn->escape($obj->AttendanceOperation) . "', 
                            Latitude = '" . $conn->escape($obj->Latitude) . "', 
                            Longitude = '" . $conn->escape($obj->Longitude) . "', 
                            CreatedBy = '" . $conn->escape($obj->CreatedBy) . "', 
                            UpdatedBy = '" . $conn->escape($obj->UpdatedBy) . "'
                        WHERE ID = '" . $conn->escape($obj->Id) . "'";
        
                    $conn->query($sql, "Attendance::update", 0);
            } catch (\Exception $e) {
                Logger::log($conn, "AttendanceDAL::update", "error", $e->getMessage());
            }
        }
    
        public static function delete($conn, $id) {
            try {
                $sql = "DELETE FROM Attendance WHERE ID=" . $conn->escape($id);
    
                $conn->query($sql);
            } catch (\Exception $e) {
                Logger::log($conn, "AttendanceDAL::delete", "error", $e->getMessage());
            }
        }
    }

    class Holiday {

        public $ID = null;
        public $Date = null;
        public $Description = null;
    }

    class HolidayDAL {

        public static function loadAll($conn) {
            try {
                $list = [];
    
                if ($result = $conn->query("SELECT * FROM holiday")) {
                    while ($row = $result->fetch_assoc()) {
                        $obj = new Holiday;
    
                        // Populate the Leavetypes object with data from the database
                        // Assuming column names match property names in the User class
                        foreach ($row as $key => $value) {
                            $obj->$key = $value;
                        }
    
                        $list[] = $obj;
                    }
                    $result->free();
                }
    
                return $list;
            } catch (\Exception $e) {
                Logger::log($conn, "LeaveStatusDAL::loadAll", "error", $e->getMessage());
            }
        }

        public static function loadById($conn, $id) {
            try {
                if ($result = $conn->query("SELECT * FROM holiday WHERE ID=" . $conn->escape($id))) {
                    $objUser = new Holiday;
    
                    while ($row = $result->fetch_assoc()) {
                        // Populate the User object with data from the database
                        foreach ($row as $key => $value) {
                            $objUser->$key = $value;
                        }
                    }
    
                    $result->free();
    
                    return $objUser;
                }
            } catch (\Exception $e) {
                Logger::log($conn, "HolidayDAL::loadById", "error", $e->getMessage());
            }
        }

        public static function create($conn, $obj) {
            try {
                $sql = "insert into holiday (Description, Date) 
                        VALUES (" . "'" . $conn->escape($obj->Description) . "'" . ",'"
                                        . $conn->escape($obj->Date) . "'" . ")";



                $conn->query($sql,"Holiday::create", 0);
                $conn->close();
            } 
            catch (\Exception $e) {
                Logger::log($conn, "HolidayDAL::create", "error", $e->getMessage());
            }
        }
    
        public static function update($conn, $obj) {
            try {
                $sql = "UPDATE holiday 
                        SET Description = '" . $conn->escape($obj->Description) . "', 
                            Date = '" . $conn->escape($obj->Date) . "'
                        WHERE ID = '" . $conn->escape($obj->ID) . "'";
        
                    $conn->query($sql, "Holiday::create", 0);
            } catch (\Exception $e) {
                Logger::log($e->getMessage());
            }

        }
    
        public static function delete($conn, $id) {
            try {
                $sql = "DELETE FROM holiday WHERE ID=" . $conn->escape($id);
    
                $conn->query($sql);
            } catch (\Exception $e) {
                Logger::log($conn, "HolidayDAL::delete", "error", $e->getMessage());
            }
        }
    }
    class UserLeaves {
        public $ID = null;
        public $UserID = null;
        public $LeaveTypeID = null;
        public $LeaveFrom = null;
        public $LeaveTo = null;
        public $StatusID = null;
        public $Reason = null;
        public $Notes = null;
        public $StatusChangedDate = null;
        public $StatusChangedBy = null;
        public $CreatedOn = null;
        public $CreatedBy = null;
        public $UpdatedOn = null;
        public $UpdatedBy = null;
    }

    class UserLeavesDAL {
        public static function loadAll($conn) {
            try {
                $list = [];
    
                if ($result = $conn->query("SELECT * FROM UserLeaves")) {
                    while ($row = $result->fetch_assoc()) {
                        $obj = new UserLeaves;
    
                        // Populate the UserLeaves object with data from the database
                        // Assuming column names match property names in the User class
                        foreach ($row as $key => $value) {
                            $obj->$key = $value;
                        }
    
                        $list[] = $obj;
                    }
                    $result->free();
                }
    
                return $list;
            } catch (\Exception $e) {
                Logger::log($conn, "UserLeavesDAL::loadAll", "error", $e->getMessage());
            }
        }
        public static function create($conn, $objUser) {
            try {
                $sql = "insert into UserLeaves(UserID,LeaveTypeID,LeaveFrom,LeaveTo,StatusID,Reason,Notes,
                StatusChangedDate,StatusChangedBy,CreatedOn,CreatedBy,UpdatedOn,UpdatedBy) values
                (" . "'" . $conn->escape($objUser->UserID) . "'" . ",'"
                                        . $conn->escape($objUser->LeaveTypeID) . "'" . ",'"
                                        . $conn->escape($objUser->LeaveFrom) . "'" . ",'"
                                        . $conn->escape($objUser->LeaveTo) . "'" . ",'"
                                        . $conn->escape($objUser->StatusID) . "'" . ",'"
                                        . $conn->escape($objUser->Reason) . "'" . ",'"
                                        . $conn->escape($objUser->Notes) . "'" . ",'"
                                        . $conn->escape($objUser->StatusChangedDate) . "'" . ",'"
                                        . $conn->escape($objUser->StatusChangedBy) . "'" . ",'"
                                        . $conn->escape($objUser->CreatedOn) . "'" . ",'"
                                        . $conn->escape($objUser->CreatedBy) . "'" . ",'"
                                        . $conn->escape($objUser->UpdatedOn) . "'" . ",'"
                                        . $conn->escape($objUser->UpdatedBy) . "'" . ")";

                $conn->query($sql, "UserLeaves::create", 0);
                $conn->close();
            } catch (\Exception $e) {
                Logger::log($conn, "UserLeavesDAL::create", "error", $e->getMessage());
            }
        }
        public static function update($conn, $objUser) {
            try {
                $sql = "UPDATE UserLeaves 
                        SET UserID = '" . $conn->escape($objUser->UserID) . "', 
                            LeaveTypeID = '" . $conn->escape($objUser->LeaveTypeID) . "', 
                            LeaveFrom = '" . $conn->escape($objUser->LeaveFrom) . "', 
                            LeaveTo = '" . $conn->escape($objUser->LeaveTo) . "', 
                            StatusID = '" . $conn->escape($objUser->StatusID) . "', 
                            Reason = '" . $conn->escape($objUser->Reason) . "', 
                            Notes = '" . $conn->escape($objUser->Notes) . "',
                            StatusChangedDate = '" . $conn->escape($objUser->StatusChangedDate) . "'
                            StatusChangedBy = '" . $conn->escape($objUser->StatusChangedBy) . "'
                            CreatedOn = '" . $conn->escape($objUser->CreatedOn) . "' 
                            CreatedBy = '" . $conn->escape($objUser->CreatedBy) . "'
                            UpdatedOn = '" . $conn->escape($objUser->UpdatedOn) . "' 
                            UpdatedBy = '" . $conn->escape($objUser->UpdatedBy) . "'
                        WHERE ID = '" . $conn->escape($objUser->id) . "'";
        
                    $conn->query($sql, "UserLeaves::create", 0);
            } catch (\Exception $e) {
                Logger::log($conn, "UserLeavesDAL::update", "error", $e->getMessage());
            }
        }
        public static function delete($conn, $id) {
            try {
                $sql = "DELETE FROM UserLeaves WHERE ID=" . $conn->escape($id);
    
                $conn->query($sql);
            } catch (\Exception $e) {
                Logger::log($conn, "UserLeavesDAL::delete", "error", $e->getMessage());
            }
        }

    }


	

}

