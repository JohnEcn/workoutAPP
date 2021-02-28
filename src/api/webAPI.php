<?php

function userAuth($parameters)
{   
    $response = ["HttpResponse"=>"","message"=>""];
    if($parameters !=null && array_key_exists ( "user" , $parameters ) && array_key_exists ( "pass" , $parameters ))
    {
            require_once("../Model/userAuth/login.php");
            $token = userLogIn($parameters["user"],$parameters["pass"]);
            
            if($token == "Wrong username or password" || $token == "Empty username or password")
            {
                $response["HttpResponse"] = 401;
                $response["message"] = $token;
            }
            else
            {
                $response["HttpResponse"] = 200;
                unset($response["message"]);
                $response["token"] = $token;
            }
    }
    else
    {
        $response["HttpResponse"] = 400;
        $response["message"] = "Malformed request syntax";
    }
    return $response;
}
function userSignUp($parameters)
{   
    $response = ["HttpResponse"=>"","message"=>""];
    if($parameters !=null && array_key_exists ( "user" , $parameters ) && array_key_exists ( "pass" , $parameters ) && array_key_exists ( "email" , $parameters ) && array_key_exists ( "passconf" , $parameters ))
    {       
            require_once("../Model/userAuth/signUp.php");
            $status = userInsert($parameters["user"],$parameters["email"],$parameters["pass"],$parameters["passconf"]);
            
            if($status == "SUCCESS")
            {
                $response["HttpResponse"] = 201;
                unset($response["message"]);
            }
            else
            {
                $response["HttpResponse"] = 409;
                $response["message"] = $status;
            }
    }
    else
    {
        $response["HttpResponse"] = 400;
        $response["message"] = "Malformed request syntax";
    }
    return $response;
    
}
function endAuth($cookies)
{
   $response = ["HttpResponse"=>"","message"=>""];

   if(isset($cookies['token']))
   {
       require_once("../Model/userAuth/logOut.php");
       $status = userLogout($cookies['token']);
       
       if($status == "Unathorized user")
       {
           $response["HttpResponse"] = 401;
           unset($response["message"]);
       }
       else
       {
            $response["HttpResponse"] = 200;
            unset($response["message"]);
       }       
   }
   else
   {
        $response["HttpResponse"] = 401;
        unset($response["message"]);
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
        $response["status"] = 201;
        $response["message"] = "Workout routine saved succesfully";
    }
    else
    {
        $response["status"] = 409;
        $response["message"] = $status;        
    }
    return $response;
}
function getWorkout($workoutID,$userID)
{  
    $response = ["HttpResponse"=>"","message"=>""];    
    
    require_once("../Model/userWorkouts/workoutHandler.php");
    $responseWorkout = retrieveWorkout($workoutID,$userID);
    if($responseWorkout != NULL)
    {   $response["HttpResponse"] = 200;
        $response["message"] = $responseWorkout;
    }
    else
    {
        $response["HttpResponse"] = 404;
        $response["message"] = "Workout routine not found.";        
    }

    return $response;    
}
function getWorkoutList($userID)
{
    $response = ["HttpResponse"=>"","message"=>""];    
    require_once("../Model/userWorkouts/workoutDB.php");
    $conn = new workoutDB;
    $workoutList = $conn->getWorkoutList($userID);

    if(empty($workoutList))
    {
        $response["HttpResponse"] = 204;
        $response["message"] = "Workout List empty";        
    }
    else
    {
        $response["HttpResponse"] = 200;
        $response["message"] = $workoutList ; 
    }
    return $response;
}  
function alterWorkoutName($workoutID,$userID,$newName)
{   
    $response = ["HttpResponse"=>"","message"=>""];    
    
    require_once("../Model/userWorkouts/workoutHandler.php");
    $status = changeWorkoutName($workoutID,$userID,$newName);

    if($status == "SUCCESS")
    {
        $response["HttpResponse"] = 200;
        $response["message"] = "Workout routine renamed succesfully."; 
    }
    elseif($status =="Invalid Workout")
    {
        $response["HttpResponse"] = 404;
        $response["message"] = "Workout routine not found.";
    }
    else
    {
        $response["HttpResponse"] = 409;
        $response["message"] = $status;    
    }    
    return $response;
}
function addNewExercise($workoutID,$userID,$newExercise)
{
    $response = ["HttpResponse"=>"","message"=>""];    
    
    require_once("../Model/userWorkouts/workoutHandler.php");
    $status = addExercise($workoutID,$userID,$newExercise);

    if($status == "SUCCESS")
    {
        $response["HttpResponse"] = 201;
        $response["message"] = "Exercise inserted succesfully in workout routine."; 
    }
    elseif($status == "Workout routine not found.")
    {
        $response["HttpResponse"] = 404;
        $response["message"] = $status;    
    }  
    else
    {
        $response["HttpResponse"] = 409;
        $response["message"] = $status;    
    }    
    return $response;
    
}
function removeExercise($workoutID,$userID,$exerciseID)
{
    $response = ["HttpResponse"=>"","message"=>""];    
    
    require_once("../Model/userWorkouts/workoutHandler.php");
    $status = deleteExercise($workoutID,$userID,$exerciseID);
    if($status == "Exercise succesfully deleted.")
    {
        $response["HttpResponse"] = 200;
        $response["message"] = "Exercise succesfully deleted."; 
    }
    elseif($status == "Workout routine not found." || $status ==  "Exercise not found.")
    {
        $response["HttpResponse"] = 404;
        $response["message"] = $status;    
    }     
    return $response;
}
function getTrainingSession($userID)
{
    $response = ["HttpResponse"=>"","message"=>""];    
    
    require_once("../Model/userTrainingSession/trainSessionHandler.php");
    $trainSession = retrieveTrainSession($userID);
    if($trainSession['workoutID'] == NULL)
    {
        $response["HttpResponse"] = 204;
        $response["message"] = "No active session found"; 
    }
    else
    {
        $response["HttpResponse"] = 200;
        $response["message"] = $trainSession;    
    }    
    return $response;
}
function newTrainingSession($workoutID,$userID)
{
    $response = ["HttpResponse"=>"","message"=>""];    
    
    require_once("../Model/userTrainingSession/trainSessionHandler.php");
    $trainSession = createTrainSession($workoutID,$userID);
    if($trainSession == NULL)
    {
        $response["HttpResponse"] = 404;
        $response["message"] = 'Workout routine not found.'; 
    }
    else
    {
        $response["HttpResponse"] = 201;
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
        $response["status"] = 404;
        $response["message"] = "Exercise id not found."; 
    }
    else
    {
        $response["status"] = 200;
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

var_dump(selectExercise(72,2));
?>

