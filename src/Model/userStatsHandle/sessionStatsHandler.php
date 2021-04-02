<?php
require_once("sessionStats.php");

function getRoutineStats($userID)
{
    $routineStats =  new sessionStats($userID);  
    $sessionCheck = $routineStats->sessionExistsCheck();   

    if($sessionCheck)
    {
        return $routineStats->routineStats();
    } 
    else
    {
        return "session 404";
    }
}
function addEntry($userID,$exerciseID,$repetition,$weight)
{
    $routineStats =  new sessionStats($userID);  
    $sessionCheck = $routineStats->sessionExistsCheck();   

    if(!$sessionCheck)
    {
        "session 404";
    } 
    
    $routineStats->addCompletedSetStats($exerciseID,$repetition,$weight);
    return true;    
}
function changeEntry($userID,$exerciseID,$setIndex,$repetition,$weight)
{
    $routineStats =  new sessionStats($userID);  
    $sessionCheck = $routineStats->sessionExistsCheck();   

    if(!$sessionCheck)
    {
        return "session 404";
    } 
    
    return  $routineStats->changeStatEntry($exerciseID,$setIndex,$repetition,$weight);   
}
function removeExerciseEntry($userID,$exerciseID)
{
    $routineStats =  new sessionStats($userID);  
    $sessionCheck = $routineStats->sessionExistsCheck();   

    if(!$sessionCheck)
    {
        return "session 404";
    } 
    
    return  $routineStats->deleteExerciseEntry($exerciseID);  
}
function removeAllEntries($userID)
{
    $routineStats =  new sessionStats($userID);  
    $sessionCheck = $routineStats->sessionExistsCheck();   

    if(!$sessionCheck)
    {
        return "session 404";
    } 
    
    $routineStats->clearAllEntries(); 
    return true; 
}
?>