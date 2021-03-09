function deleteRoutine()
{
    let routineID = window.selected;
    
    if(routineID !== undefined)
    {        
        deleteWorkout(routineID,refreshRoutineList);  
        refreshRoutineList();            
    }    
}
function displayDeleteButton(status)
{
    document.getElementById("deleteRoutineBut").style.visibility = status;
}
