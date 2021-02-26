<?php
require_once("trainingSession.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/workoutApp/src/Model/userWorkouts/workoutHandler.php");  

function retrieveTrainSession($userID)
{    
    $trainSession = new trainingSession(NULL,$userID);
    $sessionArray = $trainSession->getSessionASSOC();
    return $sessionArray;
}

function createTrainSession($workoutID,$userID)
{   
    $workout = retrieveWorkout($workoutID,$userID);

    if($workout != NULL)
    {
        $trainSession = new trainingSession($workout,$userID);
        $sessionArray = $trainSession->getSessionASSOC();
        return $sessionArray;
    }
    else
    {
        return NULL;   
    }   
} 

function nextSet($userID)
{
    $trainSession = new trainingSession(NULL,$userID);
    $status = $trainSession->nextSet();
    $sessionArray = $trainSession->getSessionASSOC();
    if($status == "WORKOUT COMPLETE")
    {
        return $status;
    }
    else
    {
        return $sessionArray;

    }    
}
function nextExercise($userID)
{
    $trainSession = new trainingSession(NULL,$userID);
    $trainSession->selectExercise($exerciseId);
    $sessionArray = $trainSession->getSessionASSOC();
    return $sessionArray;
}

function changeExercise($userID,$exerciseId)
{
    $trainSession = new trainingSession(NULL,$userID);
    $status = $trainSession->selectExercise($exerciseId);
    $sessionArray = $trainSession->getSessionASSOC();

    if($status == true)
    {
        return $sessionArray;
    }
    else 
    {
        return $status;
    }    
}

?>