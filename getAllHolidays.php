<?php

require "includes/_begin.php";
require "includes/entities/Holiday.php";

{

    // Perform the read operation
    $id = GM_HR\HolidayDAL::loadAll($security->conn);

    if ($id) {
        GM_HR\Common::jsonSuccess($id);
    } else {
        GM_HR\Common::jsonError("User not found with ID $id");
    }
} 
