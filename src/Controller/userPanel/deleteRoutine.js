function deleteRoutine()
{
    let routineID = window.selected;
    
    if(routineID !== undefined)
    {        
        deleteWorkout(routineID,deleteRoutineResponse);  
                   
    }    
}
function deleteRoutineResponse(statusCode,responseBody)
{
    if(statusCode == 200)
    {
        refreshRoutineList(statusCode,responseBody);
        displayDeleteButton("hidden");
        indicateOK();
    }
    else
    {
        displayDeleteButton("hidden");
        indicateError();
    }

}
function displayDeleteButton(status)
{
    document.getElementById("deleteRoutineBut").style.visibility = status;
}
