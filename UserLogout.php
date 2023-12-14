<?php
require "includes/_begin.php";

header('Content-Type: application/json');

{
    // Perform the read operation
    $obj = GM_HR\UserDAL::logout();

    if ($obj) {
        GM_HR\Common::jsonSuccess(array("User Logged Out"=>$obj));
    } else {
        GM_HR\Common::jsonError(array("User not logged out or user is already logged out"=> $obj));
    }
} 

?>
