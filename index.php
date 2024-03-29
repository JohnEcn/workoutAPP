<?php

if(isset($_GET['demo']))
{
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/demoUserAcc/demoAccount.php");
    exit();
}


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
    <link rel="stylesheet" href="./src/View/landingPage/css/botHorizSection.css">
    <link rel="stylesheet" href="./src/View/landingPage/css/authContainer.css">
    <link rel="stylesheet" href="./src/View/loadingAnimations/loading.css">
    <link rel="stylesheet" href="./src/View/statusIndicator/statusIndicator.css">

</head>
<body>
    <div id="loadingAnimation">
        <img src="./src/View/loadingAnimations/Eclipse-0.5s-141px.gif" alt="Loading.."/>
    </div>
    <div id="statusIndicator">
        <img src="./src/View/statusIndicator/animation_500_kmccuizn.gif" alt=""/>
    </div>
    <landingPage id='landingPage'>
        <div id='navBar'>
            <div id='navLeft'><span id='appTitle'>WorkoutApp</span></div>
            <div id='navRight'>
                <ul id='navLinks'>
                    <li class='navlink'></li>
                    <li class='navlink'></li>
                    <li class='navlink'></li>
                    <li class='navlink'></li>
                </ul>                
            </div>            
        </div>
        <div id='topHorizSect'>
            <div id='topHorizSect_left'>
                <div id='mainTextContainer'>
                    <h1 id='mainTitle'><span>Organize</span> and <span>track</span> your workouts.</h1>
                     <h3 class='secondaryTitles'>&bull; Visualize your progress.</h3>
                     <h3 class='secondaryTitles'>&bull; Optimize your workouts  .</h3>
                     <h3 class='secondaryTitles'>&bull; Improve.</h3>
                     <input onclick="location.href='/workoutApp?demo';" type="submit" value="Try a demo" id="demoButton">
                </div>       
            </div>
            <div id='topHorizSect_right'>
                <div id='userAuthContainer'>
                    <input type='text' id='username' autocomplete='off' class='authInput' title ="migga" placeholder='Username' name='fname'><br><br>
                    <input type='password' id='password' autocomplete='off' class='authInput' 
                    placeholder='Password' name='lname'><br><br>
                    <input type='submit' value='Log in' id='loginButt' onclick='logInRequest();'>
                    <span id='signUpText' onclick='signUpForm();'>Sign up!</span>                     
                    <span id='errorMessage'></span>  
                </div>
            </div>
            </div>
        <div id='bottomHorizSect'> 
        <div class='tabs' id='tab1'>
        <h3 class='tabheader'><span class='verb'>Create</span> workout routines.</h3>   
            <div class='tabImgDiv'><img class=tabImg src="./src/View/landingPage/css/list_transparent.png" alt=""></div>     
        </div> 
         <div class='tabs' id='tab1'>
          <h3 class='tabheader'><span class='verb'>Perform</span> them and log your stats.</h3>    
           <div class='tabImgDiv'><img class=tabImg src="./src/View/landingPage/css/lift.png" alt=""></div>       
         </div>
          <div class='tabs' id='tab1'>
           <h3 class='tabheader'><span class='verb'>Track</span> your progress.</h3>     
           <div class='tabImgDiv'><img class=tabImg src="./src/View/landingPage/css/chart.png" alt=""></div>      
          </div>
           <div class='tabs' id='tab1'>
            <h3 class='tabheader'><span class='verb'>Optimize</span> your training.</h3>     
            <div class='tabImgDiv'><img class=tabImg src="./src/View/landingPage/css/success.png" alt=""></div>      
           </div>        
        </div>    
    </landingPage>  
    <script src="./src/Controller/landingPage/userAuthForms.js"></script>  
    <script src="./src/Controller/landingPage/userAuthHandler.js"></script> 
    <script src="./src/Controller/clientEndPoint/endPoint.js"></script>  
    <script src="./src/Controller/loadingIndicator/loadingAnimate.js"></script>
    <script src="./src/Controller/statusIndicator/statusIndicator.js"></script>  
</body>
</html>