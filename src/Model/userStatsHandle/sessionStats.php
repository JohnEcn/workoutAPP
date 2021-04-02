<?php
class sessionStats
{
    private $exerciseList =[];
    private $sessionExists;
    
    function __construct($userID)
    {  
        $this->userID = $userID;
        $this->exerciseList = $this->getStatsList();
    }      
    private function getStatsList()
    {
        require_once("sessionStatsDB.php");
        $sessionStatsDB = new statsDB;
        $response = $sessionStatsDB->getSessionStats($this->userID);

        if( count($response) == 0)
        {
            $this->sessionExists = false;
        }      
        else
        {
            $this->sessionExists = true;
        }  
        return $response == null ? [] : json_decode($response[0],true);
        
    }        
    private function saveStatsList()
    {
        $sessionStatsStr = null;
        if($this->exerciseList != null && count($this->exerciseList) != 0) 
        {
            $sessionStatsStr = json_encode($this->exerciseList);
        }        
        require_once("sessionStatsDB.php");
        $sessionStatsDB = new statsDB;
        $sessionStatsDB->updateSessionStats($this->userID,$sessionStatsStr);
   
    }
    public function addCompletedSetStats($exerciseID,$repetition,$weight)
    {
        if(isset($this->exerciseList[$exerciseID]))
        {
            array_push($this->exerciseList[$exerciseID],[$repetition,$weight]);
        }
        else
        {
            $this->exerciseList[$exerciseID]=[[$repetition,$weight]];             
        }       
        $this->saveStatsList($this->userID); 
    }
    public function routineStats()
    {
        return $this->exerciseList;
    }  
    public function changeStatEntry($exerciseID,$setIndex,$repetition,$weight)
    {
        if(isset($this->exerciseList[$exerciseID]))
        {
            $this->exerciseList[$exerciseID][$setIndex-1] = [$repetition,$weight];
            $this->saveStatsList($this->userID); 
            return true;
        }
        else
        {
            return false;
        }
       
    }
    public function clearAllEntries()
    {
        $this->exerciseList = null;
        $this->saveStatsList($this->userID); 
    }
    public function deleteExerciseEntry($exerciseID)
    {
        if(isset($this->exerciseList[$exerciseID]))
        {
            unset($this->exerciseList[$exerciseID]);
            $this->saveStatsList($this->userID); 
            return true;
        }
        else
        {
            return false;
        }
       
    }  
    public function sessionExistsCheck()
    {
        return $this->sessionExists;     
    }    
}
?>