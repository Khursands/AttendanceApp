<?php

require "includes/_begin.php";

// Check if the required keys are present in the $_POST array
if (
    isset($_POST["UserID"]) &&
    isset($_POST["LeaveTypeID"]) &&
    isset($_POST["LeaveFrom"]) &&
    isset($_POST["LeaveTo"]) &&
    isset($_POST["StatusID"]) &&
    isset($_POST["Reason"]) &&
    isset($_POST["Notes"]) &&
    isset($_POST["StatusChangedDate"]) &&
    isset($_POST["StatusChangedBy"]) &&
    isset($_POST["CreatedOn"]) &&
    isset($_POST["CreatedBy"])&&
    isset($_POST["UpdatedOn"]) &&
    isset($_POST["UpdatedBy"])
) 
{
    $objUser = new GM_HR\UserLeaves;
    $objUser->UserID = $_POST["UserID"];
    $objUser->LeaveTypeID = $_POST["LeaveTypeID"];
    $objUser->LeaveFrom = $_POST["LeaveFrom"];
    $objUser->LeaveTo = $_POST["LeaveTo"];
    $objUser->StatusID = $_POST["StatusID"];
    $objUser->Reason = $_POST["Reason"];
    $objUser->Notes = $_POST["Notes"];
    $objUser->StatusChangedDate = $_POST["StatusChangedDate"];
    $objUser->StatusChangedBy = $_POST["StatusChangedBy"];
    $objUser->CreatedOn = $_POST["CreatedOn"];
    $objUser->CreatedBy = $_POST["CreatedBy"];
    $objUser->UpdatedOn = $_POST["UpdatedOn"];
    $objUser->UpdatedBy = $_POST["UpdatedBy"];
    $objUser->ID = isset($_POST["ID"]) ? $_POST["ID"] : 0;

    if (intval($objUser->ID) > 0) {
        GM_HR\UserLeavesDAL::update($security->conn, $objUser);
        GM_HR\Common::jsonSuccess(array("Data" => $objUser));

    } 
    else {
        GM_HR\UserLeavesDAL::create($security->conn, $objUser);
        $objUser->ID = $security->conn->insert_id();
        GM_HR\Common::jsonSuccess(array("New user Created with id " => $objUser->ID));

    }

    
} 
else {
    // Handle the case when the required keys are not present in the $_POST array
    GM_HR\Common::jsonError("Missing required POST parameters.");
}


