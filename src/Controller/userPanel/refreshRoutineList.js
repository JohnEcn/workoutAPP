function refreshRoutineList(statusCode,responseBody)
{
    if(statusCode == 200)
    {
        let routineListPath = './src/View/userPanel/routineList.php';
        let requestPage = new XMLHttpRequest;
        requestPage.open('GET',routineListPath)
        requestPage.setRequestHeader('Content-type','application/x-www-form-urlencoded');
        requestPage.send();
    
        requestPage.onreadystatechange = function()
        {
            if (this.readyState === XMLHttpRequest.DONE) 
            {             
                console.log(this.status);
                let responseBody = requestPage.responseText;
                document.getElementById("rcenterSection").innerHTML = responseBody;  
            }  
        }
    }
    
}
