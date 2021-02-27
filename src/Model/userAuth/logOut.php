<?php
require_once("userDB.php");

function userLogout($token){
    $dbConnection = new DBconnect();
    $user = $dbConnection->retrieveUserID($token);

    if(isset($user[0]['userID']))
    {           
        $dbConnection->insertToken($user[0]['userID'],NULL);
        return "User authentication ended";
    }
    else
    {
        return "Unathorized user";
    }
}



?>