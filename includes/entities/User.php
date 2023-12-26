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
                if ($result = $conn->query("SELECT * FROM user WHERE id =" . $conn->escape($id))) {
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
            }  catch (\Exception $e) {
                Logger::log($conn, $e->getMessage());
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
            }  catch (\Exception $e) {
                Logger::log($conn, $e->getMessage());
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
}