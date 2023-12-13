<?php

require "includes/_begin.php";


// Check if the required keys are present in the $_POST array
if (
    isset($_POST["Id"])
) 
{
    $id = $_POST['Id'];

    if (intval($id) > 0) {
        GM_HR\AttendanceDAL::delete($security->conn, $id);
    } 
    else {
        GM_HR\Common::jsonSuccess(array("id not found" => $id));
    }

    GM_HR\Common::jsonSuccess(array("Id Deleted Successfuly" => $id));
    
} 
else {
    // Handle the case when the required keys are not present in the $_POST array
    GM_HR\Common::jsonError(array("Missing required POST parameters id=" => $id));
}