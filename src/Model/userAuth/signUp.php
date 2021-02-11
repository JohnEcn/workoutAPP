<?php
require_once("userDB.php");

function userInsert($username,$email,$password,$passwordConfirmation)
{   
    $errorLog = [];
    $username  = validateUsername($username,$errorLog);
    $email = validateEmail($email,$errorLog);
    $password = validatePassword($password,$passwordConfirmation,$errorLog);

    if($username != false && $email != false && $password != false)
    {  
        $dbConnection = new DBconnect();
        $password = password_hash($password,PASSWORD_DEFAULT);
        $status = $dbConnection->insertUser($username,$password,$email);
        return $status;
    }
    else
    {
        return $errorLog;
    }    
}
function validatePassword($pass,$passConf,&$errorLog){
    if($pass == "")
    {
         $errorLog["PASSWORD"] = "EMPTY"; 
          return false;
    }
    $passLengthValid = 6 < strlen($pass) && strlen($pass) < 16 ? true : false;
    $notContainSpace = strpos($pass," ") == false ? true : false;
    $containsDigit = preg_match('~[0-9]+~',$pass) === 1? true : false;
    $containLetter = preg_match('~[a-zA-Z]~',$pass) === 1? true : false;
    $NotcontainSymbol = preg_match('/[\'^£$%&*()}{@#~?><;>,|=+¬-]/', $pass) === 1? false : true;
    $passMatch = $pass === $passConf ? true : false;

    if($passLengthValid && $containLetter && $notContainSpace && $containsDigit && $passMatch  && $NotcontainSymbol)
    {  
        return $pass;
    }
    else
    {   $errorLog["PASSWORD"] = "INVALID";
        return false;
    }
}
function validateEmail($mail,&$errorLog){
    if($mail !== "")
    { 
        $mail = trim($mail);
        
        if(filter_var($mail, FILTER_VALIDATE_EMAIL))
        {
            return  $mail;
        }
        else
        {   
            $errorLog["EMAIL"] = "INVALID";         
            return false;
        }        
    }
    else
    {     $errorLog["EMAIL"] = "EMPTY"; 
          return false;
    }
}
function validateUsername($user,&$errorLog){
    if($user !== "")
    { 
        $user = trim($user);
        $user = strtolower($user);

        $dbConnection = new DBconnect();
        $usernameValid = $dbConnection->checkUserUnique($user);

        if(count($usernameValid) != 0){
            $errorLog["USERNAME"] = "ALREADY EXISTS";
            return false;
        }

        $passLengthValid = 6 <= strlen($user) && strlen($user) <= 16 ? true : false;
        $notContainSpace = strpos($user," ") == false ? true : false;
        $containLetter = preg_match('~[a-zA-Z]~',$user) === 1? true : false;
        $NotcontainSymbol = preg_match('/[\'^£$%&*()}{@#~?><;>,|=+¬-]/', $user) === 1? false : true;
    

        if($passLengthValid && $containLetter && $notContainSpace  && $NotcontainSymbol)
        {  
            return $user;
        }
        elseif(!$passLengthValid)
        {   
            $errorLog["USERNAME"] = "INVALID LENGTH";       
            return false;
        }        
        else
        {
            $errorLog["USERNAME"] = "INVALID";   
        }
            
    }
    else
    {        
         $errorLog["USERNAME"] = "EMPTY"; 
          return false;
    }
}
?>