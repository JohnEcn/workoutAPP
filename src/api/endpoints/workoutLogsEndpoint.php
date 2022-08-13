<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/workoutLogHandle/workoutLog.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/api/endpoints/Endpoint.php");

Class workoutLogsEndpoint extends Endpoint
{
    public function logSessionStats($userID)
    {
        try
        {
            //Get the current session info using the endpoint
            require_once("trainingSessionsEndpoint.php");
            $sessionsEndpoint = new trainingSessionsEndpoint;
            $sessionsEndpoint->retrieveTrainSession($userID);
            $sessionInfo = $sessionsEndpoint->getResponse();
            if($sessionInfo['HttpCode'] != 200)
            {
                throw new Exception("No active session found.");
            }

            //Get the current session statistics using the endpoint
            require_once("sessionStatsEndpoint.php");
            $sessionsStatsEndpoint = new sessionStatsEndpoint;
            $sessionsStatsEndpoint->getRoutineStats($userID);
            $sessionStats = $sessionsStatsEndpoint->getResponse();
            if($sessionStats['HttpCode'] != 200)
            {
                throw new Exception("No session stats found.");
            }

            //Extract the usefull data
            $exercisesList = $sessionInfo['HttpBody']['exerciseList'];
            $workoutID = $sessionInfo['HttpBody']['workoutID'];
            $sessionStatistics = $sessionStats['HttpBody'];
            $date = date("Y/m/d");   

            //Save the data
            $workoutLogger = new workoutLog($userID); 
            $workoutLogger->addWorkoutLogEntry($workoutID,$sessionStatistics,$exercisesList,$date);
            parent::setResponse(201,NULL,NULL);

        }
        catch(Exception $e)
        {
            parent::setResponse(404,$e->getMessage(),NULL);
        }
    }  
    public function getExerciseListLogs($userID,$numberOfresults)
    {
        try
        {
            $workoutLogger = new workoutLog($userID); 
            $loggedStats = $workoutLogger->getExercisesCount($numberOfresults);
            if(count($loggedStats) <= 0)
            {
                parent::setResponse(204,NULL,NULL);                
            }
            parent::setResponse(200,$loggedStats,NULL);  
        }
        catch(Exception $e)
        {
            parent::setResponse(409,NULL,NULL);  
        }        
    }  
    public function getRoutinesListLogs($userID)
    {
        try
        {
            $workoutLogger = new workoutLog($userID); 
            $loggedStats = $workoutLogger->routinesLogs();
            if(count($loggedStats) <= 0)
            {
                parent::setResponse(204,NULL,NULL);                
            }
            parent::setResponse(200,$loggedStats,NULL);  
        }
        catch(Exception $e)
        {
            parent::setResponse(409,NULL,NULL);  
        }      
    }
    public function getExerciseLogs($userID,$exercise,$numberOfresults)
    {
        try
        {
            $workoutLogger = new workoutLog($userID); 
            $loggedStats = $workoutLogger->getExerciseLogs($exercise,$numberOfresults);
            if(count($loggedStats) <= 0)
            {
                parent::setResponse(204,NULL,NULL);                
            }
            parent::setResponse(200,$loggedStats,NULL);  
        }
        catch(Exception $e)
        {
            parent::setResponse(409,NULL,NULL);  
        }      
    }
    public function getExerciseRepMax($userID,$exercise)
    {
        try
        {
            $workoutLogger = new workoutLog($userID); 
            $loggedStats = $workoutLogger->getExerciseRP($exercise);
            if(count($loggedStats) == 0 || $result[0]['1RP']== NULL)
            {
                parent::setResponse(204,NULL,NULL);                
            }
            parent::setResponse(200,$loggedStats,NULL);  
        }
        catch(Exception $e)
        {
            parent::setResponse(409,NULL,NULL);  
        }      
    }        
}
?>