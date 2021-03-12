<?php 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="stylesheet" href="src/View/userPanel/css/structure.css">
    <link rel="stylesheet" href="src/View/userPanel/css/routinePreview.css">
    <link rel="stylesheet" href="src/View/topBar/css/topBar.css">    
    <title>Document</title>
</head>
<body>
    <userPanel>
        <div id="topBar">   
            <?php include "src/View/topBar/topBar.php" ?>         
        </div>
        <div id="leftSection">
        </div>
        <div id="rightSection">
           <div id="rtopSection">
            <h4 id='routineContainerTitle'>Your workout routines</h3></div> 
           <div id="rcenterSection">  
               <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/View/userPanel/routineList.php"); ?>                        
            </div>                        
            <div id="rbottomSection">
                <div id="addWorkoutBut" onclick="InsertRoutinePage()">Add new routine</div>
                <div id="startWorkoutBut" onclick="startWorkout()">Start workout</div>
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
    <!-- <script src="./exerciseAutocomplete/requestAutocomplete.js"></script> 
    <script src="sendWorkout.js"></script>
    <script src="workoutManage.js"></script>
    <script src="onload.js"></script>
    <script src="./userPanel/requestPage.js"></script> 
    <script src="./userPanel/timeAndDate.js"></script> 
    <script src="./userPanel/deleteRoutine.js"></script> 
    <script src="./userPanel/startWorkout.js"></script>
    <script src="./userPanel/responsiveJS.js"></script> -->
</body>
</html>    