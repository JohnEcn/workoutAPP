function saveChanges()
{
    let workoutID = JSON.parse(sessionStorage.getItem("tempWorkout")).workout.workoutID;
    let oldName = JSON.parse(sessionStorage.getItem("tempWorkout")).workout.name;
    let newName = document.getElementById("workoutName").value;
    let exercDelList = JSON.parse(sessionStorage.getItem("exForDelete"));
    let exercAddList = JSON.parse(sessionStorage.getItem("exercToAdd"));    

    if(newName != oldName)
    {
        renameWorkout(workoutID,newName,saveChangesResponseHandle);  
    } 
    if(exercDelList != null)
    {
        for(let i = 0;i < exercDelList.length; i++)
        {
            deleteExercise(workoutID,exercDelList[i],saveChangesResponseHandle);
        }        
    } 
    if(exercAddList != null)
    {
        for(let i = 0;i < exercAddList.length; i++)
        {
            insertExercise(workoutID,exercAddList[i],saveChangesResponseHandle);            
        }        
    } 

    getWorkoutList(refreshRoutineList);
}
function saveChangesResponseHandle(statusCode,responseBody)
{
    if(statusCode == 200 || statusCode == 201)
    {
        indicateOK();
    }
    else
    {
        indicateError();
    }

}
