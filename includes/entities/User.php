<?php

// namespace GM_HR {

//     class User {
//         public $id = null;
//         public $Username = null;
//         public $Email = null;
//         public $Password = null;
//         public $Mobile = null;
//         public $Phone = null;
//         public $Address = null;
//         public $EmploymentStatus = null;
//         public $CreatedOn = null;
//         public $CreatedBy = null;
//         public $UpdatedOn = null;
//         public $UpdatedBy = null;
//         public $JoiningDate = null;
//     }
    
//     class UserDAL {

//         public static function loadAll($conn) {
//             try {
//                 $list = [];
    
//                 if ($result = $conn->query("SELECT * FROM users")) {
//                     while ($row = $result->fetch_assoc()) {
//                         $objUser = new User;
    
//                         // Populate the User object with data from the database
//                         // Assuming column names match property names in the User class
//                         foreach ($row as $key => $value) {
//                             $objUser->$key = $value;
//                         }
    
//                         $list[] = $objUser;
//                     }
//                     $result->free();
//                 }
    
//                 return $list;
//             } catch (\Exception $e) {
//                 Logger::log($conn, "UserDAL::loadAll", "error", $e->getMessage());
//             }
//         }
    
//         public static function loadById($conn, $id) {
//             try {
//                 if ($result = $conn->query("SELECT * FROM users WHERE ID=" . $conn->escape($id))) {
//                     $objUser = new User;
    
//                     while ($row = $result->fetch_assoc()) {
//                         // Populate the User object with data from the database
//                         foreach ($row as $key => $value) {
//                             $objUser->$key = $value;
//                         }
//                     }
    
//                     $result->free();
    
//                     return $objUser;
//                 }
//             } catch (\Exception $e) {
//                 Logger::log($conn, "UserDAL::loadById", "error", $e->getMessage());
//             }
//         }

//         public static function create($conn, $objUser) {
//             try {
//                 $sql = "insert into users (Username, Email, Password, Mobile, Phone, Address, EmploymentStatus, CreatedBy, UpdatedBy) 
//                         VALUES (" . "'" . $conn->escape($objUser->Username) . "'" . ",'"
//                                         . $conn->escape($objUser->Email) . "'" . ",'"
//                                         . $conn->escape($objUser->Password) . "'" . ",'"
//                                         . $conn->escape($objUser->Mobile) . "'" . ",'"
//                                         . $conn->escape($objUser->Phone) . "'" . ",'"
//                                         . $conn->escape($objUser->Address) . "'" . ",'"
//                                         . $conn->escape($objUser->EmploymentStatus) . "'" . ",'"
//                                         . $conn->escape($objUser->CreatedBy) . "'" . ",'"
//                                         . $conn->escape($objUser->UpdatedBy) . "'" . ")";



//                 $conn->query($sql,"User::create", 0);
//                 $conn->close();
//             } 
//             catch (\Exception $e) {
//                 Logger::log($conn, "UserDAL::create", "error", $e->getMessage());
//             }
//         }
    
//         public static function update($conn, $objUser) {
//             try {
//                 $sql = "UPDATE users 
//                         SET Username = '" . $conn->escape($objUser->Username) . "', 
//                             Email = '" . $conn->escape($objUser->Email) . "', 
//                             Password = '" . $conn->escape($objUser->Password) . "', 
//                             Mobile = '" . $conn->escape($objUser->Mobile) . "', 
//                             Phone = '" . $conn->escape($objUser->Phone) . "', 
//                             Address = '" . $conn->escape($objUser->Address) . "', 
//                             EmploymentStatus = '" . $conn->escape($objUser->EmploymentStatus) . "', 
//                             UpdatedOn = NOW(), 
//                             UpdatedBy = '" . $conn->escape($objUser->UpdatedBy) . "', 
//                             JoiningDate = '" . $conn->escape($objUser->JoiningDate) . "' 
//                         WHERE id = " . $conn->escape($objUser->id);
    
//                 $conn->query($sql);
//             } catch (\Exception $e) {
//                 Logger::log($conn, "UserDAL::update", "error", $e->getMessage());
//             }
//         }
    
//         public static function delete($conn, $id) {
//             try {
//                 $sql = "DELETE FROM users WHERE ID=" . $conn->escape($id);
    
//                 $conn->query($sql);
//             } catch (\Exception $e) {
//                 Logger::log($conn, "UserDAL::delete", "error", $e->getMessage());
//             }
//         }
//     }

// }


