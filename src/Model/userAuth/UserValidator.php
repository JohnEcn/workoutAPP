<?php

class UserValidator{

    private  $passRegex = '^\S*(?=\S{6,16})(?=\S*[a-zA-Z])(?=\S*[\d])\S*$^';
    private  $usernameRegex = "^[\w.]{6,16}+$^";
    private  $symbolsRegex = "/[#$%^&*()+=\-\[\]\';,\/{}|\":<>?~\\\\]/";
    
    public function __construct($username,$email,$password,$passwordConfirmation)
    {
        $this->validateUsername($username);
        $this->validateEmail($email);
        $this->validatePassword($password,$passwordConfirmation);     
    }

    private function validatePassword($pass,$passConf)
    {
        if(!preg_match($this->passRegex,$pass) || $pass != $passConf || preg_match($this->symbolsRegex,$pass) || strpos($pass," "))
        {
            throw new Exception("Password is Invalid");
        }
    }
    private function validateEmail($mail)
    {                 
        if(!filter_var($mail, FILTER_VALIDATE_EMAIL))
        {
            throw new Exception("Email is Invalid");
        }
    }
    private function validateUsername($user)
    {    
        $user = trim($user);
        $user = strtolower($user);

        $dbConnection = new DBconnect();
        $usernameValid = $dbConnection->checkUserUnique($user);

        if(count($usernameValid) != 0){
            throw new Exception("Username already exists");
        }
        if(!preg_match($this->usernameRegex,$user) || preg_match($this->symbolsRegex,$user)){
            throw new Exception("Username is Invalid");
        }
    }
}
?>
