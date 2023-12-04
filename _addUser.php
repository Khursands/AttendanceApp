<?php

require "includes/_begin.php";

// Check if the required keys are present in the $_POST array
if (
    isset($_POST["Name"]) &&
    isset($_POST["email"]) &&
    isset($_POST["phone"]) &&
    isset($_POST["role"])
) {
    $objPeople = new GM_HR\People;
    $objPeople->Name = $_POST["Name"];
    $objPeople->email = $_POST["email"];
    $objPeople->phone = $_POST["phone"];
    $objPeople->role = $_POST["role"];
    $objPeople->id = isset($_POST["id"]) ? $_POST["id"] : 0;

    if (intval($objPeople->id) > 0) {
        GM_HR\PeopleDAL::update($security->conn, $objPeople);
    } else {
        GM_HR\PeopleDAL::create($security->conn, $objPeople);
        $objPeople->id = $security->conn->insert_id();
    }

    GM_HR\Common::jsonSuccess(array("id" => $objPeople->id));
} else {
    // Handle the case when the required keys are not present in the $_POST array
    GM_HR\Common::jsonError("Missing required POST parameters.");
}
