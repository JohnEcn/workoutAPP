<?php
require_once("exercise.php");
class workout 
{
    private $name;
    private $workoutID;
    private $userID;
    private $exerciseList;

    public function __construct($name,$workoutID,$userID,$exerciseList)
    {
        if($name == NULL && $exerciseList == NULL)
        {   
            $this->userID = $userID;
            $this->workoutID = $workoutID;
            $this->retrieveWorkoutFromDB();   
        }
        else
        {
            $this->name = $name;
            $this->workoutID = $workoutID;
            $this->userID = $userID;
            $this->exerciseList = $exerciseList; 
            $this->insertWorkoutInDB();
        }  
    } 
    private function insertWorkoutInDB()
    {
        require_once("workoutDB.php");
        $workoutDB = new workoutDB(); 
        
        $routineID = $workoutDB->insertWorkout($this->userID,$this->name);
        
        $exercises = $this->exerciseList;
        
        for($i=0; $i<count($exercises); $i++)
        {   
            $name = $exercises[$i]['name'];
            $sets = $exercises[$i]['sets'];
            $rest = $exercises[$i]['rest'];
            $index = $i+1;
            $workoutID = (int)$routineID;
            $exerciseID = NULL;
            
            $exercise = new exercise($name,$exerciseID,$workoutID,$sets,$rest,$index);
        }        
    }
    private function retrieveWorkoutFromDB()
    {
        require_once("workoutDB.php");
        $workoutDB = new workoutDB();        
        $workoutInfo = $workoutDB->retrieveWorkout($this->userID,$this->workoutID);
        
        if(isset($workoutInfo[0]['routineName']))
        {
            $this->name = $workoutInfo[0]['routineName'];
            for($i=0; $i<count($workoutInfo); $i++)
            {   
                $exerciseID =  $workoutInfo[$i]['exerciseID'];         
                $exercise = new exercise(NULL,$exerciseID,$this->workoutID,NULL,NULL,NULL);
                $this->exerciseList[$i] = $exercise; 
            }       
        }         
    }
    public function toASSOC()
    {
        $workoutASSOC=[];
        $workoutASSOC['name'] = $this->name;
        $workoutASSOC['workoutID'] = $this->workoutID;

        for($i=0; $i<count($this->exerciseList); $i++)
        {   
            $exerciseASSOC  =  $this->exerciseList[$i]->toASSOC();
            $workoutASSOC['exerciseList'][$i] = $exerciseASSOC; 
        }  

        return $workoutASSOC;
    } 
    public function NULLCheck()
    {
       if($this->name == NULL)
       {
           return NULL;
       }
       else
       {
           return 1;
       }
    }
    public function changeName($newName)
    {
        $this->name = $newName; 
        require_once("workoutDB.php");
        $workoutDB = new workoutDB();  
        $workoutDB->changeWorkoutName($this->workoutID,$newName);
    }    
}
















?>