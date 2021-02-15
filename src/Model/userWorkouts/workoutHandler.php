<?php
require_once("workout.php"); 

function insertWorkout($Workout,$userID)
{
    $name;
    $exerciseList;
    
    $dataStatus = workoutDataHandle($Workout,$name,$exerciseList,$userID); 
    
    if($dataStatus == "VALID")
    {  
        $workout = new workout($name,NULL,$userID,$exerciseList);
        return "SUCCESS";
    }
    else
    {
        return $dataStatus;
    }  
}
function workoutDataHandle($workout,&$name,&$exercises,$userID)
{   
   
    $nameSet = isset($workout['workout']['name']);
    $exercisesSet = isset($workout['workout']['exerciseList']);
    
    if($nameSet &&  $exercisesSet)
    {
        $name =  $workout['workout']['name'];
        $exercises = $workout['workout']['exerciseList'];   
    }    
    
    $dataStatus = validateWorkoutData($name,$exercises,$userID);

    if($dataStatus != "VALID")
    {
        return $dataStatus;
    }  
    return "VALID";
}
function validateWorkoutData($name,$exerciseList,$userID)
{
    if($name == "" || $exerciseList == NULL)
    {
        return "REQUIRED DATA MISSING";
    }
    elseif(preg_match('/[\'^£$%&*()}{@#~?><;>,|=+¬-]/', $name) === 1)
    {
        return "INVALID WORKOUT NAME";
    }
    else
    {   
        require_once("workoutDB.php");  
        $workoutBD = new workoutDB();
        $workoutList = $workoutBD->getWorkoutList($userID);
        
        if(isset($workoutList[0]['routineName']))
        {
            for($i=0; $i<count($workoutList); $i++)
            {
                if($workoutList[$i]['routineName'] == $name)
                {
                    return "WORKOUT NAME NOT UNIQUE";
                }
            }            
        }
        
        $exercisesValid = validateExercises($exerciseList);

        if(!$exercisesValid)
        {
            return "INVALID EXERCISE"; 
        }
    }
    return "VALID";
}
function validateExercises($exerciseList)
{
    for($i=0; $i<count($exerciseList); $i++)
    {
        $exercName = isset($exerciseList[$i]["name"]);
        $exercSets = isset($exerciseList[$i]["sets"]);
        $exercRest = isset($exerciseList[$i]["rest"]);
            
        if($exercName && $exercSets && $exercRest)
        {
            $exerciseNameValid = checkExerciseName($exerciseList[$i]["name"]);
            $exerciseSetsValid = checkExerciseSets($exerciseList[$i]["sets"]);
            $exerciseRestValid = checkExerciseRest($exerciseList[$i]["rest"]); 
            
            if(!$exerciseNameValid || !$exerciseSetsValid || !$exerciseRestValid)
            {
                return false; 
            }            
        }
        else
        {
            return false; 
        }
    }
    return true;   
}
function checkExerciseName($name)
{
    $exerciseData = json_decode(file_get_contents(dirname(__FILE__).'/exerciseData.json'));
    for($i = 0;$i < count($exerciseData); $i++)
    {
        if(strtoupper($name) == strtoupper($exerciseData[$i][0]))
        {
            return true;
        }
    }
    return false;
}
function checkExerciseSets($sets)
{
    if(is_int($sets) && $sets >= 1 && $sets <=20) 
    {
        return true;
    }
    else
    {
        return false;
    }
}
function checkExerciseRest($rest)
{
    if(is_int($rest) && $rest >= 10 && $rest <=300) 
    {
        return true;
    }
    else
    {
        return false;
    }

}
function retrieveWorkout($workoutID,$userID)
{
    $workout = new workout(NULL,$workoutID,$userID,NULL);
    if($workout->NULLCheck() == NULL)
    {
        return NULL;
    }
    else
    {
        $workoutASSOC = $workout->toASSOC();    
        return $workoutASSOC;
    }
    
}
function changeWorkoutName($workoutID,$userID,$newName)
{
    if(preg_match('/[\'^£$%&*()}{@#~?><;>,|=+¬-]/', $newName) === 1)
    {
        return "INVALID WORKOUT NAME";
    }
    else
    {   
        require_once("workoutDB.php");  
        $workoutBD = new workoutDB();
        $workoutList = $workoutBD->getWorkoutList($userID);
        
        if(isset($workoutList[0]['routineName']))
        {
            for($i=0; $i<count($workoutList); $i++)
            {
                if($workoutList[$i]['routineName'] == $newName)
                {
                    return "WORKOUT NAME NOT UNIQUE";
                }
            }            
        }
    }

    $workout = new workout(NULL,$workoutID,$userID,NULL);

    if($workout->NULLCheck() == NULL)
    {
        return "INVALID WORKOUT ID";
    }
    else
    {
        $workout->changeName($newName);
        return "SUCCESS";
    }   
}
?>
