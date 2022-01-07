<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userTrainingSession/trainingSession.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/api/endpoints/Endpoint.php");

Class trainingSessionsEndpoint extends Endpoint
{
    public function retrieveTrainSession($userID)
    {    
        try
        {
            $trainingSession = new trainingSession(NULL,$userID);
            $sessionInfo = $trainingSession->getSessionASSOC();
            if($sessionInfo['workoutID'] == NULL)
            {
                throw new Exception("No active session found.");
            }
            parent::setResponse(200,$sessionInfo,NULL);
        }
        catch(Exception $e)
        {
            parent::setResponse(204,NULL,NULL);
        }
    }
    public function createTrainSession($workoutID,$userID)
    {   
        try
        {
            //Use the UserWorkoutEndpoint.php to get the specified workout routine
            require_once("userWorkoutEndpoint.php");
            $UserWorkoutEndpoint = new userWorkoutEndpoint;
            $UserWorkoutEndpoint->retrieveWorkout($workoutID,$userID);
            $workoutRoutine = $UserWorkoutEndpoint->getResponse();
            
            if($workoutRoutine['HttpCode'] != 200)
            {
                throw new Exception("Workout routine not found.");
            }

            $trainSession = new trainingSession($workoutRoutine["HttpBody"],$userID);
            $sessionInfo = $trainSession->getSessionASSOC();
            parent::setResponse(201,$sessionInfo,NULL);
        }
        catch(Exception $e)
        {
            parent::setResponse(404,$e->getMessage(),NULL);
        }
    } 
    public function nextSet($userID)
    {
        try
        {
            $trainSession = new trainingSession(NULL,$userID);
            $trainSession->nextSet();
            $sessionInfo = $trainSession->getSessionASSOC();
            
            if($sessionInfo['workoutID'] == NULL)
            {
                throw new Exception("No active session found.");
            }

            $completeCheck = $trainSession->workoutCompleteCheck();            
            if($completeCheck)
            {
                $this->workoutComplete($userID); 
                return;   
            } 

            parent::setResponse(200,$sessionInfo,NULL);
        }
        catch(Exception $e)
        {
            parent::setResponse(404,$e->getMessage(),NULL);
        }
    }
    public function workoutComplete($userID)
    {
        try
        {   
                 
            require_once("workoutLogsEndpoint.php");
            $workoutLogger = new workoutLogsEndpoint;
            $workoutLogger->logSessionStats($userID);
            $wasLoggerSuccessfull = $workoutLogger->getResponse(); 
            if(!$wasLoggerSuccessfull)
            {
                throw new Exception("Workout did not get saved.");
            }

            //End the session only after the stats have saved, because endSession() deletes the workout stats
            $trainSession = new trainingSession(NULL,$userID);
            $trainSession->endSession();
            parent::setResponse(200,NULL,NULL);
        }
        catch(Exception $e)
        {
            parent::setResponse(409,$e->getMessage(),NULL);
        }
    } 
    public function changeExercise($exerciseId,$userID)
    {
        try
        {
            $trainSession = new trainingSession(NULL,$userID);
            $status = $trainSession->selectExercise($exerciseId);
            $sessionInfo = $trainSession->getSessionASSOC();
            if(!$status)
            {
                throw new Exception("Exercise not found");
            }
            parent::setResponse(200,$sessionInfo,NULL);
        }
        catch(Exception $e)
        {
            parent::setResponse(404,NULL,NULL);
        }
    } 
}
?>