function setComplete()
{
    addExerciseStatEntry();
    updateSession('setComplete',null,updateWorkoutList);
    startTimer();
}
function selectActiveExercise(exid)
{
    let activeExercID = document.getElementsByClassName("activeRowContainer")[0].id;

    if(activeExercID != exid)
    {
        updateSession(null,exid,updateWorkoutList);
        document.getElementById("secondsSpan").setAttribute("stopSignal","On")
    }   
}

function endWorkout()
{
    updateSession('endWorkout',null,endWorkouthandler);
}
function endWorkouthandler(httpCode,httpBody)
{
    if(httpCode == 200)
    {  
        location.href = '/workoutApp/userPanel.php';
    }
}
