<?php
class workoutLog
{
    private $userID;    
    function __construct($userID)
    {  
        $this->userID = $userID;        
    }      
    function addWorkoutLogEntry($workoutID,$exerciseStats,$workout,$date)
    {
        require_once("workoutLogDB.php");
        $logsDB = new workoutLogDB(); 
        $sessID = $logsDB->addSessionEntry($this->userID,$workoutID,$date)[0];
        
        $exercList = $workout;
        $exercStats = $exerciseStats;

        for($i = 0;$i<count($exercList);$i++)
        {
            $exName = $exercList[$i]['name'];
            $exID = $exercList[$i]['exerciseID'];
            $totalVolume = null;
            $oneRepMax = 0;
            
            if(isset($exercStats[$exID]))
            {
                for($j = 0;$j<count($exercStats[$exID]);$j++)
                {
                    $reps = $exercStats[$exID][$j][0];
                    $weight = $exercStats[$exID][$j][1];
                    $totalVolume += $reps * $weight;
                    $onerm = $weight / ( 1.0278 - 0.0278 * $reps );
                    $oneRepMax = $onerm > $oneRepMax ? $onerm : $oneRepMax;
                }
                $logsDB->insertExercLog($sessID,$exName,floor($totalVolume),floor($oneRepMax));
            }          
        }
    }
}

?>