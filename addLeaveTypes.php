<?php
require "includes/_begin.php";
require "includes/entities/LeaveTypes.php";

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
            isset($userPayload["type"])
        ) {
            // Create User object and perform operations as before
            $objUser = new GM_HR\LeaveTypes;
            $objUser->type = $userPayload["type"];
            $objUser->ID = isset($userPayload["ID"]) ? $userPayload["ID"] : 0;

            if (intval($objUser->ID) > 0) {
                GM_HR\LeaveTypesDAL::update($security->conn, $objUser);
                GM_HR\Common::jsonSuccess(array("Data" => $objUser));
            } 
            else {
                GM_HR\LeaveTypesDAL::create($security->conn, $objUser);
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