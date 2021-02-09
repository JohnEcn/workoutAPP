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
            $this->execiseIndex = $exerciseIndex; 
            $this->insertExerciseInDB();    
        }
        else
        {
            ///retriver exercise
        }
    }
    private function insertExerciseInDB(){
        
        require_once("workoutDB.php");
        $workoutDB = new workoutDB(); 
        
        $exercise['name'] = $this->name;
        $exercise['sets'] = $this->sets;
        $exercise['rest'] = $this->rest;
        $exercise['index'] = $this->execiseIndex;
        
        $workoutDB->insertExercise($this->workoutID,$exercise);
    }
}
?>