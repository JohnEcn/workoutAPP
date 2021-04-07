function updateWorkoutList(httpCode,httpBody)
{
    let completeCheck = JSON.parse(httpBody)['message'] == "Workout session complete." ? true : false;
    if(httpCode == 200 && !completeCheck)
    {
        let updatedWorkout = JSON.parse(httpBody);
        let exerciseList = updatedWorkout.exerciseList;
        let activeExercise = updatedWorkout.currentExercise;
        let activeSet = updatedWorkout.setsRemaining; 
        let currentExerciseDiv = document.getElementsByClassName("activeRowContainer")[0];
        let currentSetDiv = document.getElementsByClassName("activeSetRow")[0];
        let newActiveSetID = activeExercise + "-setRow" +activeSet
        let newActiveSetDiv = document.getElementById(newActiveSetID);

        if(parseInt(currentExerciseDiv.id) != activeExercise )
        {
            currentExerciseDiv.classList.remove("activeRowContainer");
            currentExerciseDiv.classList.add("exerciseRowContainer");

            let nextCurrentExDiv = document.getElementById(activeExercise);
            nextCurrentExDiv.classList.remove("exerciseRowContainer");
            nextCurrentExDiv.classList.add("activeRowContainer");
        }

        currentSetDiv.classList.remove("activeSetRow");
        newActiveSetDiv.classList.add("activeSetRow");  
        
        for(let i = 0 ; i < exerciseList.length ; i++)
        {
            if(exerciseList[i].sets == 1 && activeExercise != exerciseList[i].exerciseID){
                let completeExerciseDiv = document.getElementById(exerciseList[i].exerciseID)
                completeExerciseDiv.classList.add("completeExerciseRowContainer"); 
            }
        }
        markCompletedSets();
        updateTimerValue();
    }
    else if(httpCode == 200 && completeCheck)
    {
        workoutComplete();
    }
    else
    {
        indicateError();
        location.reload();
    }
}
function markCompletedSets()
{
    let activeSet = document.getElementsByClassName("activeSetRow")[0].parentElement;
    while(activeSet.previousElementSibling != null)
    {
        activeSet.previousElementSibling.classList.add("completeSetRow");
        activeSet = activeSet.previousElementSibling;
    }
}
function updateStatNumbers()
{
    getSessionStats(displayStatNumbers);
}
function displayStatNumbers(httpCode,httpBody)
{
    if(httpCode == 200)
    {
        let stats = JSON.parse(httpBody);
        for (var key in stats) 
        {
            let row  = document.getElementById(key);  
            if(row != null)
            {
                for(let i = 0;i< stats[key].length;i++)
                {
                    console.log(i);
                    row.children[1].children[i].children[0].children[1].children[0].value = stats[key][i][0];
                    row.children[1].children[i].children[0].children[1].children[1].value = stats[key][i][1];
                }   
            }                    
        }
    }
}
function workoutComplete()
{
    window.location.href = "/workoutAPP/userPanel.php";
}
markCompletedSets();
updateStatNumbers();

    
