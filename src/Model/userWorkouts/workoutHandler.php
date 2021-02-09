<?php
require_once("workout.php");  

$WorkoutStr =
'{
    "workout":
    {
        "name":"test",
        "userID":666,
        "exerciseList":
        [
            {"name":"Bench press","sets":6,"rest":66},
            {"name":"Push ups","sets":3,"rest":66},
            {"name":"Military Press","sets":4,"rest":66},
            {"name":"Triceps Dips","sets":6,"rest":66}
        ]
    }
}';
function insertWorkout($WorkoutStr)
{
    $name;
    $userID;
    $exerciseList;
    
    $dataStatus = workoutDataHandle($WorkoutStr,$name,$userID,$exerciseList); 
    
    if($dataStatus == "VALID")
    {  
        $workout = new workout($name,NULL,$userID,$exerciseList);
        var_dump($workout);
        return "SUCCESS";
    }
    else
    {
        echo $dataStatus;
        return $dataStatus;
    }  
}
function workoutDataHandle($JsonStr,&$name,&$userID,&$exercises)
{
    $workout = json_decode($JsonStr,true); 
    
    $nameSet = isset($workout['workout']['name']);
    $userIDSet = isset($workout['workout']['userID']);
    $exercisesSet = isset($workout['workout']['exerciseList']);
    
    if($nameSet && $userIDSet && $exercisesSet)
    {
        $name =  $workout['workout']['name'];
        $userID =  $workout['workout']['userID'];
        $exercises = $workout['workout']['exerciseList'];   
    }    
    
    $dataStatus = validateWorkoutData($name,$userID,$exercises);

    if($dataStatus != "VALID")
    {
        return $dataStatus;
    }  
    return "VALID";
}
function validateWorkoutData($name,$userID,$exerciseList)
{
    if($name == "" || $userID == "" || $exerciseList == NULL)
    {
        return "REQUIRED DATA MISSING";
    }
    else
    {   
        // require_once("workoutDB.php");  
        // $workoutBD = new workoutDBconnect();
        // $nameAvailability = $workoutBD->checkIfNameExists($userID,$workoutName);

        if(false)
        {
            return "WORKOUT NAME NOT UNIQUE";
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
            
            if(!$exerciseNameValid || !$exerciseSetsValid || !$exercRest)
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
    $exerciseData = json_decode(file_get_contents('exerciseData.json'));
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
    //var_dump($workout);
}
insertWorkout($WorkoutStr);

?>
