<?php

require_once("userDB.php");

function getUserID($token)
{    
    $dbConnection = new DBconnect();
    $userID = $dbConnection->retrieveUserID($token);
    if(isset($userID[0]['userID']))
    {
        return $userID[0]['userID'];
    }
    else
    {
        return NULL;
    }
    return $userID[0]['userID'];
}

?>