function InsertRoutinePage()
{
    disableButton("addWorkoutBut");
    displayChartPageBut();
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
function displayInsertRoutBut()
{    
    let btn = document.getElementById("addWorkoutBut");
    let clone = btn.cloneNode(true);
    btn.parentNode.replaceChild(clone,btn);

    let newBtn = document.getElementById("addWorkoutBut");
    newBtn.innerText = "Add new routine";
    newBtn.style.backgroundColor = "#ffd3aa";
    newBtn.addEventListener("click",function (){
        InsertRoutinePage();
    });
    disableButton("addWorkoutBut");
}
