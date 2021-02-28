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
        return "Required data missing";
    }
    elseif(preg_match('/[\'^£$%&*()}{@#~?><;>,|=+¬-]/', $name) === 1)
    {
        return "Invalid Workout name";
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
                    return "Workout name not unique";
                }
            }            
        }
        
        $exercisesValid = validateExercises($exerciseList);

        if(!$exercisesValid)
        {
            return "Invalid exercise"; 
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
        return "Invalid workout routine name";
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
                    return "Workout name not unique";
                }
            }            
        }
    }

    $workout = new workout(NULL,$workoutID,$userID,NULL);

    if($workout->NULLCheck() == NULL)
    {
        return "Invalid Workout";
    }
    else
    {
        $workout->changeName($newName);
        return "SUCCESS";
    }   
}
function addExercise($workoutID,$userID,$newExercise)
{
    $workout = new workout(NULL,$workoutID,$userID,NULL);

    if($workout->NULLCheck() == NULL)
    {
        return "Workout routine not found.";
    }
    else
    {   $exerciseArr[0] = $newExercise;
        $validExercise = validateExercises($exerciseArr);
        if($validExercise)
        {
            
            $workout->addExercise($newExercise);
            return "SUCCESS";
        }
        else
        {
            return "Invalid exercise";
        }        
    }   
}
function deleteExercise($workoutID,$userID,$exerciseID)
{
    $workout = new workout(NULL,$workoutID,$userID,NULL);

    if($workout->NULLCheck() == NULL)
    {
        return "Workout routine not found.";
    }
    else
    {   
        $status = $workout->deleteExercise($exerciseID);

        if($status != NULL)
        {
               return "Exercise succesfully deleted."; 
        }
        else
        {
            return "Exercise not found."; 
        }
    }   
}
?>
