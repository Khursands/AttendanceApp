<?php

require "includes/_begin.php";
require "includes/entities/LeaveStatus.php";

{

    // Perform the read operation
    $obj = GM_HR\LeaveStatusDAL::loadAll($security->conn);

    if ($obj) {
        GM_HR\Common::jsonSuccess($obj);
    } else {
        GM_HR\Common::jsonError("LeaveStatus not found");
    }
} 
