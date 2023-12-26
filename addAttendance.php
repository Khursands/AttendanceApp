<?php

require "includes/_begin.php";
require "includes/entities/Attendance.php";

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
            isset($userPayload["AttendanceSource"]) &&
            isset($userPayload["AttendanceType"]) &&
            isset($userPayload["AttendanceOperation"]) &&
            isset($userPayload["Latitude"]) &&
            isset($userPayload["Longitude"]) &&
            isset($userPayload["CreatedBy"]) &&
            isset($userPayload["UpdatedBy"])
        ) {
            // Create User object and perform operations as before
            $objUser = new GM_HR\Attendance;
            $objUser->UserId = $userPayload["UserId"];
            $objUser->AttendanceSource = $userPayload["AttendanceSource"];
            $objUser->AttendanceType = $userPayload["AttendanceType"];
            $objUser->AttendanceOperation = $userPayload["AttendanceOperation"];
            $objUser->Latitude = $userPayload["Latitude"];
            $objUser->Longitude = $userPayload["Longitude"];
            $objUser->CreatedBy = $userPayload["CreatedBy"];
            $objUser->UpdatedBy = $userPayload["UpdatedBy"];
            $objUser->ID = isset($userPayload["ID"]) ? $userPayload["ID"] : 0;

            if (intval($objUser->ID) > 0) {
                GM_HR\AttendanceDAL::update($security->conn, $objUser);
                GM_HR\Common::jsonSuccess(array("Data" => $objUser));
            } 
            else {
                GM_HR\AttendanceDAL::create($security->conn, $objUser);
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

else{
    if (
    isset($_POST["UserId"]) &&
    isset($_POST["AttendanceSource"]) &&
    isset($_POST["AttendanceType"]) &&
    isset($_POST["AttendanceOperation"]) &&
    isset($_POST["Latitude"]) &&
    isset($_POST["Longitude"]) &&
    isset($_POST["CreatedBy"]) &&
    isset($_POST["UpdatedBy"])
) 
{
    $obj = new GM_HR\Attendance;
    $obj->UserId = $_POST["UserId"];
    $obj->AttendanceSource = $_POST["AttendanceSource"];
    $obj->AttendanceType = $_POST["AttendanceType"];
    $obj->AttendanceOperation = $_POST["AttendanceOperation"];
    $obj->Latitude = $_POST["Latitude"];
    $obj->Longitude = $_POST["Longitude"];
    $obj->CreatedOn = $_POST["CreatedOn"];
    $obj->UpdatedOn = $_POST["UpdatedOn"];
    $obj->CreatedBy = $_POST["CreatedBy"];
    $obj->UpdatedBy = $_POST["UpdatedBy"];
    $obj->ID = isset($_POST["ID"]) ? $_POST["ID"] : 0;

    if (intval($obj->ID) > 0) {
        GM_HR\AttendanceDAL::update($security->conn, $obj);
        GM_HR\Common::jsonSuccess(array("Data Updated" => $obj));

    } 
    else {
        GM_HR\AttendanceDAL::create($security->conn, $obj);
        $obj->ID = $security->conn->insert_id();
        GM_HR\Common::jsonSuccess(array("New Attendance Created" => $obj));

    }

    
} 
else {
    // Handle the case when the required keys are not present in the $_POST array
    GM_HR\Common::jsonError("Missing required POST parameters.");
}

}