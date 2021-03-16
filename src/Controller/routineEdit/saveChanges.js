function saveChanges()
{
    let workoutID = JSON.parse(sessionStorage.getItem("tempWorkout")).workout.workoutID;
    let oldName = JSON.parse(sessionStorage.getItem("tempWorkout")).workout.name;
    let newName = document.getElementById("workoutName").value;
    let exercDelList = JSON.parse(sessionStorage.getItem("exForDelete"));
    let exercAddList = JSON.parse(sessionStorage.getItem("exercToAdd"));    

    if(newName != oldName)
    {
        renameWorkout(workoutID,newName,console.log);  
    } 
    if(exercDelList != null)
    {
        for(let i = 0;i < exercDelList.length; i++)
        {
            deleteExercise(workoutID,exercDelList[i],console.log);
        }        
    } 
    if(exercAddList != null)
    {
        for(let i = 0;i < exercAddList.length; i++)
        {
            insertExercise(workoutID,exercAddList[i],console.log);            
        }        
    } 

    getWorkoutList(refreshRoutineList);
}
