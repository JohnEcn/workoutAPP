function logInRequest()
{
    let username = document.getElementById("username").value.trim();
    let password = document.getElementById("password").value.trim();
    
    authenticateUser(username,password,logInResponseHandle);
}
function logInResponseHandle(statusCode,httpBody)
{
    if(statusCode == 200)
    {
        indicateOK();
        let domain = window.location.hostname;
        window.location.href = "/workoutApp/userPanel.php";
    }
    else
    {
        let errorMessageContainer = document.getElementById("errorMessage");
        let error = JSON.parse(httpBody)['message'];
        errorMessageContainer.innerText = error;
    }
}
function signUpRequest()
{
    let username = document.getElementById("username").value.trim();
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value.trim();
    let passwordConf = document.getElementById("passwordConf").value.trim();
    
    createUser(username,email,password,passwordConf,signUpResponseHandle);
}
function signUpResponseHandle(statusCode,httpBody)
{
    if(statusCode == 201)
    {
        indicateOK();
        logInForm();

        let errorMessageContainer = document.getElementById("errorMessage");
        errorMessageContainer.innerText = "Registration Succesfull!";
        errorMessageContainer.style.color = "green";
    }
    else
    {
        let errorMessageContainer = document.getElementById("errorMessage");
        let error = JSON.parse(httpBody)['message'][0];
        errorMessageContainer.innerText = error;
    }
}