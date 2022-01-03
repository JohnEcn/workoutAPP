<?php
require_once("userDB.php");
require_once("UserValidator.php");

class User{

    private $userID;
    private $userName;
    private $token;
    private $userDb;

    public function __construct(){
        $this->userDb = new DBconnect();
    } 
    public function newUser($username,$email,$password,$passwordConfirmation)
    {
        try
        {            
            new UserValidator($username,$email,$password,$passwordConfirmation);
            $this->userInsert($username,$email,$password,$passwordConfirmation);
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }
    public function loginUser($username,$password)
    {
        try
        {
            $this->userLogIn($username,$password);
        }
        catch(Exception $e)
        {
            throw $e;
        }        
    }
    public function authenticateUser($token)
    {        
        $user = $this->userDb->retrieveUserID($token);
        if(isset($user[0]['userID']))
        {
            $this->userName = $user[0]["username"];
            $this->userID = $user[0]["userID"];
            $this->token = $token;
        }
        else
        {
            throw new Exception("User not found");
        }        
    }
    public function logoutUser()
    {
        try
        { 
            $this->userDb->insertToken($this->userID,NULL); 
            $this->userName = null;
            $this->userID = null;
            $this->token = null;
        }
        catch(Exception $e)
        {
            throw $e;
        }        
    }
    public function getToken()
    {
        return $this->token;        
    }
    public function getUserName()
    {
        return $this->userName;         
    }
    public function getUserId()
    {
        return $this->userID;             
    }    
    
    private function initializeToken()
    {    
        while(true)
        {
            $newToken = base64_encode(random_bytes(32));
            $tokenExists = $this->userDb->retrieveUserID($newToken);

            if($tokenExists == NULL)
            {
                $this->userDb->insertToken($this->userID,$newToken);
                $token = $this->userDb->retrieveToken($this->userID);
                $this->token = $token[0]['token'];
                break;
            }  
        }
    }
    private function userLogIn($username,$password)
    {   
        if($username == "" || $password == "")
        {
            throw new Exception("Empty username or password");
        }        
        
        $username = trim($username);
        $user = $this->userDb->userLogin($username,$password);
        $authorisationStatus = false;
        if($user != NULL)
        {
            $authorisationStatus = password_verify($password,$user[0]['password']);
        }        
        if(!$authorisationStatus)
        {
            throw new Exception("Wrong username or password");
        }
        elseif($authorisationStatus)
        {     
            $this->userName = $username;
            $this->userID = $user[0]["userID"];
            $this->initializeToken();
        }         
    }       
    private function userInsert($username,$email,$password,$passwordConfirmation)
    {      
        try
        {
            $password = password_hash($password,PASSWORD_DEFAULT);
            $this->userDb->insertUser($username,$password,$email); 
        } 
        catch(Exception $e)
        {
            throw new Exception("Unexpected db error");
        }          
    }
}
?>