function updateTimerValue()
{          
    let secondsStr = document.getElementsByClassName("activeRowContainer")[0].children[0].children[0].children[2].innerText;
    let ms = parseInt(secondsStr)*1000;
    let timerContainer = document.getElementById("secondsSpan");

    
    let minutes = Math.floor(ms / 60000);
    let seconds = ((ms % 60000) / 1000).toFixed(0);
    let result = (minutes < 10 ? '0' : '') + minutes + ":" + (seconds < 10 ? '0' : '') + seconds
    timerContainer.innerText = result;  
   
}
function startTimer()
{
    document.getElementById("secondsSpan").setAttribute("stopSignal","off")
    let startBut = document.getElementById("setCompleteBut");
    startBut.style.pointerEvents = "none";
    const interv = setInterval(() =>
    {
        let timerValue = document.getElementById("secondsSpan").innerText.split(":");
        let timerContainer = document.getElementById("secondsSpan");
        let stopSignal = timerContainer.getAttribute("stopSignal");

        if(stopSignal == "On")
        {
            clearInterval(interv);
            updateTimerValue();
            startBut.style.pointerEvents = "initial";
            return;
        }
        let mins = parseInt(timerValue[0]);
        let secs = parseInt(timerValue[1]);

        let ms = mins*60000 + secs*1000;
    
        ms -=1000;
        

        if(ms < 500)
        {
            setTimeout(() => {
               updateTimerValue(); 
            }, 1500);
            startBut.style.pointerEvents = "initial";
            clearInterval(interv);
            
            
        }
        let minutes = Math.floor(ms / 60000);
        let seconds = ((ms % 60000) / 1000).toFixed(0);
        let result = (minutes < 10 ? '0' : '') + minutes + ":" + (seconds < 10 ? '0' : '') + seconds
        timerContainer.innerText = result;  
                
    }, 1000);    
}
function modifyTimer(sec)
{
    let timerValue = document.getElementById("secondsSpan").innerText.split(":");
    let timerContainer = document.getElementById("secondsSpan");
    
    let min = parseInt(timerValue[0]);
    let secs = parseInt(timerValue[1]);
    let ms = min*60000 + secs*1000;
    let value = parseInt(sec);

    ms+=value*1000;

    if(ms < 500)
    {
        ms = 0;
    }
    let minutes = Math.floor(ms / 60000);
    let seconds = ((ms % 60000) / 1000).toFixed(0);
    let result = (minutes < 10 ? '0' : '') + minutes + ":" + (seconds < 10 ? '0' : '') + seconds
    timerContainer.innerText = result; 
    console.log(result);     
}