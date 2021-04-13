<?php

function userAuth($parameters)
{   
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""];
    
    if($parameters != null && array_key_exists ( "user" , $parameters ) && array_key_exists ( "pass" , $parameters ))
    {       
            require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userAuth/login.php");
            $status = userLogIn($parameters["user"],$parameters["pass"]);
            
            if($status == "Wrong username or password" || $status == "Empty username or password")
            {              
                $response["HttpCode"] = 401;
                $response["HttpBody"]  = ["message"=>$status];
                $response["cookie"] = NULL;                
            }
            else
            {
                $response["HttpCode"] = 200;
                $response["HttpBody"]  = NULL;
                $response["cookie"] = $status;
            }
    }
    else
    {
        $response["HttpCode"] = 400;
        $response["HttpBody"] = NULL;
        $response["cookie"] = NULL;
    }
    return $response;
}
function userSignUp($parameters)
{   
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""];

    if($parameters !=null && array_key_exists ( "user" , $parameters ) && array_key_exists ( "pass" , $parameters ) && array_key_exists ( "email" , $parameters ) && array_key_exists ( "passconf" , $parameters ))
    {       
            require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userAuth/signUp.php");
            $status = userInsert($parameters["user"],$parameters["email"],$parameters["pass"],$parameters["passconf"]);
            
            if($status == "SUCCESS")
            {
                $response["HttpCode"] = 201;
                $response["HttpBody"]  = NULL;  
                $response["cookie"] = NULL;    
            }
            else
            {           
                $response["HttpCode"] = 409;
                $response["HttpBody"]  = ["message"=>$status];
                $response["cookie"] = NULL;         
            }
    }
    else
    {
        $response["HttpCode"] = 400;
        $response["HttpBody"]  = NULL;
        $response["cookie"] = NULL;         
    }
    return $response;    
}
function endAuth($userID)
{
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""];
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userAuth/logOut.php");
    $status = userLogout($userID);
       
    if($status == "User auth ended")
    {
        $response["HttpCode"] = 200;
        $response["HttpBody"]  = NULL;  
        $response["cookie"] = "deleted";           
    }       
    return $response;
}
function checkAuth($cookies)
{   
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userAuth/checkAuth.php");
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
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""];
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userWorkouts/workoutHandler.php");
    $status  = insertWorkout($httpBody,$userID);
    
    if($status == "SUCCESS")
    {   
        $response["HttpCode"] = 201;
        $response["HttpBody"]  = NULL;  
        $response["cookie"] = NULL;          
    }
    elseif($status == "Required data missing")
    {
        $response["HttpCode"] = 400;
        $response["HttpBody"]  = NULL;
        $response["cookie"] = NULL;        
    }
    else
    {
        $response["HttpCode"] = 409;
        $response["HttpBody"]  = ["message"=>$status];
        $response["cookie"] = NULL;        
    }
    return $response;
}
function getWorkout($workoutID,$userID)
{  
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""]; 
    
    if($workoutID == "" || is_int((int)$workoutID) == false)
    {
        $response["HttpCode"] = 400;
        $response["HttpBody"]  = ["message"=>"Parameter 'wid' must be integer."];
        $response["cookie"] = NULL;  
        return $response;  
    }

    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userWorkouts/workoutHandler.php");
    $responseWorkout = retrieveWorkout($workoutID,$userID);
    if($responseWorkout != NULL)
    {   
        $response["HttpCode"] = 200;
        $response["HttpBody"]  = $responseWorkout;
        $response["cookie"] = NULL; 
    }
    else
    {
        $response["HttpCode"] = 404;
        $response["HttpBody"]  = ["message"=>"Workout routine not found."];
        $response["cookie"] = NULL;  
    }

    return $response;    
}
function removeWorkout($workoutID,$userID)
{  
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""];
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userWorkouts/workoutHandler.php");
    $status = deleteWorkout($workoutID,$userID);
    if($status == "SUCCESS")
    {   
        $response["HttpCode"] = 200;
        $response["HttpBody"]  =  NULL; 
        $response["cookie"] = NULL; 
    }
    else
    {
        $response["HttpCode"] = 404;
        $response["HttpBody"]  = ["message"=>"Workout routine not found."];
        $response["cookie"] = NULL;  
    }
    return $response;    
}
function getWorkoutList($userID)
{
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""]; 
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userWorkouts/workoutDB.php");
    $conn = new workoutDB;
    $workoutList = $conn->getWorkoutList($userID);

    if(empty($workoutList))
    { 
        $response["HttpCode"] = 204;
        $response["HttpBody"]  = ["message"=>"Workout List IS empty"];
        $response["cookie"] = NULL; 
    }
    else
    {
        $response["HttpCode"] = 200;
        $response["HttpBody"]  = $workoutList ; 
        $response["cookie"] = NULL; 
    }
    return $response;
}  
function alterWorkoutName($workoutID,$userID,$newName)
{   
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""];     
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userWorkouts/workoutHandler.php");
    $status = changeWorkoutName($workoutID,$userID,$newName);

    if($status == "SUCCESS")
    {      
        $response["HttpCode"] = 200;
        $response["HttpBody"]  = NULL; 
        $response["cookie"] = NULL; 
    }
    elseif($status =="Invalid Workout")
    {  
        $response["HttpCode"] = 404;
        $response["HttpBody"]  = ["message"=>"Workout routine not found."];
        $response["cookie"] = NULL; 
    }
    else
    {
        $response["HttpCode"] = 409;
        $response["HttpBody"]  = ["message"=>$status];
        $response["cookie"] = NULL; 
    }    
    return $response;
}
function addNewExercise($workoutID,$userID,$newExercise)
{
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""];      
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userWorkouts/workoutHandler.php");
    $status = addExercise($workoutID,$userID,$newExercise);

    if($status == "SUCCESS")
    {
        $response["HttpCode"] = 201;
        $response["HttpBody"]  = NULL; 
        $response["cookie"] = NULL;  
    }
    elseif($status == "Workout routine not found.")
    {
        $response["HttpCode"] = 404;
        $response["HttpBody"]  = ["message"=>$status];
        $response["cookie"] = NULL;    
    }  
    else
    {
        $response["HttpCode"] = 409;
        $response["HttpBody"]  = ["message"=>$status];
        $response["cookie"] = NULL;  
    }    
    return $response;
    
}
function removeExercise($workoutID,$userID,$exerciseID)
{
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""];   
    
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userWorkouts/workoutHandler.php");
    $status = deleteExercise($workoutID,$userID,$exerciseID);
    if($status == "Exercise succesfully deleted.")
    {
        $response["HttpCode"] = 200;
        $response["HttpBody"]  =  NULL;
        $response["cookie"] = NULL; 
    }
    elseif($status == "Workout routine not found." || $status ==  "Exercise not found.")
    {
        $response["HttpCode"] = 404;
        $response["message"] = ["message"=>$status];
        $response["cookie"] = NULL;   
    }     
    return $response;
}
function getTrainingSession($userID)
{
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""];
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userTrainingSession/trainSessionHandler.php");
    $trainSession = retrieveTrainSession($userID);
    if($trainSession['workoutID'] == NULL)
    {       
        $response["HttpCode"] = 204;
        $response["HttpBody"]  =  NULL;
        $response["cookie"] = NULL;  
    }
    else
    {
        $response["HttpCode"] = 200;
        $response["HttpBody"]  =  $trainSession;
        $response["cookie"] = NULL;  
    }    
    return $response;
}
function newTrainingSession($workoutID,$userID)
{
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""];    
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userTrainingSession/trainSessionHandler.php");
    $trainSession = createTrainSession($workoutID,$userID);
    if($trainSession == NULL)
    { 
        $response["HttpCode"] = 404;
        $response["HttpBody"]  =  NULL;
        $response["cookie"] = NULL;   
    }
    else
    {       
        $response["HttpCode"] = 201;
        $response["HttpBody"]  =  $trainSession;
        $response["cookie"] = NULL;       
    }    
    return $response;
}
function selectExercise($exerciseID,$userID)
{
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""];        
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userTrainingSession/trainSessionHandler.php");
    $trainSession = changeExercise($userID,$exerciseID);    
    if($trainSession == NULL)
    {    
        $response["HttpCode"] = 404;
        $response["HttpBody"]  =  NULL;
        $response["cookie"] = NULL;    
    }
    else
    {
        $response["HttpCode"] = 200;
        $response["HttpBody"]  =  $trainSession;
        $response["cookie"] = NULL;    
    }    
    return $response;
}
function setComplete($userID)
{
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""];   
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userTrainingSession/trainSessionHandler.php");
    $trainSession = nextSet($userID);  
    if($trainSession == "WORKOUT COMPLETE")
    {
        $response["HttpCode"] = 200;
        $response["HttpBody"]  =  ["message"=>"Workout session complete."];
        $response["cookie"] = NULL;    
    }
    else
    {      
        $response["HttpCode"] = 200;
        $response["HttpBody"]  =  $trainSession;
        $response["cookie"] = NULL;        
    }    
    return $response;
}
function endWorkoutSession($userID)
{
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""];   
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userTrainingSession/trainSessionHandler.php");
    workoutComplete($userID);
    
    $response["HttpCode"] = 200;
    $response["HttpBody"]  =  ["message"=>"Workout session complete."];
    $response["cookie"] = NULL;    
    
    return $response;
}
function autoComplete($queryParameters)
{
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""];   

    if(isset($queryParameters['str']) == true && $queryParameters['str'] != "")
    {
        require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/exerciseAutoComplete/exerciseAutocomplete.php");
        $results = findMatchingStrings($queryParameters['str']);  
        if($results != null)
        {
            $response["HttpCode"] = 200;
            $response["HttpBody"]  =  $results;
            $response["cookie"] = NULL;    
        }
        else
        {      
            $response["HttpCode"] = 204;
            $response["HttpBody"]  =  NULL;
            $response["cookie"] = NULL;        
        }
    }
    else
    {
        $response["HttpCode"] = 400;
        $response["HttpBody"]  =  NULL;
        $response["cookie"] = NULL;  
    }   
    return $response;
}
function getWorkoutStats($userID)
{
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""];   
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userStatsHandle/sessionStatsHandler.php");
    $stats = getRoutineStats($userID);
    
    if($stats === "session 404")
    {
        $response["HttpCode"] = 404;
        $response["HttpBody"]  =  ["message"=>"No active session found"];
        $response["cookie"] = NULL;    
    }
    else if($stats == null)
    {      
        $response["HttpCode"] = 204;
        $response["HttpBody"]  =  $stats;
        $response["cookie"] = NULL;        
    }  
    else
    {
        $response["HttpCode"] = 200;
        $response["HttpBody"]  =  $stats;
        $response["cookie"] = NULL; 
    }  
    return $response;       
}
function addExerciseStats($userID,$exerciseID,$repetition,$weight)
{
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""];   
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userStatsHandle/sessionStatsHandler.php");
    $status = addEntry($userID,$exerciseID,$repetition,$weight);
    
    if($status === "session 404")
    {
        $response["HttpCode"] = 404;
        $response["HttpBody"]  =  ["message"=>"No active session found"];
        $response["cookie"] = NULL;    
    }
    else
    {
        $response["HttpCode"] = 201;
        $response["HttpBody"]  =  NULL;
        $response["cookie"] = NULL; 
    }  
    return $response;      
}
function changeExerciseEntry($userID,$exerciseID,$setIndex,$repetition,$weight)
{
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""];   
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userStatsHandle/sessionStatsHandler.php");
    $status = changeEntry($userID,$exerciseID,$setIndex,$repetition,$weight);
    
    if($status === "session 404")
    {
        $response["HttpCode"] = 404;
        $response["HttpBody"]  =  ["message"=>"No active session found"];
        $response["cookie"] = NULL;    
    }
    else if($status == false)
    {      
        $response["HttpCode"] = 409;
        $response["HttpBody"]  =  NULL; 
        $response["cookie"] = NULL;        
    }  
    else
    {
        $response["HttpCode"] = 200;
        $response["HttpBody"]  =  NULL;
        $response["cookie"] = NULL; 
    }  
    return $response;       
}
function deleteExerciseEntry($userID,$exerciseID)
{
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""];   
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userStatsHandle/sessionStatsHandler.php");
    $status = removeExerciseEntry($userID,$exerciseID);
    
    if($status === "session 404")
    {
        $response["HttpCode"] = 404;
        $response["HttpBody"]  =  ["message"=>"No active session found"];
        $response["cookie"] = NULL;    
    }
    else if($status == false)
    {      
        $response["HttpCode"] = 409;
        $response["HttpBody"]  =  NULL; 
        $response["cookie"] = NULL;        
    }  
    else
    {
        $response["HttpCode"] = 200;
        $response["HttpBody"]  =  NULL;
        $response["cookie"] = NULL; 
    }  
    return $response;       
}
function deleteAllEntries($userID)
{
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""];   
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userStatsHandle/sessionStatsHandler.php");
    $status = removeAllEntries($userID);
    
    if($status === "session 404")
    {
        $response["HttpCode"] = 404;
        $response["HttpBody"]  =  ["message"=>"No active session found"];
        $response["cookie"] = NULL;    
    }    
    else
    {
        $response["HttpCode"] = 200;
        $response["HttpBody"]  =  NULL;
        $response["cookie"] = NULL; 
    }  
    return $response;       
}
function getRoutineLogsEntries($userID)
{
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""];   
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/workoutLogHandle/workoutLogHandler.php");
    $status = getRoutinesListLogs($userID);
    
    if($status === false)
    {
        $response["HttpCode"] = 204;
        $response["HttpBody"]  =  NULL; 
        $response["cookie"] = NULL;    
    }    
    else
    {
        $response["HttpCode"] = 200;
        $response["HttpBody"]  =  $status;
        $response["cookie"] = NULL; 
    }  
    return $response;       
}
function getExercisesLogsEntries($userID,$numberOfresults)
{
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""];   
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/workoutLogHandle/workoutLogHandler.php");
    $status = getExerciseListLogs($userID,$numberOfresults);
    
    if($status === false)
    {
        $response["HttpCode"] = 204;
        $response["HttpBody"]  =  NULL; 
        $response["cookie"] = NULL;    
    }    
    else
    {
        $response["HttpCode"] = 200;
        $response["HttpBody"]  =  $status;
        $response["cookie"] = NULL; 
    }  
    return $response;       
}
function getExerciseLogsEntries($userID,$exercise,$numberOfresults)
{
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""];   
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/workoutLogHandle/workoutLogHandler.php");
    $status = getExerciseLogs($userID,$exercise,$numberOfresults);
    
    if($status === false)
    {
        $response["HttpCode"] = 204;
        $response["HttpBody"]  =  NULL; 
        $response["cookie"] = NULL;    
    }    
    else
    {
        $response["HttpCode"] = 200;
        $response["HttpBody"]  =  $status;
        $response["cookie"] = NULL; 
    }  
    return $response;       
}
function getExerciseRP($userID,$exercise)
{
    $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""];   
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/workoutLogHandle/workoutLogHandler.php");
    $status = getExerciseRepMax($userID,$exercise);
    
    if($status === false)
    {
        $response["HttpCode"] = 204;
        $response["HttpBody"]  =  NULL; 
        $response["cookie"] = NULL;    
    }    
    else
    {
        $response["HttpCode"] = 200;
        $response["HttpBody"]  =  $status[0];
        $response["cookie"] = NULL; 
    }  
    return $response;       
}
?>

