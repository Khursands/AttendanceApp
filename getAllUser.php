<?php

require "includes/_begin.php";

{

    // Perform the read operation
    $id = GM_HR\UserDAL::loadAll($security->conn);

    if ($id) {
        GM_HR\Common::jsonSuccess($id);
    } else {
        GM_HR\Common::jsonError("User not found with ID");
    }
} 
