<?php

function userAuth($parameters)
{   
    $response = ["status"=>"","message"=>""];
    if($parameters !=null && array_key_exists ( "user" , $parameters ) && array_key_exists ( "pass" , $parameters ))
    {
            require_once("../Model/userAuth/login.php");
            $token = userLogIn($parameters["user"],$parameters["pass"]);
            
            if($token == "WRONG USERNAME OR PASSWORD" || $token == "EMPTY USERAME OR PASSWORD")
            {
                $response["status"] = "401 (UNATHORIZED)";
                $response["message"] = $token;
            }
            else
            {
                $response["status"] = "200 (OK)";
                $response["message"] = "AUTHORISATION SUCESSFULL";
                $response["token"] = $token;
            }
    }
    else
    {
        $response["status"] = "400 (BAD REQUEST)";
        $response["message"] = "REQUIRED FIELDS MISSING";
    }
    return $response;
}
function userSignUp($parameters)
{
    $response = ["status"=>"","message"=>""];
    if($parameters !=null && array_key_exists ( "user" , $parameters ) && array_key_exists ( "pass" , $parameters ) && array_key_exists ( "email" , $parameters ) && array_key_exists ( "passconf" , $parameters ))
    {       
            require_once("../Model/userAuth/signUp.php");
            $status = userInsert($parameters["user"],$parameters["email"],$parameters["pass"],$parameters["passconf"]);
            
            if($status == "SUCCESS")
            {
                $response["status"] = "200 (OK)";
                $response["message"] = "USER REGISTRATION SUCCESSFULL";
            }
            else
            {
                $response["status"] = "401 (UNATHORIZED)";
                $response["message"] = $status;
            }
    }
    else
    {
        $response["status"] = "400 (BAD REQUEST)";
        $response["message"] = "REQUIRED FIELDS MISSING";
    }
    return $response;
    
}
function endAuth($cookies)
{
   $response = ["status"=>"","message"=>""];

   if(isset($cookies['token']))
   {
       require_once("../Model/userAuth/logOut.php");
       $status = userLogout($cookies['token']);
       
       if($status == "UNATHORIZED USER")
       {
           $response["status"] = "401 (UNATHORIZED)";
           $response["message"] = $status;
       }
       else
       {
            $response["status"] = "200 (OK)";
            $response["message"] = "USER AUTHORIZATION ENDED";
       }
       
   }
   else
   {
        $response["status"] = "401 (UNATHORIZED)";
        $response["message"] = "UNATHORIZED USER";
   }
   return $response;
}
function checkAuth($cookies)
{   
    require_once("../Model/userAuth/checkAuth.php");
    if(isset($cookies['token']))
    {
        $token = $cookies['token'];
        $userID = getUserID($token);
    }
    else
    {
        $userID = NULL;
    }
    
    return $userID;
}
function saveWorkout($httpBody,$userID)
{   $response = [];
    require_once("../Model/userWorkouts/workoutHandler.php");
    $status  = insertWorkout($httpBody,$userID);
    
    if($status == "SUCCESS")
    {   
        $response["status"] = "200 (OK)";
        $response["message"] = $status;
    }
    else
    {
        $response["status"] = "400 (BAD REQUEST)";
        $response["message"] = $status;        
    }
    return $response;
}
function getWorkout($workoutID,$userID)
{  
    $response = ["status"=>"","message"=>""];    
    
    require_once("../Model/userWorkouts/workoutHandler.php");
    $responseWorkout = retrieveWorkout($workoutID,$userID);
    if($responseWorkout != NULL)
    {   $response["status"] = "200 (OK)";
        $response["message"] = $responseWorkout;
    }
    else
    {
        $response["status"] = "400 (BAD REQUEST)";
        $response["message"] = "INVALID WORKOUT ID";        
    }

    return $response;    
}
?>

