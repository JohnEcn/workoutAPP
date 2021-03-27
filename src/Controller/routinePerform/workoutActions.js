function setComplete()
{
    updateSession('setComplete',null,updateWorkoutList);
    startTimer();
}
function selectActiveExercise(exid)
{
    updateSession(null,exid,updateWorkoutList);
    updateTimerValue();
}