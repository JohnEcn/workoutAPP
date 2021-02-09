<?php

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
            $this->retrieveWorkoutInDB();    
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
        
        require_once("exercise.php");
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
        //2.Get the auto incremeted routineId of the previous insertion
        //3.Create a new exercise using the routineID
    }
    private function retrieveWorkoutInDB()
    {
        $this->name = "Push workout";
        $this->workoutID = "A123";
        $this->userID = 1592;
        $this->exerciseList = 
        [
            ["workoutID"=>"A123","exerciseID"=>9832,"name"=>"Bench Press","sets"=>4,"rest"=>120],
            ["workoutID"=>"A123","exerciseID"=>9833,"name"=>"Overhead Press","sets"=>3,"rest"=>90],
            ["workoutID"=>"A123","exerciseID"=>9834,"name"=>"Tricep extensions","sets"=>6,"rest"=>60],
            ["workoutID"=>"A123","exerciseID"=>9835,"name"=>"Shoulder press","sets"=>6,"rest"=>90]
        ];
    }    
    public function addExercise($exercise)
    {
        //add exercise to exericeList
    }
    public function setName($name)
    {
        $this->name = $name;
    }  
    public function getName()
    {
        return $this->name;
    }
    public function getWorkoudID()
    {
        return $this->workoutID;
    }
    public function getExerciseList()
    {
        return $this->exerciseList;
    }
    
}
















?>