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

    btnDiv.id = "startWorkoutBut";
    btnDiv.innerHTML = "Start workout";
    setTimeout(() => 
    {
        btnDiv.addEventListener("click", function (){
        initializeRoutine();
        });
    }, 1000);    
}
function dismissCurrentRoutineResponseHandler(httpCode,httpBody)
{
    if(httpBody == 200)
    {
        
        btnDiv.id = "startWorkoutBut";
        btnDiv.innerHTML = "Start workout";
        setTimeout(() => 
        {   indicateOK();
            btnDiv.addEventListener("click", function (){
            initializeRoutine();
            });
        }, 1000); 
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