<?php



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./src/View/landingPage/css/landingStructure.css">
    <link rel="stylesheet" href="./src/View/landingPage/css/navBar.css">
    <link rel="stylesheet" href="./src/View/landingPage/css/topHorizSection.css">
    <link rel="stylesheet" href="./src/View/landingPage/css/authContainer.css">

</head>
<body>
    <landingPage id='landingPage'>
        <div id='navBar'>
            <div id='navLeft'><span id='appTitle'>WorkoutApp    </span></div>
            <div id='navRight'>
                <ul id='navLinks'>
                    <li class='navlink'>Whatever</li>
                    <li class='navlink'>Whatever</li>
                    <li class='navlink'>Whatever</li>
                    <li class='navlink'>Whatever</li>
                </ul>                
            </div>            
        </div>
        <div id='topHorizSect'>
            <div id='topHorizSect_left'>
                <div id='mainTextContainer'>
                    <h1 id='mainTitle'><span>Organize</span> and <span>track</span> your workouts.</h1>
                     <h3 class='secondaryTitles'>&bull; Visualize your progress.</h3>
                     <h3 class='secondaryTitles'>&bull; Compare yourself to others.</h3>
                     <h3 class='secondaryTitles'>&bull; Improve.</h3>
                     <input type="submit" value="Try a demo" id="demoButton">
                </div>       
            </div>
            <div id='topHorizSect_right'>
                <div id='userAuthContainer'>
                    <input type='text' id='username' autocomplete='off' class='authInput' title ="migga" placeholder='Username' name='fname'><br><br>
                    <input type='password' id='password' autocomplete='off' class='authInput' 
                    placeholder='Password' name='lname'><br><br>
                    <input type='submit' value='Log in' id='loginButt'>
                    <span id='signUpText' onclick='signUpForm();'>Sign up!</span>                     
                    <span id='errorMessage'></span>  
                </div>
            </div>
            </div>
        <div id='bottomHorizSect'>  </div>    
    </landingPage>  
    <script src="./src/Controller/landingPage/userAuthForms.js"></script>  
</body>
</html>