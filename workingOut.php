<?php 
function getWorkoutSession()
{
    $GLOBALS["internalRequest"] = true;   
    $_GET['q'] ='user/workouts/sessions';    
    $URL  = ['user','workouts','sessions'];
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/api/index.php");
    
    $response = ApiRequest("GET",$URL,"",$_GET,$_COOKIE);
   
    if($response == 401)
    {
        header("Location:/workoutApp/");
        exit(); 
    }
    elseif($response['HttpCode'] == 204)
    {
        header("Location:/workoutApp/userPanel.php");
        exit();       
    }
    elseif($response['HttpCode'] == 200)
    {
        return $response['HttpBody'];        
    }
}
function getActiveWorkout($workoutID)
{
    $GLOBALS["internalRequest"] = true;   
    $_GET['q'] ='user/workouts';  
    $_GET['wid'] = $workoutID;   
    $URL  = ['user','workouts'];
    
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/api/index.php");    
    $response = ApiRequest("GET",$URL,"",$_GET,$_COOKIE);
    return  $response;
}
function displayWorkout($sessionInfo,$workout)
{   
    $exesciseList = $workout['exerciseList'];
    $sessionExerciseList = $sessionInfo['exerciseList'];
    $activeExercise = $sessionInfo['currentExercise'];
    $activeSet = $sessionInfo['setsRemaining'];

    for($i = 0; $i < count($exesciseList); $i++)
    {        
        $exName = $exesciseList[$i]['name'];
        $exSets = $exesciseList[$i]['sets'];
        $exRest = $exesciseList[$i]['rest'];
        $setsRemaining = $sessionExerciseList[$i]['sets'];
        $exID = $exesciseList[$i]['exerciseID'];        
        $exClass = $activeExercise == $exID ? 'activeRowContainer' : 'exerciseRowContainer';
        $exClass = $setsRemaining == 1 && $exID != $activeExercise ? 'exerciseRowContainer completeExerciseRowContainer' : $exClass;
        
        echo 
            "<div class='$exClass' id='$exID' ondblclick='selectActiveExercise($exID)'>
                <div id='exerciseContainer'>
                    <exrow>
                        <exname>$exName</exname>
                        <exsets>$exSets Sets</exsets>
                        <exrest>$exRest\" </exrest>
                    </exrow>
                        <chechmark id='checkmark'><img id='image' src='src/View/workingOutPage/checkmark.png'>
                        </chechmark>
                </div> 
                <sets>";
                
                    for($j = $exSets; $j > 0  ; $j--)
                    {
                        $setClass = $activeSet == $j && $activeExercise == $exID ? '"setRow activeSetRow"' : 'setRow';
                        $currentSet = ($exSets+1) - $j; 
                        echo 
                        "<div id='setRowContainer'>
                            <div id='$exID-setRow$j' class=$setClass>
                                <div class='setNumber'>$currentSet<span class='numb'>st</span>&nbsp;Set</div>
                                <div class='statsInput'>
                                    <input type='number' pattern=\"\d*\" class='repsInput' placeholder='reps'>
                                    <input type='number' pattern=\"\d*\" class='kgInput' placeholder='kg'>
                                </div>
                            </div> 
                            <chechmark id='checkmark'><img id='image' src='src/View/workingOutPage/checkmark.png'>
                            </chechmark>
                        </div>";
                    }  

                    echo "</sets> </div>";
     }
}    

$sessionInfo = getWorkoutSession();
$activeWorkout = getActiveWorkout($sessionInfo['workoutID'])['HttpBody'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
    <title id='title'></title>
    <link rel="stylesheet" href="src/View/workingOutPage/css/exRow.css">
    <link rel="stylesheet" href="src/View/workingOutPage/css/activeExRow.css">
    <link rel="stylesheet" href="src/View/workingOutPage/css/workingOutStructure.css">
    <link rel="stylesheet" href="src/View/workingOutPage/css/timer.css">
    <link rel="stylesheet" href="src/View/workingOutPage/css/completeExRow.css">
    <link rel="stylesheet" href="src/View/topBar/css/topBar.css"> 
    <link rel="stylesheet" href="src/View/loadingAnimations/loading.css"> 
    <link rel="stylesheet" href="src/View/statusIndicator/statusIndicator.css"> 
</head>
<body>
<div id="loadingAnimation">
        <img src="./src/View/loadingAnimations/Eclipse-0.5s-141px.gif" alt="Loading.."/>
    </div>
    <div id="statusIndicator">
        <img src="./src/View/statusIndicator/animation_500_kmccuizn.gif" alt=""/>
    </div>
    <div id="errorIndicator">
        <img src="./src/View/statusIndicator/animation_500_kmf8rvk2.gif" alt=""/>
    </div>
    <div id='workingOutPageContainer'> 
        <div id="topBar"><?php include "src/View/topBar/topBar.php" ?></div>
        <div id="leftSection">
        <?php displayWorkout($sessionInfo,$activeWorkout) ?>
        </div>
        <div id="rightSection">
            <div id="rtopSection"></div>
            <div id="rcenterSection">
                <div id='timer'> <span id='secondsSpan' stopSignal = "off"> 01:23</span>
                <div id="secondsPlusMinus"><div id='plusBut' onclick='modifyTimer(10)'>+ 10''</div><div id='minusBut' onclick='modifyTimer(-10)'>- 10''</div></div>
                        
                </div>
                <div id='setCompleteBut'onclick="setComplete()">
                    Set done!                    
                </div>        
            </div>
            <div id="rbottomSection">
                <div id='stopSkipButtons'>
                    <div id='skipSetBut' class='bottomButton' onclick="setComplete()">
                    Next exercise                   
                </div>  
                <div id='endWorkoutBut' class='bottomButton' onclick="setComplete()">
                    End workout                    
                </div>
                </div>
                 <div id='totalTimeContainer'>
                     <div id='totalTime'>
                            
                     </div>
                    
                 </div>   

                </div>
            </div>             
        </div>
    </div>
<script src="src/View/topBar/timeAndDate.js"></script> 
<script src="src/Controller/routinePerform/workoutActions.js"></script> 
<script src="src/Controller/routinePerform/updateWorkoutList.js"></script> 
<script src="src/Controller/routinePerform/timer.js"></script> 
<script src="src/Controller/clientEndPoint/endpoint.js"></script>
<script src="src/Controller/loadingIndicator/loadingAnimate.js"></script>
<script src="src/Controller/statusIndicator/statusIndicator.js"></script> 

</body>
</html>

