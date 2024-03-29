function routinePreview(routineID){
    
    let routinePreviewCheck = document.getElementsByClassName("routinePreview").length;
    if(routinePreviewCheck == 0)
    {
        getWorkout(routineID,displayRoutinePreview);  
    }
    else
    {
        hideRoutinePreview();
    }
    
}
function displayRoutinePreview(statusCode,responseBody){
    
    hideRoutinePreview();
    if(statusCode == 200)
    {   
        let workout = JSON.parse(responseBody);
        let exerciseList = workout['exerciseList'];
        let workoutID = workout['workoutID'];
        let preview = document.createElement("div");
        let rows = "";
        preview.classList.add("routinePreview");

        for(let i = 0;i <exerciseList.length;i++)
        {
            let name = exerciseList[i].name;
            let sets = exerciseList[i].sets;
            let rest = exerciseList[i].rest;
            rows += "<div id='exercisePreview'><div id='exerciseNamePreview'>"+name+"</div><div id='setsPreview'>"+sets+" sets</div><div id='restPreview'>"+rest+" sec</div></div>";           
        }

        let editRoutineButton = "<div id='editRoutineBut' onclick='editRoutine("+workoutID+");'>Edit routine</div>";
        rows += editRoutineButton;

        preview.innerHTML = rows;
        let routine = document.getElementById(JSON.parse(responseBody)['workoutID']);
        routine.parentNode.insertBefore(preview, routine.nextSibling);
    }
}
function hideRoutinePreview(){
    let existingPreview = document.getElementsByClassName("routinePreview")[0];
    if(existingPreview != undefined)
    {
        existingPreview.remove();
    }
}
function displayArrow(id){
    let exerciseRows = document.getElementById(id).parentNode.children;
    
    for(let i = 0;i<exerciseRows.length;i++)
    {   
        exerciseRows[i].children[1].children[0].style.display = "none"
    }
    document.getElementById(id).children[1].children[0].style.display = "initial";
}