<?php

require "includes/_begin.php";
require "includes/entities/LeaveStatus.php";

// Check if the required keys are present in the $_POST array
if (
    isset($_POST["Status"]) 
) 
{
    $objUser = new GM_HR\leavestatus;
    // $objUser->Username = $_POST["Username"];
    $objUser->Status = $_POST["Status"];
    
    $objUser->id = isset($_POST["id"]) ? $_POST["id"] : 0;

    if (intval($objUser->id) > 0) {
        GM_HR\LeaveStatusDAL::update($security->conn, $objUser);
        GM_HR\Common::jsonSuccess(array("Data" => $objUser));

    } 
    else {
        GM_HR\LeaveStatusDAL::create($security->conn, $objUser);
        $objUser->id = $security->conn->insert_id();
        GM_HR\Common::jsonSuccess(array("New user Created with id " => $objUser->id));
    }

    
} 
else {
    // Handle the case when the required keys are not present in the $_POST array
    GM_HR\Common::jsonError("Missing required POST parameters.");
}


// Check if the required keys are present in the $_POST array