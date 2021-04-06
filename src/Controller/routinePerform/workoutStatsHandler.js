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

function editExerciseStatEntry(element)
{   
    setStatus = element.parentElement.parentElement.parentElement.className;
    if(setStatus == 'completeSetRow')
    {
        setIndex = parseInt(element.parentElement.parentElement.children[0].innerText.substring(0,2));
        exercId = parseInt(element.parentElement.parentElement.parentElement.parentElement.parentElement.id);
        reps = element.parentElement.children[0].value;
        weight = element.parentElement.children[1].value;   
        changeSessionStatEntry(setIndex,exercId,reps,weight,console.log)
    }
        
}