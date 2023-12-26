<?php
namespace GM_HR {

    class LeaveStatus {
        public $ID = null;
        public $Status = null;
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


}

