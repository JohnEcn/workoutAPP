<?php 

function fetchAndDisplayRoutines()
{   $_GET =[];  
    $GLOBALS["internalRequest"] = true;   
    $_GET['q'] ='user/workouts';    
    $URL  = ['user','workouts'];
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/api/index.php");
    $arrowImage =  "/src/View/userPanel/previewIcon.jpg";
    
    $response = ApiRequest("GET",$URL,"",$_GET,$_COOKIE);

    $routineList = $response['HttpBody'];
    
    if(isset($routineList['message']) && $routineList['message'] == "Workout List IS empty")
    {
        echo "<span id='noRoutineMessage'>You have no saved routines.</span>";
    }
    else
    {
        for($i = 0; $i < count($routineList); $i++)
        {
            $routineName = $routineList[$i]['routineName'];
            $routineID = $routineList[$i]['routineID'];
            echo 
            "<div class='workoutSumRow' id='$routineID' onclick ='selectedRoutine(\"$routineID\")'>
                <div id='exerciseName'>$routineName</div>
                <div id='imageDiv'><img id='arrowImage' src= .$arrowImage .  alt='' onclick ='routinePreview(\"$routineID\")' > </div>
            </div>"
            ;
        }    
    }
}
fetchAndDisplayRoutines();
?>