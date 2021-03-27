function updateWorkoutList(httpCode,httpBody)
{
    if(httpCode == 200)
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
    }
    else
    {
        // indicateError();
        // location.reload();
    }
}
    
