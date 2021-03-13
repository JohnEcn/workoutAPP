function requestSuggestions()
{
    let inputStr = document.getElementById("exerciseNameInp").value;
    buttonState("disabled");
    if(inputStr != "" && inputStr.length <= 12)
    {
        autocompleteEx(inputStr,displaySuggestions);
        ;
    }    
}
function displaySuggestions(statusCode,responseBody)
{
    if(statusCode == 200)
    {   let autocompleteContainer = document.getElementById("autocompleteContainer");
        let suggestionArray = JSON.parse(responseBody);
        let suggestionDiv = "";

        for(let i = 0; i < suggestionArray.length; i++)
        {
            suggestionDiv += "<div class=\"suggestionDiv\"onclick=\"pickSuggestion('"+ suggestionArray[i] +"')\">"+suggestionArray[i]+"</div>";
        }     
        autocompleteContainer.innerHTML = suggestionDiv;      
    } 
}
function pickSuggestion(suggestion)
{
    let exerciseInputField = document.getElementById("exerciseNameInp");
    let autocompleteContainer = document.getElementById("autocompleteContainer");
    exerciseInputField.value = suggestion;
    autocompleteContainer.innerHTML = "";  
    buttonState("enabled");
}
function buttonState(status){
    let addButton = document.getElementById('addExerciseBtn');
    
    if(status === "enabled"){
        addButton.style.pointerEvents = "initial";  
    }
    else if(status === "disabled"){
        addButton.style.pointerEvents = "none";  
    } 
}