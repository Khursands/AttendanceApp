<?php

require "includes/_begin.php";
require "includes/entities/Holiday.php";

// Check if the required keys are present in the $_POST array
if (
    isset($_POST["Date"]) &&
    isset($_POST["Description"])
) 
{
    $obj = new GM_HR\Holiday;
    $obj->Date = $_POST["Date"];
    $obj->Description = $_POST["Description"];
    $obj->ID = isset($_POST["id"]) ? $_POST["id"] : 0;

    if (intval($obj->ID) > 0) {
        GM_HR\HolidayDAL::update($security->conn, $obj);
        GM_HR\Common::jsonSuccess(array("Data" => $obj));

    } 
    else {
        GM_HR\HolidayDAL::create($security->conn, $obj);
        $obj->ID = $security->conn->insert_id();
        GM_HR\Common::jsonSuccess(array("New user Created with ID " => $obj->ID));

    }

    
} 
else {
    // Handle the case when the required keys are not present in the $_POST array
    GM_HR\Common::jsonError("Missing required POST parameters.");
}


