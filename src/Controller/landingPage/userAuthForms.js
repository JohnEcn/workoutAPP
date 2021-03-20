function signUpForm()
{
    let container = document.getElementById("userAuthContainer");
    
    container.style.height = "85%";
    container.style.width = "60%";
    container.style.transition = "all linear 150ms";
    container.innerHTML = "";
    
    let signUpForm = "<input type='text' id='username' autocomplete='off' class='authInput' placeholder='Username' name='fname'><br>"+
    "<input type='text' id='email' autocomplete='off' class='authInput'placeholder='Email' name='lname'><br>"+
    "<input type='password' id='password' autocomplete='off' class='authInput' placeholder='Password' name='fname'><br>"+
    "<input type='password' id='passwordConf' autocomplete='off' class='authInput'placeholder='Confirm Password' name='lname'><br>"+
    "<input type='submit' value='Sign Up' id='signUpButt' onclick='signUpRequest();'><span id='signUpText' onclick='logInForm();'>Already a user ?</span><span id='errorMessage'></span> ";
    setTimeout(() => {
        container.innerHTML = signUpForm;        
    }, 150);  
}
function logInForm()
{
    let container = document.getElementById("userAuthContainer");
    
    container.style.height = "60%";
    container.style.width = "50%";
    container.style.transition = "all linear 150ms";
    container.innerHTML = "";
    
    let logInForm = "<input type='text' id='username' autocomplete='off' class='authInput' placeholder='Username' name='username'><br><br><input type='password' id='password' autocomplete='off' class='authInput' placeholder='Password' name='password'><br><input type='submit' value='Log in' id='loginButt' onclick='logInRequest();'><span id='signUpText' onclick='signUpForm();'>Sign up!</span><span id='errorMessage'></span> ";
    setTimeout(() => {
        container.innerHTML = logInForm;      
    }, 150);  

}