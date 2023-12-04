<?php

require "includes/_begin.php";

// Check if the required key "id" is present in the $_POST array
if (isset($_POST["id"])) 
{
    $id = $_POST["id"];

    // Perform the read operation
    $user = GM_HR\UserDAL::loadById($security->conn, $id);

    if ($user) {
        GM_HR\Common::jsonSuccess($user);
    } else {
        GM_HR\Common::jsonError("User not found with ID $id");
    }
} 
else {
    // Handle the case when the required key is not present in the $_POST array
    GM_HR\Common::jsonError("Missing required POST parameter: id");
}
