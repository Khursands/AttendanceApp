<?php

require "includes/_begin.php";
require "includes/entities/UserLeaves.php";


// Check if the required keys are present in the $_POST array
if (
    isset($_POST["ID"])
) 
{
    $id = $_POST['ID'];

    if (intval($id) > 0) {
        GM_HR\UserLeavesDAL::delete($security->conn, $id);
    } 
    else {
        GM_HR\Common::jsonSuccess(array("id not found" => $id));
    }

    GM_HR\Common::jsonSuccess(array("id Deleted Successfully" => $id));
    
} 
else {
    // Handle the case when the required keys are not present in the $_POST array
    GM_HR\Common::jsonError("Missing required POST parameters.");
}