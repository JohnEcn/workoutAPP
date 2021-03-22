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
    <link rel="stylesheet" href="src/View/routineInsert/css/backButton.css">
    <link onload='doneLoading();' rel="stylesheet" href="src/View/routineInsert/css/button.css">
</head>
<body>
<div id="backBtnDiv" onclick='changeActivePage("right");'>
    <span id='backBtn'>Back</span>
</div>
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
                                <input autocomplete="off" class="exercInput" required="" onkeyup='requestSuggestions()' id="exerciseNameInp" type="text" >
                                <label>Exercise</label>                         
                            </div>
                            <div id="autocompleteContainer"></div>
                        </div>
                    </td>
                    <td id='setsNumTD'>
                        <div class="input-container">
                            <input autocomplete="off" class = "exercInput" required="" id="setsNum" type="number" >
                            <label>Number of sets</label>
                        </div>
                    </td>
                    <td id='secRestTD' colspan="5">
                        <div class="input-container">
                            <input  autocomplete="off" class = "exercInput " required="" id="secondsRest" type="number" >
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
                <button class = "btn draw-border" id="saveWorkoutButton" onclick="saveRoutine()">Save workout routine</button>      
            </div>
            <table id="exercTable"></table>
        </div> 
    </div>           
</body>
</html>









































</div>