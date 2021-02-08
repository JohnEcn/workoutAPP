<?php

require_once("userDB.php");

function getUserID($token)
{    
    $dbConnection = new DBconnect();
    $userID = $dbConnection->retrieveUserID($token);
    return $userID[0]['userID'];
}

?>