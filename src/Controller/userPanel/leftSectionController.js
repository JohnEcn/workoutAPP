function displayContent(statusCode,responseBody)
{
    let leftContainer = document.getElementById("leftSection");
    leftContainer.innerHTML = responseBody;
    enableButton("addWorkoutBut");
}