<?php

require "includes/_begin.php";
require "includes/entities/User.php";

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
            isset($userPayload["Email"]) && isset($userPayload["Password"])){
            // Create User object and perform operations as before
            $Email = $userPayload["Email"];
            $Password = $userPayload["Password"];

            $obj = GM_HR\UserDAL::login($security->conn, $Email, $Password);
            GM_HR\Common::jsonSuccess(array($obj));
        } else {
            // Handle the case when the required keys are not present in the decoded JSON data
            GM_HR\Common::jsonError("Missing required parameters in the JSON data.");
        }
    } else {
        // Handle the case when the 'data' key is not present in the decoded JSON data
        GM_HR\Common::jsonError("Invalid JSON data format. 'data' key is missing.");
    }
} 
