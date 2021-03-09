function selectedRoutine(routineID)
{    
    let prevSelectedRoutine = document.getElementsByClassName("selected")[0];
    
    if(prevSelectedRoutine !== undefined)
    {
        prevSelectedRoutine.classList.remove("selected");
    }    
    
    let selectedRoutine = document.getElementById(routineID);
    selectedRoutine.classList.add("selected");

    window.selected = routineID;
    hideRoutinePreview();
    displayArrow(routineID);
    displayDeleteButton("initial");       
}