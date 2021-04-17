function displayContButt()
{
    btnDiv = document.getElementById("startWorkoutBut");
    btnDiv.id = "contRoutineBut";
    btnDiv.innerText = "Continue Routine";
    btnDiv.innerHTML = "<div id='contText' onclick ='contCurrentRoutine()'>Continue Routine</div> <div id='dismissBut' onclick ='dismissCurrentRoutine()'>X</div>"
    btnDiv.onclick = null;
}
function dismissCurrentRoutine()
{
    updateSession('endWorkout',null,dismissCurrentRoutineResponseHandler);
    btnDiv = document.getElementById("contRoutineBut");
}
function dismissCurrentRoutineResponseHandler(httpCode,httpBody)
{
    if(httpCode == 200)
    {
        
        btnDiv.id = "startWorkoutBut";
        btnDiv.innerHTML = "Start workout";
        setTimeout(() => 
        {   indicateOK();
            requestStatsPage();  
            btnDiv.addEventListener("click", function (){
            initializeRoutine();
            });  
        }, 200); 
    }     
}
function contCurrentRoutine(){
    window.location.href = "WorkingOut.php";
}
function checkIfSessionExists(statusCode,httpBody)
{
    if(statusCode == 200)
    {
        displayContButt();
    }
}

getSession(checkIfSessionExists);