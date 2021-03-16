function saveRoutine()
{
    let workoutName = document.getElementById("workoutName").value;
    if(workoutName != "")
    {
       let tempRoutine = JSON.parse(sessionStorage.getItem("tempWorkout")); 
       let exerciseList = tempRoutine.workout.exerciseList;
       saveWorkout(workoutName,exerciseList,saveRoutineResponseHandler);
       console.log(tempRoutine);
    }
}
function saveRoutineResponseHandler(statusCode,responseBody)
{
    if(statusCode == 201)
    {
        sessionStorage.removeItem("tempWorkout");
        getWorkoutList(refreshRoutineList);
        displayExerciseList();      
        document.getElementById("workoutName").value = "";             
    }
    else if(statusCode == 409)
    {
        let responseMesssage = JSON.parse(responseBody);
        displayError(responseMesssage.message);
    }
    else if(statusCode == 409)
    {
        let responseMesssage = JSON.parse(responseBody);
        displayError("Error , try again.");
    }
}