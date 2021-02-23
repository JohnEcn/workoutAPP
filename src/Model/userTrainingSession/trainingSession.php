<?php
require_once("../userWorkouts/workout.php");
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
        $this->exerciseList = $this->BsortExerciseArray($this->workout->toAssoc()['exerciseList']);
        $this->workoutID = $this->workout->toAssoc()['workoutID'];    
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
        $session = $sessionDB->loadSession($this->userID)[0];
      
        $this->workoutID = (int)$session["workoutID"];        
        $this->currentExercise = (int)$session["currentExerciseID"];        
        $this->setsRemaining = (int)$session["setsRemaining"];        
        $this->exerciseList = json_decode($session["exerciseList"],true);
        $this->lastModified = $session["lastModified"];
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
}

$workoutID = 2;
$userID = 2;
$workout = new workout(NULL,$workoutID,$userID,NULL);
//$session = new trainingSession($workout,$userID);
$session = new trainingSession(NULL,$userID);
//$session->nextSet();
//$session->nextExercise();
$session->selectExercise(5);
var_dump($session);













?>