function addExerciseStatEntry()
{
    let activeSet = document.getElementsByClassName("activeSetRow")[0];
    let activeExerciseID = document.getElementsByClassName("activeRowContainer")[0].id;
    let reps = activeSet.children[1].children[0].value;
    let weight = activeSet.children[1].children[1].value;

    if(reps == "" || weight == "" )
    {
        reps = 0;
        weight = 0;
    }   
    newSessionStatEntry(parseInt(activeExerciseID),parseInt(reps),parseInt(weight),console.log);    
}