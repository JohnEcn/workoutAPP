<?php
function logSessionStats($userID)
{
    $GLOBALS["internalRequest"] = true;   
    $_GET['q'] ='user/workouts/sessions';    
    $URL  = ['user','workouts','sessions'];
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/api/index.php");    
    $response = ApiRequest("GET",$URL,"",$_GET,$_COOKIE);
    
    $workout = $response['HttpBody']['exerciseList'];
    $workoutID = $response['HttpBody']['workoutID'];

    $GLOBALS["internalRequest"] = true;   
    $_GET['q'] ='user/workouts/sessions/stats';    
    $URL  = ['user','workouts','sessions','stats'];
    $response2 = ApiRequest("GET",$URL,"",$_GET,$_COOKIE);

    $sessionStats = $response2['HttpBody'];
    $date = date("Y/m/d");    
    
    require_once("workoutLog.php");
    $log = new workoutLog($userID); 
    if($sessionStats == null || count($sessionStats) ==0)
    {     

    }   
    else
    {
        $log->addWorkoutLogEntry($workoutID,$sessionStats,$workout,$date);
             
    }  
}
function getExerciseListLogs($userID,$numberOfresults)
{
    require_once("workoutLog.php");
    $log = new workoutLog($userID); 
    $result = $log->getExercisesCount($numberOfresults);
    if(count($result) <= 0)
    {
        return false;
    }
    return $result;
}
function getRoutinesListLogs($userID)
{
    require_once("workoutLog.php");
    $log = new workoutLog($userID); 
    $result = $log->routinesLogs();
    if(count($result) <= 0)
    {
        return false;
    }
    return $result;
}
function getExerciseLogsList($userID,$exercise,$numberOfresults)
{
    require_once("workoutLog.php");
    $log = new workoutLog($userID); 
    $result = $log->getExerciseLogs($exercise,$numberOfresults);
    if(count($result) <= 0)
    {
        return false;
    }
    return $result;
}
function getExerciseRepMax($userID,$exercise)
{
    require_once("workoutLog.php");
    $log = new workoutLog($userID); 
    $result = $log->getExerciseRP($exercise);
    if(count($result) == 0 || $result[0]['1RP']== null)
    {
        return false;
    }
    return $result;
}
?>