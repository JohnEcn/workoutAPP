<?php
Class workoutValidator{

    public function validateWorkoutData($name,$exerciseList,$userID)
    {
        if($name == "" || $exerciseList == NULL)
        {
            throw new Exception("Required data missing");
        }
        else
        {   
            try
            {
                $this->validateWorkoutName($userID,$name);
                $exercisesValid = $this->validateExercises($exerciseList);

                if(!$exercisesValid)
                {
                    throw new Exception("Invalid exercise");     
                }
            }
            catch(Exception $e)
            {
                throw $e;
            }
        }
    }
    public function validateWorkoutName($userID,$name)
    {
        if(preg_match('/[\'^£$%&*()}{@#~?><;>,|=+¬-]/', $name) === 1)
        {
            throw new Exception("Invalid Workout name");
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
                        throw new Exception("Workout name not unique");        
                    }
                }            
            }
        }

    }
    public function validateExercises($exerciseList)
    {
        for($i=0; $i<count($exerciseList); $i++)
        {
            $exercName = isset($exerciseList[$i]["name"]);
            $exercSets = isset($exerciseList[$i]["sets"]);
            $exercRest = isset($exerciseList[$i]["rest"]);
                
            if($exercName && $exercSets && $exercRest)
            {
                $exerciseNameValid = $this->checkExerciseName($exerciseList[$i]["name"]);
                $exerciseSetsValid = $this->checkExerciseSets($exerciseList[$i]["sets"]);
                $exerciseRestValid = $this->checkExerciseRest($exerciseList[$i]["rest"]); 
                
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
    private function checkExerciseName($name)
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
    private function checkExerciseSets($sets)
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
    private function checkExerciseRest($rest)
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
}
?>