<?PHP
namespace GM_HR{

require 'vendor/autoload.php';

    class Attendance {
        public $ID = null;
        public $UserId = null;
        public $AttendanceSource = null;
        public $AttendanceType = null;
        public $AttendanceOperation = null;
        public $Latitude = null;
        public $Longitude = null;
        public $CreatedOn = null;
        public $CreatedBy = null;
        public $UpdatedOn = null;
        public $UpdatedBy = null;
    }
    
    class AttendanceDAL {

        public static function loadAll($conn) {
            try {
                $list = [];
                if ($result = $conn->query("SELECT * FROM Attendance")) {
                    while ($row = $result->fetch_assoc()) {
                        $objUser = new Attendance;
    
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

        public static function create($conn, $objUser) {
            try {
                $sql = "insert into Attendance (UserId, AttendanceSource, AttendanceType, AttendanceOperation, Latitude, Longitude, CreatedBy, UpdatedBy) 
                        VALUES (" . "'" . $conn->escape($objUser->UserId) . "'" . ",'"
                                        . $conn->escape($objUser->AttendanceSource) . "'" . ",'"
                                        . $conn->escape($objUser->AttendanceType) . "'" . ",'"
                                        . $conn->escape($objUser->AttendanceOperation) . "'" . ",'"
                                        . $conn->escape($objUser->Latitude) . "'" . ",'"
                                        . $conn->escape($objUser->Longitude) . "'" . ",'"
                                        . $conn->escape($objUser->CreatedBy) . "'" . ",'"
                                        . $conn->escape($objUser->UpdatedBy) . "'" . ")";

                // Common::jsonSuccess(array("Query" => $sql));
                $conn->query($sql,"Attendance::create", 0);
                $conn->close();
            } 
            catch (\Exception $e) {
                Logger::log($conn, "AttendanceDAL::create", "error", $e->getMessage());
            }
        }
    
        public static function update($conn, $objUser) {
            try {
                $sql = "UPDATE Attendance
                        SET UserId = '" . $conn->escape($objUser->UserId) . "', 
                            AttendanceSource = '" . $conn->escape($objUser->AttendanceSource) . "',  
                            AttendanceType = '" . $conn->escape($objUser->AttendanceType) . "', 
                            AttendanceOperation = '" . $conn->escape($objUser->AttendanceOperation) . "', 
                            Latitude = '" . $conn->escape($objUser->Latitude) . "', 
                            Longitude = '" . $conn->escape($objUser->Longitude) . "', 
                            CreatedBy = '" . $conn->escape($objUser->CreatedBy) . "',
                            UpdatedBy = '" . $conn->escape($objUser->UpdatedBy) . "'
                        WHERE ID = '" . $conn->escape($objUser->ID) . "'";
        
                    $conn->query($sql, "Attendance::create", 0);
            }  catch (\Exception $e) {
                Logger::log($conn, $e->getMessage());
            }
        }
    //     public static function delete($conn, $id) {
    //         try {
    //             $sql = "delete from user where id=" . $conn->escape($id);
    //             $conn->query($sql, "User::delete", 0);
    //             $conn->close();
    //         } catch (\Exception $e) {
    //             Logger::log($conn, "UserDAL::delete", "error", $e->getMessage());
    //         }
    //     }
    //     public static function login($conn, $email, $password) {
    //         try {
    //             // Fetch user by username
    //             $sql = "SELECT * FROM user WHERE Email = '" . $conn->escape($email) . "'";
    //             if ($result = $conn->query($sql)) {
    //                 $user = $result->fetch_assoc();
    //                 $result->free();
    
    //                 // Check if the user exists and verify the password
    //                 if ($user && password_verify($password, $user['Password'])) {
    //                     // User authenticated
    //                     $token = self::generateJwtToken($user); // You need to implement this method
    
    //                     // Set the token as a cookie
    //                     setcookie('auth_token', $token, time() + (24 * 60 * 60), '/'); // Adjust path, domain, and secure parameters as needed
    
    //                     // Return success
    //                     return Common::jsonSuccess(array("User Logged In "=> True));
    //                 }
    //             }
    //             // Authentication failed
    //             return Common::jsonSuccess(array("Incorrect Username or Password "=> False));
    //         } catch (\Exception $e) {
    //             Logger::log($e->getMessage());
    //             return false;
    //         }
    //     }
    //     public static function logout() 
    //     {
    //         if (isset($_COOKIE['auth_token'])) 
    //         {
    //             unset($_COOKIE['auth_token']);
    //             setcookie('auth_token', '', time() - 3600, '/'); // empty value and old timestamp
    //             return true;
    //         }
    //         // Return success
    //         return false;
    //     }
            
    
    //     private static function generateJwtToken($user) {
    //         $tokenId = base64_encode(random_bytes(32));
    //         $issuedAt = time();
    //         $expirationTime = $issuedAt + (24 * 60 * 60); // Token expiry time (e.g., 24 hours)
        
    //         $payload = [
    //             'id' => $tokenId,
    //             'iat' => $issuedAt,
    //             'exp' => $expirationTime,
    //             'sub' => $user['id'],
    //         ];
        
    //         $secretKey = 'd1cd453f02639be7540186ec35cf39a1e99f5027'; // Change this to a secure secret key
    //         $algorithm = 'HS256'; // Use a secure algorithm like HS256
        
    //         // Use the correct namespace for the JWT class
    //         return \Firebase\JWT\JWT::encode($payload, $secretKey, $algorithm);
    //     }
        
    }
}