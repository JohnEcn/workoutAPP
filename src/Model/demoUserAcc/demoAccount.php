<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/api/endpoints/userAuthEndpoint.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/api/endpoints/userWorkoutEndpoint.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/api/endpoints/trainingSessionsEndpoint.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userTrainingSession/trainingSessionDB.php");
class demoAccount 
{
    private $token = null;
    private $userID = null;
    private $demoRoutines = ['{"workout":{"name":"Push workout","exerciseList":[{"name":"Bench Press","sets":5,"rest":90},{"name":"Military Press","sets":4,"rest":90},{"name":"Decline Bench Press","sets":5,"rest":90},{"name":"Shoulder Press","sets":4,"rest":90},{"name":"Cable Triceps Extensions","sets":5,"rest":60}]}}','{"workout":{"name":"Pull workout","exerciseList":[{"name":"Lat Pull Down","sets":5,"rest":90},{"name":"Seated Cable rows","sets":5,"rest":90},{"name":"Facepulls","sets":4,"rest":60},{"name":"BB Shrugs","sets":4,"rest":75},{"name":"DB Biceps Curls","sets":4,"rest":60}]}}','{"workout":{"name":"Legs workout","exerciseList":[{"name":"Squats","sets":5,"rest":120},{"name":"Leg Press","sets":4,"rest":90},{"name":"Leg Extension","sets":4,"rest":60},{"name":"Leg Curl","sets":4,"rest":60}]}}'];

    public function __construct()
    {
        $this->token = $this->newDemoUser();
        $_COOKIE['token'] = $this->token;   
        $this->userID = $this->getUserID();
        $this->insertDemoWorkouts(); 
        $this->insertDemoLogs();    
        $this->sendCookieAndRedirect();
    } 
    private function newDemoUser()
    {    
        $token = null;
        $authStatus = 400;
        $userParameters = []; 
        $userAuth = new userAuthEndpoint;
        
        while($authStatus != 201)
        {      
            $userParameters['user'] = "Demo_".bin2hex(openssl_random_pseudo_bytes(5));
            $userParameters['pass'] = bin2hex(openssl_random_pseudo_bytes(5));
            $userParameters['passconf'] = $userParameters['pass'];
            $userParameters['email'] = bin2hex(openssl_random_pseudo_bytes(3))."@demo.dm";
            
            $userAuth->userSignUp($userParameters);
            $response =  $userAuth->getResponse();
            $authStatus = $response['HttpCode'];
        }

        $userAuth->userAuth($userParameters);
        $response =  $userAuth->getResponse();
        return $response['cookie'];
    }
    private function getUserID()
    {
        $userAuth = new userAuthEndpoint;
        return $userAuth->identifyUser($this->token);
    }
    private function insertDemoWorkouts()
    {
        $_GET = [];
        $GLOBALS["internalRequest"] = true;   
        $_GET['q'] ='user/workouts'; 
        $URL  = ['user','workouts'];    
        require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/api/index.php");   

        $workoutsEndpoint = new userWorkoutEndpoint;      
        
        $workoutsEndpoint->insertWorkout(json_decode($this->demoRoutines[0],true),$this->userID);
        $workoutsEndpoint->insertWorkout(json_decode($this->demoRoutines[1],true),$this->userID);
        $workoutsEndpoint->insertWorkout(json_decode($this->demoRoutines[2],true),$this->userID);
    }
    private function insertDemoLogs()
    {
        require_once("logs.php");
        
        for($i = 0;$i<count($logs);$i++)
        {
            $userID = (int)$this->userID;
            $workoutID = 12;
            $exerciseList = $logs[0]['exerciseList'];
            $sessionStats = $logs[$i]['sessionStats'];

            $sessionDB = new trainingSessionDB($userID); 
            $sessionDB->saveSession($userID,$workoutID,1,1,$exerciseList,$sessionStats);

            $session = new trainingSessionsEndpoint;
            $session->workoutComplete($this->userID);
        }
    }
    private function sendCookieAndRedirect()
    {
        setcookie("token", $this->token,0,"/");
        header("Location:/workoutApp/userPanel.php");
    }
}
$dm = new demoAccount();
?>