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
            isset($userPayload["Username"]) && isset($userPayload["Password"])){
            // Create User object and perform operations as before
            $Username = $userPayload["Username"];
            $Password = $userPayload["Password"];

            $obj = GM_HR\UserDAL::login($security->conn, $Username, $Password);
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
else {
    if (
        isset($_POST["Username"]) &&
        isset($_POST["Email"]) &&
        isset($_POST["Password"]) &&
        isset($_POST["Mobile"]) &&
        isset($_POST["Phone"]) &&
        isset($_POST["Address"]) &&
        isset($_POST["EmploymentStatus"]) &&
        isset($_POST["CreatedBy"]) &&
        isset($_POST["UpdatedBy"])
    ) 
    {
        $objUser = new GM_HR\User;
        $objUser->Username = $_POST["Username"];
        $objUser->Email = $_POST["Email"];
        $objUser->Password = $_POST["Password"];
        $objUser->Mobile = $_POST["Mobile"];
        $objUser->Phone = $_POST["Phone"];
        $objUser->Address = $_POST["Address"];
        $objUser->EmploymentStatus = $_POST["EmploymentStatus"];
        $objUser->CreatedBy = $_POST["CreatedBy"];
        $objUser->UpdatedBy = $_POST["UpdatedBy"];
        $objUser->id = isset($_POST["id"]) ? $_POST["id"] : 0;
    
        if (intval($objUser->id) > 0) {
            GM_HR\UserDAL::update($security->conn, $objUser);
            GM_HR\Common::jsonSuccess(array("Data" => $objUser));
    
        } 
        else {
            GM_HR\UserDAL::create($security->conn, $objUser);
            $objUser->id = $security->conn->insert_id();
            GM_HR\Common::jsonSuccess(array("New user Created with id " => $objUser->id));
    
        }
    
        
    } 
    else {
        // Handle the case when the required keys are not present in the $_POST array
        GM_HR\Common::jsonError("Missing required POST parameters.");
    }
}
