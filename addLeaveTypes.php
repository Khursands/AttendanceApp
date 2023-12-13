<?php

require "includes/_begin.php";

// Check if the required keys are present in the $_POST array
if (
    isset($_POST["type"])
) 
{
    $obj = new GM_HR\LeaveTypes;
    $obj->type = $_POST["type"];
    $obj->ID = isset($_POST["ID"]) ? $_POST["ID"] : 0;

    if (intval($obj->ID) > 0) {
        GM_HR\LeaveTypesDAL::update($security->conn, $obj);
        GM_HR\Common::jsonSuccess(array("Data Updated" => $obj));

    } 
    else {
        GM_HR\LeaveTypesDAL::create($security->conn, $obj);
        $obj->ID = $security->conn->insert_id();
        GM_HR\Common::jsonSuccess(array("New user Created with id " => $obj->ID));

    }

    
} 
else {
    // Handle the case when the required keys are not present in the $_POST array
    GM_HR\Common::jsonError("Missing required POST parameters.");
}


