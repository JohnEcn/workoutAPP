<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userWorkouts/workout.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/api/endpoints/Endpoint.php");

Class userWorkoutEndpoint extends Endpoint
{
    public function insertWorkout($Workout,$userID)
    {
        try
        {
            //get workout data
            if(!isset($Workout['workout']['name']) || !isset($Workout['workout']['exerciseList']))
            {
                throw new Exception("Required data missing");
            }
            
            $name =  $Workout['workout']['name'];
            $exerciseList = $Workout['workout']['exerciseList'];  

            //validate
            require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userWorkouts/workoutValidator.php");
            $workoutValidator = new workoutValidator;
            $workoutValidator->validateWorkoutData($name,$exerciseList,$userID);
            
            //create and insert
            new workout($name,NULL,$userID,$exerciseList);
            parent::setResponse(201,NULL,NULL);
        }
        catch(Exception $e)
        {
            if($e->getMessage() == "Required data missing")
            {
                parent::setResponse(400,NULL,NULL);
            }
            else
            {
                parent::setResponse(409,$e->getMessage(),NULL);
            }
        }
    }
    public function deleteWorkout($WorkoutID,$userID)
    {
        try
        {
            $workout = new workout(NULL,$WorkoutID,$userID,NULL);
            $status = $workout->NULLCheck();   
            if($status != 1)
            {
                throw new Exception("Workout routine not found.");
            }
            $workout->moveWorkoutToDeleted();
            parent::setResponse(200,NULL,NULL);
        }
        catch(Exception $e)
        {
            parent::setResponse(404,["message"=>$e],NULL);
        }
    }
    public function retrieveWorkout($workoutID,$userID)
    {
        if($workoutID == "" || is_int((int)$workoutID) == false)
        {
            parent::setResponse(400,["message"=>"Parameter 'wid' must be integer."],NULL);
            return;  
        }
        
        try
        {
            $workout = new workout(NULL,$workoutID,$userID,NULL);
            if($workout->NULLCheck() == NULL)
            {
                throw new Exception("Workout routine not found.");
            }
            $workoutASSOC = $workout->toASSOC();    
            parent::setResponse(200,$workoutASSOC,NULL);
        }
        catch(Exception $e)
        {
            parent::setResponse(404,["message"=>$e->getMessage()],NULL);
        }

    }
    public function changeWorkoutName($workoutID,$userID,$newName)
    {
        try
        {
            require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userWorkouts/workoutValidator.php");
            $workoutValidator = new workoutValidator;
            $workoutValidator->validateWorkoutName($userID,$newName);

            $workout = new workout(NULL,$workoutID,$userID,NULL);
            if($workout->NULLCheck() == NULL)
            {
                parent::setResponse(404,["message"=>"Workout routine not found."],NULL);
            }
            else
            {
                $workout->changeName($newName);
                parent::setResponse(200,NULL,NULL);
            }   
        }
        catch(Exception $e)
        {
            parent::setResponse(409,["message"=>$e->getMessage()],NULL);
        }    
    }
    public function addExercise($workoutID,$userID,$newExercise)
    {
        try
        {
            $workout = new workout(NULL,$workoutID,$userID,NULL);
            if($workout->NULLCheck() == NULL)
            {
                parent::setResponse(404,["message"=>"Workout routine not found."],NULL);
                return;
            }
            else
            {
                $exerciseArr[0] = $newExercise;
                require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userWorkouts/workoutValidator.php");
                $workoutValidator = new workoutValidator;
                $validExercise = $workoutValidator->validateExercises($exerciseArr);
                if($validExercise)
                {                    
                    $workout->addExercise($newExercise);
                    parent::setResponse(201,NULL,NULL);
                }
                else
                {
                    throw new Exception("Invalid exercise");
                }    
            }
        }
        catch(Exception $e)
        {
            parent::setResponse(409,["message"=>$e->getMessage()],NULL);
        }
    }
    public function deleteExercise($workoutID,$userID,$exerciseID)
    {
        try
        {
            $workout = new workout(NULL,$workoutID,$userID,NULL);
            if($workout->NULLCheck() == NULL)
            {
                throw new Exception("Workout routine not found.");
            }
            else
            {   
                $status = $workout->deleteExercise($exerciseID);
                if($status != NULL)
                {
                    parent::setResponse(200,NULL,NULL);
                }
                else
                {
                    throw new Exception("Exercise not found.");
                }
            }   
        }
        catch(Exception $e)
        {
            parent::setResponse(404,["message"=>$e->getMessage()],NULL);
        }
    }
}