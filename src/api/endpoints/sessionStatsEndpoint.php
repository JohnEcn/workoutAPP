<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userStatsHandle/sessionStats.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/api/endpoints/Endpoint.php");

Class sessionStatsEndpoint extends Endpoint
{
    private function getSession($userID)
    {
        $session =  new sessionStats($userID);  
        $sessionExists = $session->sessionExistsCheck(); 
        if(!$sessionExists)
        {
            throw new Exception("No active session found");
        } 
        return $session;
    }
    public function getRoutineStats($userID)
    {
        try
        {
            $session =  $this->getSession($userID);
            $sessionStats = $session->routineStats();
            if($sessionStats == null)
            {
                parent::setResponse(204,NULL,NULL);
            } 
            else
            {
                parent::setResponse(200,$sessionStats,NULL);
            }
        }
        catch(Exception $e)
        {
            parent::setResponse(404,["message"=>$e->getMessage()],NULL);
        }
    }
    public function addEntry($userID,$exerciseID,$repetition,$weight)
    {
        try 
        {
            $session =  $this->getSession($userID);
            $session->addCompletedSetStats($exerciseID,$repetition,$weight);
            parent::setResponse(201,NULL,NULL);
        }
        catch(Exception $e)
        {
            parent::setResponse(404,["message"=>$e->getMessage()],NULL);
        }
    }
    public function changeEntry($userID,$exerciseID,$setIndex,$repetition,$weight)
    {
        try 
        {
            $session =  $this->getSession($userID);
            $wasSuccefull = $session->changeStatEntry($exerciseID,$setIndex,$repetition,$weight);
            if(!$wasSuccefull)
            {
                throw new Exception("Unexpected error");
            }
            parent::setResponse(200,NULL,NULL);
        }
        catch(Exception $e)
        {
            if($e->getMessage() == "No active session found" )
            {
                parent::setResponse(404,["message"=>$e->getMessage()],NULL);
            }
            else
            {
                parent::setResponse(409,NULL,NULL);
            }            
        }   
    }
    public function removeExerciseEntry($userID,$exerciseID)
    {
        try
        {
            $session =  $this->getSession($userID);        
            $wasSuccefull = $session->deleteExerciseEntry($exerciseID); 
            if(!$wasSuccefull)
            {
                throw new Exception("Unexpected error");
            }
            parent::setResponse(200,NULL,NULL);
        }
        catch(Exception $e)
        {
            if($e->getMessage() == "No active session found" )
            {
                parent::setResponse(404,["message"=>$e->getMessage()],NULL);
            }
            else
            {
                parent::setResponse(409,NULL,NULL);
            }   
        }
        $session =  $this->getSession($userID);
        
        return  $routineStats->deleteExerciseEntry($exerciseID);  
    }
    public function removeAllEntries($userID)
    {
        try
        {
            $session =  $this->getSession($userID);          
            $session->clearAllEntries(); 
            parent::setResponse(200,NULL,NULL);            
        }
        catch(Exception $e)
        {
            parent::setResponse(404,["message"=>$e->getMessage()],NULL);
        }        
    }
}
?>