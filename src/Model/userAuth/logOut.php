<?php
require_once("userDB.php");

function userLogout($token){
    $dbConnection = new DBconnect();
    $user = $dbConnection->retrieveUserID($token);

    if(isset($user[0]['userID']))
    {           
        $dbConnection->insertToken($user[0]['userID'],NULL);
        return "USER AUTHORIZATION ENDED";
    }
    else
    {
        return "UNATHORIZED USER";
    }
}



?>