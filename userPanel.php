<?php 
function checkUserAuth()
{
    $GLOBALS["internalRequest"] = true;   
    $_GET['q'] ='user/workouts';    
    $URL  = ['user','workouts'];
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/api/index.php");
    
    $response = ApiRequest("GET",$URL,"",$_GET,$_COOKIE);

    $authStatus = $response == 401 ? false : true;
    return $authStatus;
}
$authStatus = checkUserAuth();

if($authStatus == false)
{
    header("Location:/workoutApp/");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="stylesheet" href="src/View/userPanel/css/structure.css">
    <link rel="stylesheet" href="src/View/userPanel/css/routinePreview.css">
    <link rel="stylesheet" href="src/View/topBar/css/topBar.css"> 
    <link rel="stylesheet" href="src/View/loadingAnimations/loading.css"> 
    <link rel="stylesheet" href="src/View/statusIndicator/statusIndicator.css">  
    <link rel="stylesheet" href="src/View/chartsPage/css/charts.css">    
    <link rel="stylesheet" href="src/View/routineInsert/css/backButton.css">
    <title>Document</title>
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
    <userPanel>
        <div id="topBar">   
            <?php include "src/View/topBar/topBar.php" ?>         
        </div>
        <div id="leftSection">
            <?php include "src/View/chartsPage/charts.php"?> 
        </div>
        <div id="rightSection">
           <div id="rtopSection">
            <h4 id='routineContainerTitle'>Your workout routines</h3></div> 
           <div id="rcenterSection">  
               <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/View/userPanel/routineList.php"); ?>                        
            </div>                        
            <div id="rbottomSection">
                <div id='secondaryBtns'>
                <div id="addWorkoutBut" onclick="InsertRoutinePage()">Add new routine</div><div id='chartsIcon'><img id='chartImg' onclick='requestStatsPage()' src='src/View/chartsPage/css/chart-646.png'></div></div>
                <div id="startWorkoutBut" onclick="initializeRoutine()">Start workout</div>
                <div id="deleteRoutineBut" onclick="deleteRoutine()">Delete selected routine</div>
            </div>
        </div>      
    </userPanel>
    
    
    <script src="src/View/topBar/timeAndDate.js"></script>   
    <script src="src/Controller/userPanel/selectRoutine.js"></script>
    <script src="src/Controller/userPanel/deleteRoutine.js"></script>
    <script src="src/Controller/userPanel/insertRoutine.js"></script>
    <script src="src/Controller/userPanel/leftSectionController.js"></script>
    <script src="src/Controller/routinePreview/routinePreview.js"></script>
    <script src="src/Controller/userPanel/refreshRoutineList.js"></script>
    <script src="src/Controller/clientEndPoint/endpoint.js"></script>
    <script src="src/Controller/clientEndPoint/pageFetch.js"></script> 
    <script src="src/Controller/routineInsert/exerciseHandle.js"></script>   
    <script src="src/Controller/routineInsert/exerciseAutocomplete.js"></script>  
    <script src="src/Controller/routineInsert/routineSave.js"></script> 
    <script src="src/Controller/routineEdit/routineEdit.js"></script>  
    <script src="src/Controller/routineEdit/saveChanges.js"></script> 
    <script src="src/Controller/loadingIndicator/loadingAnimate.js"></script> 
    <script src="src/Controller/statusIndicator/statusIndicator.js"></script> 
    <script src="src/View/userPanel/responsivePages.js"></script> 
    <script src="src/Controller/logOut/logOutAndRedirect.js"></script> 
    <script src="src/Controller/routinePerform/startRoutine.js"></script>
    <script src="src/Controller/clientEndPoint/endPoint.js"></script> 
    <script src="src/Controller/userPanel/continueRoutine.js"></script> 
    <script src="src/Controller/userPanel/chartsPage.js"></script>                   
</body>
</html>    