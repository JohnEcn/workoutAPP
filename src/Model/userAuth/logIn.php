<?php
require_once("userDB.php");
function getToken($userID)
{
    $dbConnection = new DBconnect();
    
    while(true)
    {
        $newToken = base64_encode(random_bytes(32));
        $tokenExists = $dbConnection->retrieveUserID($newToken);

        if($tokenExists == NULL)
        {
            $dbConnection->insertToken($userID,$newToken);
            $token = $dbConnection->retrieveToken($userID);
            return $token[0]['token'];
            break;
        }    
    }
}
function userLogIn($username,$password)
{   
    if($username !== "" && $password !== "")
    {
        $username = trim($username);
        $dbConnection = new DBconnect();
        $user = $dbConnection->userLogin($username,$password);
        if($user != NULL)
        {
            $authorisation = password_verify($password,$user[0]['password']);
        }
        else
        {
            $authorisation = false;
        }
        if(!$authorisation)
        {
            $status = "Wrong username or password";
            return $status;
        }
        elseif($authorisation)
        {         
            return getToken($user[0]["userID"]);
        }
    }
    else
    {
        $status = "Empty username or password";
        return $status;        
    }


}
?>