function requestPage(file,section){
    //The section parameter means where the requested page will be displayed
    //This parameter is passed to the displayPage();    
    let requestPage = new XMLHttpRequest;
    requestPage.open('GET',file)
    requestPage.setRequestHeader('Content-type','application/x-www-form-urlencoded');
    requestPage.send();
    
    requestPage.onreadystatechange = function()
    {
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) 
        {               
            let requestedPage = requestPage.response;
            displayPage(requestedPage,section);
            changeButton(file);
            //alterPageVisibilityInMobile(section);                                     
        }  
    }
}
function requestNewRoutinePage()
{   ajaxLoading();
    requestPage('./workoutinsert.php',"left");  
}
function requestStatsPage()
{   ajaxLoading();
    requestPage('./UserStats.php',"left"); 
    refreshExerciseList();    
   
}
function refreshExerciseList(){
    requestPage('./src/View/userPanel/routineList.php',"right");
}
function displayPage(page,section){
    //displays the page at the specified section
    if(section ==="left")
    {
        document.getElementById("leftSection").innerHTML = page;  
    }
    else if(section === "right"){
        document.getElementById("rcenterSection").innerHTML = page;  
    }
}
function changeButton(page){
    //Changes the button appearance depending on the page loaded with ajax
    var button = document.getElementById("addWorkoutBut");
    var leftSection = document.getElementById("leftSection");
    
    if(page === "./workoutinsert.php")
    {
        leftSection.style.backgroundColor = "#eeeded";
        button.classList.add("changed");
        button.innerText = "User stats";
        button.onclick = "";
        button.addEventListener("click",function(){return requestStatsPage();})
    }
    else if(page === "./UserStats.php")
    {
        leftSection.style.backgroundColor = "#d7e2ff";
        button.classList.remove("changed");
        button.innerText = "Add new routine";
        button.onclick = "";
        button.addEventListener("click",function(){return requestNewRoutinePage();})
    }

}
function ajaxLoading()
{//Hides the content until is loaded fully and makes the button unclickable to prevert another ajax request while content is loading
    document.getElementById("addWorkoutBut").onclick = "";
    document.getElementById("leftSection").style.visibility="hidden";
    document.getElementById("addWorkoutBut").style.pointerEvents = "none";
}
function fullyLoaded()
//Once the Ajax content is fully loaded this function is called to make it visible 
//The button that switches between the 2 ajax pages becomes again clickable
{
    setTimeout(() => {
        document.getElementById("leftSection").style.visibility="initial";
        document.getElementById("addWorkoutBut").style.pointerEvents = "initial";
    }, 500);
    
}