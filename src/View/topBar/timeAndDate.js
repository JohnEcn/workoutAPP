//Displays current time and date top left corner of userPanel Page

function currentDate(){
    let currentDate = new Date();
    let date = currentDate.toString().slice(0, 15);
    let hour = currentDate.toString().slice(16, 24);
    let dateSrt = date + "    |    "+ hour;
    
    let dateContainer = document.getElementById("leftTopBar");
    dateContainer.innerHTML  = "<PRE id='timeAndDate'>"+dateSrt+"</PRE>";
}

setInterval(() => {
    currentDate();
    
}, 1000);

