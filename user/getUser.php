<?php

require "includes/_begin.php";

// Check if the required key "ID" is present in the $_GET array
if (isset($_GET["ID"])) {
    $userID = $_GET["ID"];

    // Perform the read operation
    $user = GM_HR\UserDAL::loadById($security->conn, $userID);

    if ($user) {
        GM_HR\Common::jsonSuccess($user);
    } else {
        GM_HR\Common::jsonError("User not found with ID $userID");
    }
} else {
    // Handle the case when the required key is not present in the $_GET array
    GM_HR\Common::jsonError("Missing required GET parameter: ID");
}
