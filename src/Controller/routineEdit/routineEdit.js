function editRoutine(wid)
{
    requestPage("workoutInsert",displayEditRoutinePage); 
    window.workoutForEdit = wid;  
    sessionStorage.removeItem("exForDelete");
    sessionStorage.removeItem("exercToAdd");
}
function displayEditRoutinePage(statusCode,responseBody)
{
    //Loads the page that is used for routine insertion and displays the selected routine
    displayContent(statusCode,responseBody);
    getWorkout(window.workoutForEdit,insertWorkoutToSessionStorage);
    window.workoutForEdit = null;
}
function insertWorkoutToSessionStorage(statusCode,responseBody)
{
    if(statusCode == 200)
    {
        //the routine for edit is inserted in sessionStorage and displayed
        sessionStorage.removeItem("tempWorkout");        
        let workout = {'workout':JSON.parse(responseBody)};
        sessionStorage.setItem("tempWorkout",JSON.stringify(workout));
        displayExerciseList();
        editPageModify();
    }
}
function editPageModify()
{
    //This function edits the routine Insert page so it can be used to edit a routine
    let title = document.getElementById("routineContainerTitle");
    title.innerText = "Edit workout ... ";
    
    let saveBtn = document.getElementById("saveWorkoutButton");
    saveBtn.innerText = "Save changes";
    saveBtn.onclick = function(){saveChanges(window.workoutForEdit)};

    let addExBtn = document.getElementById("addExerciseBtn");
    addExBtn.onclick = function(){newExercise()};
    
    let workoutNameField = document.getElementById("workoutName");
    let workoutName = JSON.parse(sessionStorage.getItem("tempWorkout")).workout.name;
    workoutNameField.value = workoutName;

    let exerciseList = JSON.parse(sessionStorage.getItem("tempWorkout")).workout.exerciseList;
    let exerciseListContainer = document.getElementById("exercTable");

    //The delete button is removed and added again so the event listener can change to the appropriate function
    for(let i = 0; i< exerciseListContainer.children.length; i++)
    {    
        exercId =  exerciseList[i].exerciseID == undefined ? "newEx:" + i : "exid:" + exerciseList[i].exerciseID; 
        exerciseListContainer.children[i].id = exercId; 
        exerciseListContainer.children[i].children[3].remove();
                
        let deleteButton =document.createElement("div");
        let img = "<img class='deleteImg' title = 'Delete exercise' src = 'https://pngimage.net/wp-content/uploads/2018/05/delete-symbol-png-8.png'/>";
        deleteButton.innerHTML = img;
        deleteButton.classList.add("deletBut");  
        if(exerciseList[i].exerciseID == undefined)
        {
            deleteButton.addEventListener("click",function(){deleteNewEx("newEx:" + i)},false); 
        }
        else
        {
            deleteButton.addEventListener("click",function(){markForDeletion(exerciseList[i].exerciseID)},false); 
        } 
        exerciseListContainer.children[i].appendChild(deleteButton);                     
    }
    markForDeletion();
    displayNewExercises();
}
function markForDeletion(exid)
{
    //This function is executed when the delete button is pressed at an exercise row
    //The exercise is marked and gets added to the sessionStorage in an array that contains the exercises for delete
    //Also when an exercise is called for deletion but already exists in the sessionStorage array , that means that
    //This array gets removed from the exercises for delete
    let exForDelete = sessionStorage.getItem("exForDelete");
    if(exForDelete == null && exid != null)
    {
        sessionStorage.setItem("exForDelete",JSON.stringify([exid]));
    }
    else if(exid != null)
    {
        let exList = JSON.parse(exForDelete);
        if(exList.indexOf(exid) != -1)
        {
            exList.splice(exList.indexOf(exid), 1);
            editPageModify();
            sessionStorage.setItem("exForDelete",JSON.stringify(exList));
            
            let exRow = document.getElementById("exid:"+ exid);
            exRow.classList.remove("markedDel");     
        }
        else
        {
            exList.push(exid);
            sessionStorage.setItem("exForDelete",JSON.stringify(exList));
        }
    }
    
    let deletionList = JSON.parse(sessionStorage.getItem("exForDelete"));
    if(deletionList != null)
    {
        for(let i = 0; i< deletionList.length; i++)
        {
            let exRow = document.getElementById("exid:"+ deletionList[i]);
            exRow.classList.add("markedDel");     
        }
    
    }    
}
function newExercise()
{
    let exName = document.getElementById("exerciseNameInp").value;
    let exSets = document.getElementById("setsNum").value;
    let exRest = document.getElementById("secondsRest").value;

    createExercise();
    let error = document.getElementById("inputErrorMessage").innerText;

    if(error == "")
    {
        let exAddList = JSON.parse(sessionStorage.getItem("exercToAdd"));
        if(exAddList == null)
        {
            sessionStorage.setItem("exercToAdd",JSON.stringify([{name:exName,sets:parseInt(exSets),rest:parseInt(exRest),index:-1}]));
        }
        else
        {
            let Exercise = {name:exName,sets:exSets,rest:exRest};
            exAddList.push(Exercise);
            sessionStorage.setItem("exercToAdd",JSON.stringify(exAddList));
        }
    }  
    editPageModify();
}
function displayNewExercises()
{
    let exerciseListContainer = document.getElementById("exercTable").children;
    for(let i = 0; i< exerciseListContainer.length; i++)
    {
        if(exerciseListContainer[i].id.substring(0, 5   ) == "newEx")
        {
            exerciseListContainer[i].classList.add("newExerc");
        }
    }    
}
function deleteNewEx(exid)
{
    let exIndex = exid.substring(6,8);
    let tempWorkout = JSON.parse(sessionStorage.getItem("tempWorkout"));
        
    let exList = tempWorkout.workout.exerciseList;
    exList.splice(exIndex,1);

    sessionStorage.removeItem("tempWorkout");
    sessionStorage.setItem("tempWorkout",JSON.stringify(tempWorkout));

    let exercSRow = document.getElementById(exid);
    let name = exercSRow.children[0].innerText;
    let sets = exercSRow.children[1].innerText;
    let rest = exercSRow.children[2].innerText; 
    let newExList = JSON.parse(sessionStorage.getItem("exercToAdd"));
    
    for(let i = 0;i<newExList.length;i++)
    {
        if(newExList[i].name == name)
        {
            newExList.splice(i, 1);
        }
    }
    sessionStorage.removeItem("exercToAdd");
    sessionStorage.setItem("exercToAdd",JSON.stringify(newExList));
    exercSRow.remove();   
    editPageModify(); 

}
