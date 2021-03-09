function InsertRoutinePage()
{
    disableButton("addWorkoutBut")
    requestPage("workoutInsert",displayContent);
}
function disableButton(id)
{
    var button = document.getElementById(id);
    button.style.pointerEvents = "none";
}
function enableButton(id)
{
    var button = document.getElementById(id);
    button.style.pointerEvents = "initial";
}
