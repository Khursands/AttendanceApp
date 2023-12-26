<?php
namespace GM_HR {

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
}