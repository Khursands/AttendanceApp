<?php

require "includes/_begin.php";


// Check if the required keys are present in the $_POST array
if (
    isset($_POST["UserId"]) &&
    isset($_POST["AttendanceSource"]) &&
    isset($_POST["AttendanceType"]) &&
    isset($_POST["AttendanceOperation"]) &&
    isset($_POST["Latitude"]) &&
    isset($_POST["Longitude"]) &&
    isset($_POST["CreatedOn"]) &&
    isset($_POST["CreatedBy"]) &&
    isset($_POST["UpdatedOn"]) &&
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
    $obj->Id = isset($_POST["Id"]) ? $_POST["Id"] : 0;

    if (intval($obj->Id) > 0) {
        GM_HR\AttendanceDAL::update($security->conn, $obj);
        GM_HR\Common::jsonSuccess(array("Data Updated" => $obj));

    } 
    else {
        GM_HR\AttendanceDAL::create($security->conn, $obj);
        $obj->Id = $security->conn->insert_id();
        GM_HR\Common::jsonSuccess(array("New Attendence Created" => $obj));

    }

    
} 
else {
    // Handle the case when the required keys are not present in the $_POST array
    GM_HR\Common::jsonError("Missing required POST parameters.");
}

