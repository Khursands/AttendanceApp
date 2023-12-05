<?PHP

namespace GM_HR {
    
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
            try {
                $sql = "insert into user (Username, Email, Password, Mobile, Phone, Address, EmploymentStatus, CreatedBy, UpdatedBy) 
                        VALUES (" . "'" . $conn->escape($objUser->Username) . "'" . ",'"
                                        . $conn->escape($objUser->Email) . "'" . ",'"
                                        . $conn->escape($objUser->Password) . "'" . ",'"
                                        . $conn->escape($objUser->Mobile) . "'" . ",'"
                                        . $conn->escape($objUser->Phone) . "'" . ",'"
                                        . $conn->escape($objUser->Address) . "'" . ",'"
                                        . $conn->escape($objUser->EmploymentStatus) . "'" . ",'"
                                        . $conn->escape($objUser->CreatedBy) . "'" . ",'"
                                        . $conn->escape($objUser->UpdatedBy) . "'" . ")";



                $conn->query($sql,"User::create", 0);
                $conn->close();
            } 
            catch (\Exception $e) {
                Logger::log($conn, "UserDAL::create", "error", $e->getMessage());
            }
        }
    
        public static function update($conn, $objUser) {
            try {
                $sql = "UPDATE user 
                        SET Username = '" . $conn->escape($objUser->Username) . "', 
                            Email = '" . $conn->escape($objUser->Email) . "', 
                            Password = '" . $conn->escape($objUser->Password) . "', 
                            Mobile = '" . $conn->escape($objUser->Mobile) . "', 
                            Phone = '" . $conn->escape($objUser->Phone) . "', 
                            Address = '" . $conn->escape($objUser->Address) . "', 
                            EmploymentStatus = '" . $conn->escape($objUser->EmploymentStatus) . "', 
                            UpdatedBy = '" . $conn->escape($objUser->UpdatedBy) . "'
                        WHERE ID = '" . $conn->escape($objUser->id) . "'";
        
                    $conn->query($sql, "User::create", 0);
            } catch (\Exception $e) {
                Logger::log($conn, "UserDAL::update", "error", $e->getMessage());
            }
            // try {
            //     $sql = "update user set " . "Username='" . $conn->escape($objUser->Username) . "'" . ",Email='" . $conn->escape($objUser->Email) . "'" . ",Password='" . $conn->escape($objUser->Password) . "'" . ",Mobile='" . $conn->escape($objUser->Mobile) . "'" . " where id=" . $conn->escape($objUser->id) ;
            //     Common::jsonSuccess(array("query"=>$sql));
            //     $conn->query($sql, "User::create", 0);
            //     $conn->close();
            // } catch (\Exception $e) {
            //     Logger::log($conn, "UserDAL::update", "error", $e->getMessage());
            // }
        }
    
        public static function delete($conn, $id) {
            try {
                $sql = "DELETE FROM user WHERE ID=" . $conn->escape($id);
    
                $conn->query($sql);
            } catch (\Exception $e) {
                Logger::log($conn, "UserDAL::delete", "error", $e->getMessage());
            }
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
                $sql = "delete from people   where id=" . $conn->escape($id);
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
    }



}
