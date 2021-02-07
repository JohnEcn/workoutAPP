<?php
require_once("userDB.php");
function getToken($userID)
{
    $dbConnection = new DBconnect();
    
    $newToken = base64_encode(random_bytes(32));
    $dbConnection->insertToken($userID,$newToken);
    $token = $dbConnection->retrieveToken($userID);
    return $token[0]['token'];
}
function userLogIn($username,$password)
{   
    if($username !== "" && $password !== "")
    {
        $username = trim($username);
        $dbConnection = new DBconnect();
        $user = $dbConnection->userLogin($username,$password);
        if($user != null)
        {
            $authorisation = password_verify($password,$user[0]['password']);
        }
        else
        {
            $authorisation = false;
        }
        if(!$authorisation)
        {
            $status = "WRONG USERNAME OR PASSWORD";
            return $status;
        }
        elseif($authorisation)
        {         
            return getToken($user[0]["userID"]);
        }
    }
    else
    {
        $status = "EMPTY USERAME OR PASSWORD";
        return $status;        
    }


}
?>