<?php
require_once("userDB.php");

function userLogout($userID)
{
    $dbConnection = new DBconnect();           
    $dbConnection->insertToken($userID,NULL);    
    return "User auth ended";    
}



?>