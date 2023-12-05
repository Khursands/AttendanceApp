<?php


namespace GM_HR {

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
                Logger::log($conn, "UserDAL::update", "error", $e->getMessage());
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
    
}