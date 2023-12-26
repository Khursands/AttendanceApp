<?php
namespace GM_HR{

class UserRoles {

     public $ID = null;
     public $Role = null;
 }

 class UserRolesDAL {

     public static function loadAll($conn) {
         try {
             $list = [];

             if ($result = $conn->query("SELECT * FROM userroles")) {
                 while ($row = $result->fetch_assoc()) {
                     $obj = new UserRoles;

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
             Logger::log($conn, "UserRolesDAL::loadAll", "error", $e->getMessage());
         }
     }

     public static function loadById($conn, $id) {
         try {
             if ($result = $conn->query("SELECT * FROM UserRoles WHERE ID=" . $conn->escape($id))) {
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
             Logger::log($conn, "UserRolesDAL::loadById", "error", $e->getMessage());
         }
     }

     public static function create($conn, $obj) {
         try {
             $sql = "insert into UserRoles (Role) 
                     VALUES (" . "'" . $conn->escape($obj->Role) . "'" . ")";
             $conn->query($sql,"UserRoles::create", 0);
             $conn->close();
         } 
         catch (\Exception $e) {
             Logger::log($conn, "UserRolesDAL::create", "error", $e->getMessage());
        }
     }

     public static function update($conn, $obj) {
         try {
             $sql = "UPDATE UserRoles 
                     SET  Role = '" . $conn->escape($obj->Role) . "'
                     WHERE ID = '" . $conn->escape($obj->ID) . "'";
    
                 $conn->query($sql, "UserRoles::create", 0);
         }  catch (\Exception $e) {
            Logger::log($conn, $e->getMessage());
        }

     }

     public static function delete($conn, $id) {
         try {
             $sql = "DELETE FROM UserRoles WHERE ID=" . $conn->escape($id);

             $conn->query($sql);
         } catch (\Exception $e) {
             Logger::log($conn, "UserRolesDAL::delete", "error", $e->getMessage());
         }
     }
    }
}