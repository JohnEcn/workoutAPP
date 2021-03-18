function indicateOK()
{
    let checkmark = document.getElementById("statusIndicator");
    checkmark.style.visibility = "visible";

    setTimeout(function(){checkmark.style.visibility = "hidden";},1000)
}
function indicateError()
{
    let checkmark = document.getElementById("errorIndicator");
    checkmark.style.visibility = "visible";

    setTimeout(function(){checkmark.style.visibility = "hidden";},4000)
}