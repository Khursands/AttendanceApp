<?PHP
namespace GM_HR{

require 'vendor/autoload.php';
class UserLeaves {
        public $ID = null;
        public $UserId = null;
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
                $sql = "insert into UserLeaves(UserId,LeaveTypeID,LeaveFrom,LeaveTo,StatusID,Reason,Notes,
                StatusChangedDate,StatusChangedBy,CreatedOn,CreatedBy,UpdatedOn,UpdatedBy) values
                (" . "'" . $conn->escape($objUser->UserId) . "'" . ",'"
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
                        SET UserId = '" . $conn->escape($objUser->UserId) . "', 
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
                        WHERE ID = '" . $conn->escape($objUser->ID) . "'";
        
                    $conn->query($sql, "UserLeaves::create", 0);
            } catch (\Exception $e) {
                Logger::log($conn, "UserLeavesDAL::update", "error", $e->getMessage());
            }
        }
        public static function delete($conn, $id) {
            try {
                $sql = "DELETE FROM UserLeaves WHERE ID =" . $conn->escape($id);
    
                $conn->query($sql, "UserLeaves::delete", 0);
                $conn->close();
            } catch (\Exception $e) {
                Logger::log($conn, "UserLeavesDAL::delete", "error", $e->getMessage());
            }
        }

    }
}