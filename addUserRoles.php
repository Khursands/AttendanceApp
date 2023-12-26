<?php

require "includes/_begin.php";
require "includes/entities/UserRoles.php";

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
            isset($userPayload["Role"]) 
        ) {
            // Create User object and perform operations as before
            $objUser = new GM_HR\UserRoles;
            $objUser->Role = $userPayload["Role"];
            $objUser->ID = isset($userPayload["ID"]) ? $userPayload["ID"] : 0;

            if (intval($objUser->ID) > 0) {
                GM_HR\UserRolesDAL::update($security->conn, $objUser);
                GM_HR\Common::jsonSuccess(array("Data" => $objUser));
            } 
            else {
                GM_HR\UserRolesDAL::create($security->conn, $objUser);
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
    isset($_POST["Role"]) 
) 
{
    $obj = new GM_HR\UserRoles;
    $obj->Status = $_POST["Role"];
    $obj->ID = isset($_POST["ID"]) ? $_POST["ID"] : 0;

    if (intval($obj->ID) > 0) {
        GM_HR\UserRolesDAL::update($security->conn, $obj);
        GM_HR\Common::jsonSuccess(array("Data Updated" => $obj));

    } 
    else {
        GM_HR\UserRolesDAL::create($security->conn, $obj);
        $obj->ID = $security->conn->insert_id();
        GM_HR\Common::jsonSuccess(array("New UserRole Created" => $obj));

    }   
} 
else {
    // Handle the case when the required keys are not present in the $_POST array
    GM_HR\Common::jsonError("Missing required POST parameters.");
}

}