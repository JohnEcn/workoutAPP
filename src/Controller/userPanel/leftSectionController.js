function displayContent(statusCode,responseBody)
{
    let leftContainer = document.getElementById("leftSection");
    leftContainer.style.visibility="hidden";
    leftContainer.innerHTML = responseBody;   
    displayExerciseList(); 
}
function doneLoading()
{   
    let leftContainer = document.getElementById("leftSection");
    setTimeout(function()
    {
        leftContainer.style.visibility='initial';
        enableButton("addWorkoutBut");    
    }, 350);
}
