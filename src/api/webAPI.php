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
{   
    $response = [];
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
function getWorkoutList($userID)
{
    $response = ["status"=>"","message"=>""];    
    require_once("../Model/userWorkouts/workoutDB.php");
    $conn = new workoutDB;
    $workoutList = $conn->getWorkoutList($userID);

    if(empty($workoutList))
    {
        $response["status"] = "200 (OK)";
        $response["message"] = "WORKOUT LIST EMPTY";        
    }
    else
    {
        $response["status"] = "200 (OK)";
        $response["message"] = $workoutList ; 
    }
    return $response;
}  
function alterWorkoutName($workoutID,$userID,$newName)
{   
    $response = ["status"=>"","message"=>""];    
    
    require_once("../Model/userWorkouts/workoutHandler.php");
    $status = changeWorkoutName($workoutID,$userID,$newName);

    if($status == "SUCCESS")
    {
        $response["status"] = "200 (OK)";
        $response["message"] = "WORKOUT NAME CHANGED"; 
    }
    else
    {
        $response["status"] = "400 (BAD REQUEST)";
        $response["message"] = $status;    
    }    
    return $response;
}
function addNewExercise($workoutID,$userID,$newExercise)
{
    $response = ["status"=>"","message"=>""];    
    
    require_once("../Model/userWorkouts/workoutHandler.php");
    $status = addExercise($workoutID,$userID,$newExercise);

    if($status == "SUCCESS")
    {
        $response["status"] = "200 (OK)";
        $response["message"] = "EXERCISE INSERTED"; 
    }
    else
    {
        $response["status"] = "400 (BAD REQUEST)";
        $response["message"] = $status;    
    }    
    return $response;
    
}
function removeExercise($workoutID,$userID,$exerciseID)
{
    $response = ["status"=>"","message"=>""];    
    
    require_once("../Model/userWorkouts/workoutHandler.php");
    $status = deleteExercise($workoutID,$userID,$exerciseID);
    if($status == "SUCCESS")
    {
        $response["status"] = "200 (OK)";
        $response["message"] = "EXERCISE DELETED"; 
    }
    else
    {
        $response["status"] = "400 (BAD REQUEST)";
        $response["message"] = $status;    
    }    
    return $response;
}
function getTrainingSession($userID)
{
    $response = ["status"=>"","message"=>""];    
    
    require_once("../Model/userTrainingSession/trainSessionHandler.php");
    $trainSession = retrieveTrainSession($userID);
    if($trainSession['workoutID'] == NULL)
    {
        $response["status"] = "400 (BAD REQUEST)";
        $response["message"] = "NO ACTIVE SESSION FOUND"; 
    }
    else
    {
        $response["status"] = "200 (OK)";
        $response["message"] = $trainSession;    
    }    
    return $response;
}
function newTrainingSession($workoutID,$userID)
{
    $response = ["status"=>"","message"=>""];    
    
    require_once("../Model/userTrainingSession/trainSessionHandler.php");
    $trainSession = createTrainSession($workoutID,$userID);
    if($trainSession == NULL)
    {
        $response["status"] = "400 (BAD REQUEST)";
        $response["message"] = "INVALID WORKOUT ID"; 
    }
    else
    {
        $response["status"] = "200 (OK)";
        $response["message"] = $trainSession;    
    }    
    return $response;
}
function selectExercise($exerciseID,$userID)
{
    $response = ["status"=>"","message"=>""];    
    
    require_once("../Model/userTrainingSession/trainSessionHandler.php");
    $trainSession = changeExercise($userID,$exerciseID);    
    if($trainSession == NULL)
    {
        $response["status"] = "400 (BAD REQUEST)";
        $response["message"] = "INVALID EXERCISE ID"; 
    }
    else
    {
        $response["status"] = "200 (OK)";
        $response["message"] = $trainSession;    
    }    
    return $response;
}
function setComplete($userID)
{
    $response = ["status"=>"","message"=>""];    
    
    require_once("../Model/userTrainingSession/trainSessionHandler.php");
    $trainSession = nextSet($userID);  
    if($trainSession == "WORKOUT COMPLETE")
    {
        $response["status"] = "200 (OK)";
        $response["message"] = $trainSession; 
    }
    else
    {
        $response["status"] = "200 (OK)";
        $response["message"] = $trainSession;    
    }    
    return $response;
}
?>

