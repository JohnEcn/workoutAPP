function initializeRoutine()
{   
    let workoutID = window.selected;
    disableButton("startWorkoutBut");
    startSession(workoutID,initRoutineResponseHandle);
}
function initRoutineResponseHandle(statusCode,responseBody)
{
    if(statusCode == 201)
    {
        indicateOK();
        enableButton("startWorkoutBut");
        window.location.href = "/workoutApp/workingout.php";
    }
    else
    {
        indicateError();
    }      
}