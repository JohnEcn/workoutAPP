<?php
class trainingSession 
{
    private $workout;
    private $workoutID;
    private $userID;
    private $currentExercise;
    private $setsRemaining;
    private $exerciseList;
    private $lastModified;
    private $workoutCompleteFlag = 0;

    public function __construct($workout,$userID)
    {
        if($workout == NULL)
        {   
            $this->userID = $userID;
            $this->loadExistingSession();   
        }
        else
        {
            $this->workout = $workout;
            $this->userID = $userID;
            $this->initializeSession();
        }         
    }

    private function initializeSession()
    {
        $this->exerciseList = $this->BsortExerciseArray($this->workout['exerciseList']);
        $this->workoutID = $this->workout['workoutID'];    
        $this->currentExercise =$this->exerciseList[0]['exerciseID'];
        $this->setsRemaining = $this->exerciseList[0]['sets'];

        require_once("trainingSessionDB.php");
        $sessionDB = new trainingSessionDB;
        $sessionDB->deleteSession($this->userID);
        $sessionDB->saveSession($this->userID,$this->workoutID,$this->currentExercise,$this->setsRemaining,json_encode($this->exerciseList));
    }

    private function loadExistingSession()
    {   
        require_once("trainingSessionDB.php");
        $sessionDB = new trainingSessionDB;
        $session = $sessionDB->loadSession($this->userID);

        if(count($session) != 0)
        {            
            $this->workoutID = (int)$session[0]["workoutID"];        
            $this->currentExercise = (int)$session[0]["currentExerciseID"];        
            $this->setsRemaining = (int)$session[0]["setsRemaining"];        
            $this->exerciseList = json_decode($session[0]["exerciseList"],true);
            $this->lastModified = $session[0]["lastModified"];
        }      
    }

    function BsortExerciseArray($array)
    {        
        $tempArray = $array;
        do
        {
            $flag = false;
            for($i=0; $i<count($tempArray)-1; $i++)
            {
                if($tempArray[$i]['index'] > $tempArray[$i+1]['index'])
                {
                    $temp = $tempArray[$i+1];
                    $tempArray[$i+1] = $tempArray[$i];
                    $tempArray[$i] = $temp;
                    $flag = true;                    
                }
            }
        }
        while($flag);
        return  $tempArray;        
    }

    function nextSet()
    {        
        if($this->setsRemaining == 1)
        {   
            $this->nextExercise();
        }
        else
        {
            for($i = 0; $i < count($this->exerciseList); $i++)
            {
                if($this->currentExercise == $this->exerciseList[$i]['exerciseID'])
                {
                 $this->exerciseList[$i]['sets']--;
                 break;
                }
             }
            $this->setsRemaining--;
            $this->updateSession();
        }
    }

    function nextExercise()
    {  
        if($this->workoutCompleteFlag == 2)
        {
            $this->workoutComplete();
            return;
        }
        for($i = 0; $i < count($this->exerciseList); $i++)
        {
            if($this->currentExercise == $this->exerciseList[$i]['exerciseID'])  
            {
                if($i != count($this->exerciseList)-1 && $this->exerciseList[$i+1]['sets'] != 1)
                {
                    $this->currentExercise = $this->exerciseList[$i+1]['exerciseID'];
                    $this->setsRemaining = $this->exerciseList[$i+1]['sets'];
                    break;
                }          
                elseif($i < count($this->exerciseList)-1)
                {
                    $this->currentExercise = $this->exerciseList[$i+1]['exerciseID'];
                    $this->nextExercise();
                    break;
                }
                else
                {
                    if($this->exerciseList[0]['sets'] > 1)
                    {
                        $this->currentExercise = $this->exerciseList[0]['exerciseID'];
                        $this->setsRemaining = $this->exerciseList[0]['sets'];
                        break;
                    }
                    else
                    {
                        $this->currentExercise = $this->exerciseList[0]['exerciseID'];
                        $this->workoutCompleteFlag ++;
                        $this->nextExercise();
                        break;
                    }
                    
                }
            }
            
        }
        $this->updateSession();
    }

    function workoutComplete()
    {
        echo "under construction";
    }
    
    function selectExercise($exerciseId)
    {
        $status = false;
        for($i = 0; $i < count($this->exerciseList); $i++)
        {
            if($exerciseId == $this->exerciseList[$i]['exerciseID'] && $this->exerciseList[$i]['sets'] != 1 )  
            {
                $this->currentExercise = $this->exerciseList[$i]['exerciseID'];
                $this->setsRemaining = $this->exerciseList[$i]['sets'];
                $status = true;
                break;
            }
        }        
    }  

    function updateSession()
    {
        require_once("trainingSessionDB.php");
        $sessionDB = new trainingSessionDB;
        $sessionDB->deleteSession($this->userID);
        $sessionDB->saveSession($this->userID,$this->workoutID,$this->currentExercise,$this->setsRemaining,json_encode($this->exerciseList));
    }

    function getSessionASSOC()
    {
        $trainSession = [];
        $trainSession['workoutID'] = $this->workoutID;
        $trainSession['currentExercise'] = $this->currentExercise;
        $trainSession['setsRemaining'] = $this->setsRemaining;
        $trainSession['exerciseList'] = $this->exerciseList;        
        
        return $trainSession;
    }
}
?>