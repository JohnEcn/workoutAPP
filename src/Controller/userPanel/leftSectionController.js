function displayContent(statusCode,responseBody)
{
    let leftContainer = document.getElementById("leftSection");
    leftContainer.style.visibility="hidden";
    leftContainer.innerHTML = responseBody;   
    displayExerciseList(); 
}
function doneLoading()
{
    console.log("swiss");
    let leftContainer = document.getElementById("leftSection");
    leftContainer.style.visibility='initial';
    enableButton("addWorkoutBut");
}
