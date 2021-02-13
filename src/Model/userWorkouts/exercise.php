<?php
class exercise
{
    private $name;
    private $exerciseID;
    private $workoutID;
    private $sets;
    private $rest;
    private $exerciseIndex;
    
    function __construct($name,$exerciseID,$workoutID,$sets,$rest,$exerciseIndex)
    {
        if($exerciseID == NULL)
        {   
            $this->workoutID = $workoutID;
            $this->name = $name;            
            $this->sets = $sets;
            $this->rest = $rest; 
            $this->exerciseIndex = $exerciseIndex; 
            $this->insertExerciseInDB();    
        }
        else
        {
            $this->workoutID = (int)$workoutID;
            $this->exerciseID = (int)$exerciseID; 
            $this->retrieveExerciseFromDB();
        }
    }
    private function insertExerciseInDB()
    {
        
        require_once("workoutDB.php");
        $workoutDB = new workoutDB(); 
        
        $exercise['name'] = $this->name;
        $exercise['sets'] = $this->sets;
        $exercise['rest'] = $this->rest;
        $exercise['index'] = $this->exerciseIndex;
        
        $workoutDB->insertExercise($this->workoutID,$exercise);
    }
    private function retrieveExerciseFromDB()
    {        
        require_once("workoutDB.php");
        $workoutDB = new workoutDB();
        $result = $workoutDB->retrieveExercise($this->exerciseID);

        $this->name = $result[0]['exerciseName'];           
        $this->sets = (int)$result[0]['exerciseSets'];   
        $this->rest = (int)$result[0]['exerciseRest'];   
        $this->exerciseIndex = (int)$result[0]['exerciseIndex'];       
    }
    public function toASSOC()
    {        
        $exerciseASSOC=[];
        $exerciseASSOC['name'] = $this->name;
        $exerciseASSOC['exerciseID'] = $this->exerciseID;
        $exerciseASSOC['sets'] = $this->sets;
        $exerciseASSOC['rest'] = $this->rest;
        $exerciseASSOC['index'] = $this->exerciseIndex; 
        return $exerciseASSOC;
    }
}
?>