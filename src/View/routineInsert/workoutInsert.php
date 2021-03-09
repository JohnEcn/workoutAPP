<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="src/View/routineInsert/css/workoutInsertStructure.css">
    <link rel="stylesheet" href="src/View/routineInsert/css/mainPage.css">
    <link rel="stylesheet" href="src/View/routineInsert/css/inputFields.css">
    <link rel="stylesheet" href="src/View/routineInsert/css/autoComplete.css">
    <link rel="stylesheet" href="src/View/routineInsert/css/button.css">
</head>
<body>
<div id='exerciseInsertContainer'>
    <div id='itopSection'>
        <h4 id='routineContainerTitle'>Insert a new workout routine</h3>
    </div>     
    <div id='icenterSection'>
        <table id="inputTable">                
                <tr class="inputTableRow">
                    <td id='exNameTD'>                      
                        <div class="input-container">
                            <div class="autocomplete">
                                <input autocomplete="off" class="exercInput" required="" onkeyup='autoComplete()' id="exerciseNameInp" type="text" >
                                <label>Exercise</label>                         
                            </div>
                            <div id="autocompleteContainer"></div>
                        </div>
                    </td>
                    <td id='setsNumTD'>
                        <div class="input-container">
                            <input autocomplete="off" class = "exercInput" required="" id="setsNum" type="text" >
                            <label>Number of sets</label>
                        </div>
                    </td>
                    <td id='secRestTD' colspan="5">
                        <div class="input-container">
                            <input  autocomplete="off" class = "exercInput " required="" id="secondsRest" type="text" >
                            <label>Rest time</label>
                        </div>
                    </td>
                </tr>
                <tr>                    
                    <td id='addBtnTD' colspan="3">                       
                        <button id="addExerciseBtn" class="btn draw-border" onclick="createExercise()">Add exercise</button>
                        <div id="inputErrorMessage"></div> </td></tr>
                    </td> 
                 </tr>                    
            </table>

    </div>
    <div id='ibottomSection'>   
            <div id="saveWorkoutDiv">          
            <div  class="input-container">                
                <input id="workoutName" class = "exercInput " type="text" placeholder="Workout Name">
                <button class = "btn draw-border" id="saveWorkoutButton" onclick="saveWorkout()">Save workout routine</button>      
            </div>
           </div> 
            <table id="exercTable"></table>
</div>     
    <script src="./exerciseAutocomplete/requestAutocomplete.js"></script> 
    <script src="sendWorkout.js"></script>
    <script src="workoutManage.js"></script>
    <script src="onload.js"></script>
    <script src="./userPanel/requestPage.js"></script> 
    <script src="./userPanel/timeAndDate.js"></script> 
    <script src="./userPanel/selectRoutine.js"></script> 
    <script src="./userPanel/deleteRoutine.js"></script> 
    <script src="./userPanel/startWorkout.js"></script>   
</body>
</html>









































</div>