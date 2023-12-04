<?php
require "includes/_begin.php";
require "includes/entities/User.php";

// Check if the required keys are present in the $_POST array
if (
    isset($_POST["Username"])&&
    isset($_POST["Email"]) &&
    isset($_POST["Password"]) &&
    isset($_POST["Mobile"]) &&
    isset($_POST["Phone"]) &&
    isset($_POST["Address"]) &&
    isset($_POST["EmploymentStatus"]) &&
    isset($_POST["CreatedBy"]) &&
    isset($_POST["UpdatedBy"])
) 
{
    $objUser = new GM_HR\User;
    $objUser->Username = $_POST["Username"];
    $objUser->Email = $_POST["Email"];
    $objUser->Password = $_POST["Password"];
    $objUser->Mobile = $_POST["Mobile"];
    $objUser->Phone = $_POST["Phone"];
    $objUser->Address = $_POST["Address"];
    $objUser->EmploymentStatus = $_POST["EmploymentStatus"];
    $objUser->CreatedBy = $_POST["CreatedBy"];
    $objUser->UpdatedBy = $_POST["UpdatedBy"];

    GM_HR\Common::jsonSuccess(array("User" => $objUser));

    GM_HR\UserDAL::create($security->conn, $objUser);
    $objUser->UserId = $security->conn->insert_id();
    

    GM_HR\Common::jsonSuccess(array("ID" => $objUser->ID));
} 
else {
    // Handle the case when the required keys are not present in the $_POST array
    GM_HR\Common::jsonError("Missing required POST parameters.");
}
