function changeActivePage(side)
{
    let rightSection = document.getElementById("rightSection");
    let leftSection = document.getElementById("leftSection");
    let backBtn = document.getElementById("backBtnDiv");
    let singlePage = window.innerWidth < 799 ? true : false;

    if(side == "left" && singlePage)
    {
        leftSection.style.display = "initial";
        rightSection.style.display = "none";
        backBtn.style.visibility = "initial";
    }
    else if(side == "right" && singlePage)
    {
        rightSection.style.display = "flex";
        leftSection.style.display = "none";
        backBtn.style.visibility = "hidden";
    }
}