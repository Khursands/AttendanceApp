<?php

require "includes/_begin.php";

{
    // Perform the read operation
    $obj = GM_HR\AttendanceDAL::loadAll($security->conn);

    if ($obj) {
        GM_HR\Common::jsonSuccess($obj);
    } else {
        GM_HR\Common::jsonError("Attendance data not found with ID $id");
    }
} 
