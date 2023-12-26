<?php

require "includes/_begin.php";
require "includes/entities/UserLeaves.php";

// Get the raw JSON data from the request body
$jsonData = file_get_contents("php://input");

// Check if the JSON data is not empty
if (!empty($jsonData)) {
    // Decode the JSON data
    $postData = json_decode($jsonData, true);

    // Check if the 'data' key is present in the decoded JSON data
    if (isset($postData['data'])) {
        $userPayload = $postData['data'];

        // Check if the required keys are present in the decoded JSON data
        if (
            isset($userPayload["UserId"]) &&
            isset($userPayload["LeaveTypeID"]) &&
            isset($userPayload["LeaveFrom"]) &&
            isset($userPayload["LeaveTo"]) &&
            isset($userPayload["StatusID"]) &&
            isset($userPayload["Reason"]) &&
            isset($userPayload["Notes"]) &&
            isset($userPayload["StatusChangedDate"]) &&
            isset($userPayload["StatusChangedBy"]) &&
            isset($userPayload["CreatedBy"]) &&
            isset($userPayload["UpdatedBy"]) 
        ) {
            // Create User object and perform operations as before
            $objUser = new GM_HR\UserLeaves;
            $objUser->UserId = $userPayload["UserId"];
            $objUser->LeaveTypeID = $userPayload["LeaveTypeID"];
            $objUser->LeaveFrom = $userPayload["LeaveFrom"];
            $objUser->LeaveTo = $userPayload["LeaveTo"];
            $objUser->StatusID = $userPayload["StatusID"];
            $objUser->Reason = $userPayload["Reason"];
            $objUser->Notes = $userPayload["Notes"];
            $objUser->StatusChangedDate = $userPayload["StatusChangedDate"];
            $objUser->StatusChangedBy = $userPayload["StatusChangedBy"];
            $objUser->CreatedBy = $userPayload["CreatedBy"];
            $objUser->UpdatedBy = $userPayload["UpdatedBy"];
            $objUser->ID = isset($userPayload["ID"]) ? $userPayload["ID"] : 0;

            if (intval($objUser->ID) > 0) {
                GM_HR\UserLeavesDAL::update($security->conn, $objUser);
                GM_HR\Common::jsonSuccess(array("Data" => $objUser));
            } 
            else {
                GM_HR\UserLeavesDAL::create($security->conn, $objUser);
                $insertId = $security->conn->insert_id();
                if ($insertId > 0) {
                    $objUser->ID = $insertId;
                    GM_HR\Common::jsonSuccess(array("New user Created with id" => $objUser->ID));
                } else {
                    GM_HR\Common::jsonError("Failed to create a new user. Insert ID is 0.");
                }
            }
        } else {
            // Handle the case when the required keys are not present in the decoded JSON data
            GM_HR\Common::jsonError("Missing required parameters in the JSON data.");
        }
    } else {
        // Handle the case when the 'data' key is not present in the decoded JSON data
        GM_HR\Common::jsonError("Invalid JSON data format. 'data' key is missing.");
    }
} 
// Check if the required keys are present in the $_POST array
else{
if (
    isset($_POST["UserId"]) &&
    isset($_POST["LeaveTypeID"]) &&
    isset($_POST["LeaveFrom"]) &&
    isset($_POST["LeaveTo"]) &&
    isset($_POST["StatusID"]) &&
    isset($_POST["Reason"]) &&
    isset($_POST["Notes"]) &&
    isset($_POST["StatusChangedDate"]) &&
    isset($_POST["StatusChangedBy"]) &&
    isset($_POST["CreatedBy"]) &&
    isset($_POST["UpdatedBy"])
) 
{
    $objUser = new GM_HR\UserLeaves;
    $objUser->UserId = $_POST["UserId"];
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
}


