function createExercise()
{
    let exercise = extractExercise();
    let exerciseValid = validateExercise(exercise);
    
    if(exerciseValid == true)
    {
        addExerciseToLS(exercise); 
        displayError("");  
    }
    else
    {
        displayError(exerciseValid);
    }
}
function extractExercise()
{
    let exercArr = [];
    exercArr[0] = document.getElementById("exerciseNameInp").value;
    exercArr[1] = parseInt(document.getElementById("setsNum").value);
    exercArr[2] = parseInt(document.getElementById("secondsRest").value);
    
    document.getElementById("exerciseNameInp").value = "";
    document.getElementById("setsNum").value = "";
    document.getElementById("secondsRest").value = "";

    return exercArr;
}
function validateExercise(exercise)
{
    let name = exercise[0];
    let sets = exercise[1];
    let rest = exercise[2];
    let response = true;

    if(name == "" || isNaN(sets) || isNaN(rest))
    {
        response = "Incorrect Values";
    }
    else if(sets < 1 || sets > 20)
    {
        response = "Invalid sets value";
    }
    else if(rest < 10 || rest > 300)
    {
        response = "Invalid rest value";
    }
    return response;

}
function addExerciseToLS(exercise)
{
    let tempWorkout = sessionStorage.getItem("tempWorkout");
    let exerciseName = exercise[0];
    let exerciseSets = exercise[1];
    let exerciseRest = exercise[2];

    if(tempWorkout == null)
    {
        let newWorkout = 
        {
            workout:
            {
                name:"Test Workout",
                exerciseList:
                [
                    {name:exerciseName,sets:exerciseSets,rest:exerciseRest}
                ]
            }
        }
        sessionStorage.setItem("tempWorkout", JSON.stringify(newWorkout));
    }
    else
    {
        let existingWorkout = JSON.parse(tempWorkout);
        let exercise = {name:exerciseName,sets:exerciseSets,rest:exerciseRest};
        existingWorkout.workout.exerciseList.push(exercise);
        sessionStorage.setItem("tempWorkout", JSON.stringify(existingWorkout));
    }   
    displayExerciseList();
}
function displayExerciseList()
{
    let tempWorkout = sessionStorage.getItem("tempWorkout");
    let table = document.getElementById("exercTable");
    table.innerHTML = "";

    if(tempWorkout == null)
    {
        let row = document.createElement("span");
        let message = document.createTextNode("Insert some exercises!");
        
        row.classList.add("AddExerciseMessage");
        row.appendChild(message);
        table.appendChild(row);       
    }
    else
    {   table.innerHTML = "";
        let exerciseList = JSON.parse(tempWorkout).workout.exerciseList;
        for(let i = 0; i<exerciseList.length; i++)
        {
            diplayExercise(exerciseList[i]);           
        }
    }
    displayError("");   
}
function diplayExercise(ex)
{    
    let table = document.getElementById("exercTable");
    let row = document.createElement("tr");
    row.classList.add("exercRow")
    let tbRow = table.appendChild(row);
    
    let textNodeName = document.createTextNode(ex.name);
    let textNodeSets = document.createTextNode(ex.sets + " sets");
    let textNodeSeconds = document.createTextNode(ex.rest + " seconds");

    tbRow.appendChild(document.createElement("td")).appendChild(textNodeName);
    tbRow.appendChild(document.createElement("td")).appendChild(textNodeSets);
    tbRow.appendChild(document.createElement("td")).appendChild(textNodeSeconds);

    let deleteButton =document.createElement("div");
    deleteButton.classList.add("deletBut");  
    
    deleteButton.addEventListener("click",function(){
            deleteTempExercise(this);
        },false);

    let img = "<img class='deleteImg' title = 'Delete exercise' src = 'https://pngimage.net/wp-content/uploads/2018/05/delete-symbol-png-8.png'/>";
    deleteButton.innerHTML = img;

    tbRow.append(deleteButton);

    document.getElementById("saveWorkoutDiv").style.display ="flex";
}  
function deleteTempExercise(exRow)
{
    let exerciseName = exRow.parentElement.children[0].innerText;
    let tempWorkout = JSON.parse(sessionStorage.getItem("tempWorkout"));
    let exerciseList = tempWorkout.workout.exerciseList;
    if(exerciseList.length ==1)
    {
        sessionStorage.removeItem("tempWorkout");
        displayExerciseList();
        return 1;
    }
    for(let i = 0; i < exerciseList.length; i++)
    {
        if(exerciseName == exerciseList[i].name )
        {
            exerciseList.splice(i,1);
        }
    }

    tempWorkout.workout.exerciseList = exerciseList;
    sessionStorage.setItem("tempWorkout", JSON.stringify(tempWorkout));
    displayExerciseList();
}
function displayError(error){
    let errorText = document.getElementById("inputErrorMessage");
    errorText.innerHTML = error;
}


