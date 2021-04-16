function requestStatsPage(offset)
{      
    if(offset != undefined) 
    {
        url = "/workoutAPP/src/View/chartsPage/charts.php?offset="+offset;  
        requestPage(url,updateCharts);  
    }
    else
    {
        url = "/workoutAPP/src/View/chartsPage/charts.php";    
        requestPage(url,displayCharts);
    }    
}
function displayCharts(httpCode,httpBody)
{
    if(httpCode == 200)
    {  
        updateCharts(httpCode,httpBody)
        displayInsertRoutBut();
        enableButton("addWorkoutBut");
    }
}
function updateCharts(httpCode,httpBody)
{
    let leftSection = document.getElementById("leftSection");
    leftSection.innerHTML = httpBody;

    let scripts = document.getElementsByClassName("chartScript") ;

    for(let i = 0; i<scripts.length;i++)
    {
        eval(scripts[i].innerText);    
    }
    changeActivePage("left");
}
function displayChartPageBut()
{
    if(window.innerWidth <= 800){return 1}
    let btn = document.getElementById("addWorkoutBut");
    let clone = btn.cloneNode(true);
    btn.parentNode.replaceChild(clone,btn);

    let newBtn = document.getElementById("addWorkoutBut");
    newBtn.innerText = "Progress Charts";
    newBtn.style.backgroundColor = "#fbff00";
    newBtn.addEventListener("click",function(){
        requestStatsPage();
    });
    disableButton("addWorkoutBut");
}