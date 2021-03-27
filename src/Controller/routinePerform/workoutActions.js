function setComplete()
{
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