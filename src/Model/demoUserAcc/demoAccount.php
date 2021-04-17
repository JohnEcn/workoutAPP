<?php
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
        $status = 400;
        $userParameters = []; 
        
        while($status != 201)
        {      
            $userParameters['user'] = "Demo_".bin2hex(openssl_random_pseudo_bytes(5));
            $userParameters['pass'] = bin2hex(openssl_random_pseudo_bytes(5));
            $userParameters['passconf'] = $userParameters['pass'];
            $userParameters['email'] = bin2hex(openssl_random_pseudo_bytes(3))."@demo.dm";
            
            $_GET = [];
            $GLOBALS["internalRequest"] = true;   
            $_GET['q'] ='user/auth';    
            $URL  = ['user','auth'];
            require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/api/index.php");    
            $response = ApiRequest("PUT",$URL,$userParameters,$_GET,$_COOKIE);
            $status = $response['HttpCode'];
        }

        $_GET = [];
        $GLOBALS["internalRequest"] = true;   
        $_GET['q'] ='user/auth';    
        $URL  = ['user','auth'];
        require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/api/index.php");    
        $response = ApiRequest("POST",$URL,$userParameters,$_GET,$_COOKIE);   
        $token = $response['cookie'];
        return $token;      
    }
    private function getUserID()
    {
        require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userAuth/checkAuth.php");
        return getUserID($this->token);
    }
    private function insertDemoWorkouts()
    {
        $_GET = [];
        $GLOBALS["internalRequest"] = true;   
        $_GET['q'] ='user/workouts'; 
        $URL  = ['user','workouts'];    
        require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/api/index.php");   

        $response = ApiRequest("POST",$URL,json_decode($this->demoRoutines[0], true),$_GET,$_COOKIE);
        $response = ApiRequest("POST",$URL,json_decode($this->demoRoutines[1], true),$_GET,$_COOKIE);
        $response = ApiRequest("POST",$URL,json_decode($this->demoRoutines[2], true),$_GET,$_COOKIE);
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

            require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userTrainingSession/trainingSessionDB.php");
            $sessionDB = new trainingSessionDB;
            $sessionDB->saveSession($userID,$workoutID,1,1,$exerciseList,$sessionStats);

            require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userTrainingSession/trainSessionHandler.php");

            workoutComplete($this->userID);
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