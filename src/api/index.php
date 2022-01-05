<?php

    ini_set('session.use_cookies', '0');
    //error_reporting(0);

    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $requestURL = $_SERVER['REQUEST_URI'];    
    $httpBodyParameters = json_decode(file_get_contents('php://input'), true);
    $queryParameters = $_GET;
    $cookies = $_COOKIE;   

    //Separate path from query string
    if (strpos($requestURL, '?') != false) 
    {
        $requestURL = substr($requestURL, 0, strpos($requestURL, "?"));  
    }    
    $path = array_slice(explode ( "/" , $requestURL ), 4);          
    
    function ApiRequest($requestMethod,$path,$httpBodyParameters,$queryParameters,$cookies)
    {
        $userID = checkAuth($cookies);  //Check if the user us authorized
        $response = NULL;               //response[0] contains the httpCode and the response[1] contains the reply body and response[2] contains cookies              

        switch($path)
        {
            case $path[0] == "user" && $path[1] == "auth" && isset($path[2]) == false:                        
                $response =  $userID == NULL && $requestMethod == "DELETE"  ? 401 : user_auth($requestMethod,$httpBodyParameters,$userID);                
            break;
                    
            case $path[0] == "user" && $path[1] == "workouts" && isset($path[2]) == false:
                $response =  $userID == NULL ? 401 : user_workouts($requestMethod,$httpBodyParameters,$userID,$queryParameters);
            break; 
                    
            case $path[0] == "user" && $path[1] == "workouts" && $path[2] == "exercises":
                $response =  $userID == NULL ? 401 : user_workouts_exercises($requestMethod,$httpBodyParameters,$userID,$queryParameters);
            break;

            case $path[0] == "user" && $path[1] == "workouts" && $path[2] == "logs":
                $response =  $userID == NULL ? 401 : user_workouts_logs($requestMethod,$httpBodyParameters,$userID,$queryParameters);
            break;

            case $path[0] == "user" && $path[1] == "workouts" && $path[2] == "sessions" && isset($path[3]) == false:
                $response =  $userID == NULL ? 401 : user_workouts_sessions($requestMethod,$httpBodyParameters,$userID,$queryParameters);
            break;
            
            case $path[0] == "user" && $path[1] == "workouts" && $path[2] == "sessions" && $path[3] == "stats":
                $response =  $userID == NULL ? 401 : user_workouts_sessions_stats($requestMethod,$httpBodyParameters,$userID,$queryParameters);
            break; 

            case $path[0] == "autocomplete" && isset($path[1]) == false:
                $response = request_AutoComplete($requestMethod,$queryParameters);
            break; 
                    
            default:
                $response = 404;
            break;           
        } 
        
        return $response;
    }
    function user_auth($requestMethod,$httpBodyParameters,$userID)
    {   
        $response = NULL;  
        switch($requestMethod)
        {                  
            case "POST": 
                $response = userAuth($httpBodyParameters);
            break; 
                    
            case "PUT": 
                $response = userSignUp($httpBodyParameters);
            break;

            case "DELETE": 
                $response = endAuth($userID);
            break;  
                    
            default:
                $response = 405;
            break;           
        } 

        return $response;
    }
    function user_workouts($requestMethod,$httpBodyParameters,$userID,$queryParameters)
    {   
        require_once("endpoints/userWorkoutEndpoint.php");
        $workoutEndp = new userWorkoutEndpoint;
        $response = NULL;  
        switch($requestMethod)
        {   
            case "GET": 
                if(isset($queryParameters['wid']))
                {
                    $workoutEndp->retrieveWorkout($queryParameters['wid'],$userID);
                    $response = $workoutEndp->getResponse();
                }
                elseif(count($queryParameters) == 1)
                {
                    $response = getWorkoutList($userID);
                }
                else
                {
                    $response = 400;
                }              
            break; 

            case "POST": 
                $workoutEndp->insertWorkout($httpBodyParameters,$userID);
                $response = $workoutEndp->getResponse();
            break; 
                    
            case "PUT": 
                if(isset($queryParameters['newName']) && isset($queryParameters['wid']))
                {
                    $workoutEndp->changeWorkoutName($queryParameters['wid'],$userID,$queryParameters['newName']);
                    $response = $workoutEndp->getResponse();
                }
                else
                {
                    $response = 400;
                }                  
            break;

            case "DELETE":
                if(isset($queryParameters['wid']) && $queryParameters['wid'] != "")
                {
                    $workoutEndp->deleteWorkout($queryParameters['wid'],$userID);
                    $response = $workoutEndp->getResponse();
                }
                else
                {
                    $response = 400;
                }                               
            break;  
                    
            default:
                $response = 405;
            break;           
        } 

        return $response;
    }
    function user_workouts_exercises($requestMethod,$httpBodyParameters,$userID,$queryParameters)
    {   
        require_once("endpoints/userWorkoutEndpoint.php");
        $workoutEndp = new userWorkoutEndpoint;
        $response = NULL;  
        switch($requestMethod)
        {                      
            case "PUT": 
                if(isset($queryParameters['wid']) && $queryParameters['wid'] != "" )
                {
                    $workoutEndp->addExercise($queryParameters['wid'],$userID,$httpBodyParameters);
                    $response = $workoutEndp->getResponse();
                }
                else
                {
                    $response = 400;
                }                  
            break;

            case "DELETE":
                if(isset($queryParameters['wid']) && isset($queryParameters['exid']) && $queryParameters['wid'] != "" && $queryParameters['exid'] != "" )
                {
                    $workoutEndp->deleteExercise($queryParameters['wid'],$userID,$queryParameters['exid']);
                    $response = $workoutEndp->getResponse();
                }
                 else
                {
                    $response = 400;
                }    
            break;  
                    
            default:
                $response = 405;
            break;           
        } 
        return $response;
    }
    function user_workouts_logs($requestMethod,$httpBodyParameters,$userID,$queryParameters)
    {   
        $response = NULL;  
        switch($requestMethod)
        {                      
            case "GET": 
                if(isset($queryParameters['q']) && $queryParameters['q'] == "user/workouts/logs/exercises" && isset($queryParameters['exName']) && isset($queryParameters['info']) && $queryParameters['info'] == 'rp')
                {
                    $exercise = $queryParameters['exName'];
                    $response = getExerciseRP($userID,$exercise);
                }
                elseif(isset($queryParameters['q']) && $queryParameters['q'] == "user/workouts/logs/exercises" && isset($queryParameters['exName']))
                {
                    $exercise = $queryParameters['exName'];
                    $numberOfresults = isset($queryParameters['resultsNum']) && is_int((int)$queryParameters['resultsNum']) ? $queryParameters['resultsNum'] : 10;
                    $response = getExerciseLogsEntries($userID,$exercise,$numberOfresults);
                }
                elseif(isset($queryParameters['q']) && $queryParameters['q'] == "user/workouts/logs/exercises" )
                {
                    $entriesNum = isset($queryParameters['resultsNum']) && is_int((int)$queryParameters['resultsNum']) ? $queryParameters['resultsNum'] : 10;
                    $response = getExercisesLogsEntries($userID,$entriesNum);
                }
                elseif(isset($queryParameters['q']) && $queryParameters['q'] == "user/workouts/logs/routines" )
                {
                    $response = getRoutineLogsEntries($userID);
                }
                else
                {
                    $response = 400;
                }                  
            break;             
                    
            default:
                $response = 405;
            break;           
        } 

        return $response;
    }
    function user_workouts_sessions($requestMethod,$httpBodyParameters,$userID,$queryParameters)
    {   
        $response = NULL;  
        switch($requestMethod)
        {     
            case "GET":                 
                $response = getTrainingSession($userID);                         
            break;

            case "POST": 
                if(isset($queryParameters['wid']) && $queryParameters['wid'] != "" )
                {
                    $response = newTrainingSession($queryParameters['wid'],$userID);
                }
                else
                {
                    $response = 400;
                }                  
            break;

            case "PUT":
                if(isset($queryParameters['exid']) && $queryParameters['exid'] != "")
                {
                    $response = selectExercise($queryParameters['exid'],$userID);
                }
                elseif(isset($queryParameters['action']) && $queryParameters['action'] == "setComplete")
                {
                    $response = setComplete($userID);
                }
                elseif(isset($queryParameters['action']) && $queryParameters['action'] == "endWorkout")
                {
                    $response = endWorkoutSession($userID);
                }      
                else
                {
                    $response = 400;
                }    
            break;  
                    
            default:
                $response = 405;
            break;           
        } 

        return $response;
    }
    function user_workouts_sessions_stats($requestMethod,$httpBodyParameters,$userID,$queryParameters)
    {   
        $response = NULL;  
        switch($requestMethod)
        {     
            case "GET":                 
                $response = getWorkoutStats($userID);                      
            break;

            case "POST": 
                if(isset($queryParameters['exid']) && $queryParameters['exid'] != "" && isset($queryParameters['reps']) && $queryParameters['reps'] != "" && isset($queryParameters['wt']) && $queryParameters['wt'] != "" )
                {
                    $response = addExerciseStats($userID,$queryParameters['exid'],$queryParameters['reps'],$queryParameters['wt']);
                }
                else
                {
                    $response = 400;
                }                  
            break;

            case "PUT":
                if(isset($queryParameters['exid']) && $queryParameters['exid'] != "" && isset($queryParameters['reps']) && $queryParameters['reps'] != "" && isset($queryParameters['wt']) && $queryParameters['wt'] != "" && isset($queryParameters['exIndex']) && $queryParameters['exIndex'] != "" )
                {
                    $response = changeExerciseEntry($userID,$queryParameters['exid'],$queryParameters['exIndex'],$queryParameters['reps'],$queryParameters['wt']);
                }
                else
                {
                    $response = 400;
                }    
            break;
            
            case "DELETE":
                if(isset($queryParameters['exid']) && $queryParameters['exid'] != "")
                {
                    $response = deleteExerciseEntry($userID,$queryParameters['exid']);
                }
                else
                {
                    $response = deleteAllEntries($userID);
                }    
            break;
                    
            default:
                $response = 405;
            break;           
        } 

        return $response;
    }
    function request_AutoComplete($requestMethod,$queryParameters)
    {
        $response = NULL;  
        switch($requestMethod)
        {     
            case "GET":                 
                $response = autoComplete($queryParameters);                         
            break;

            default:
                $response = 405;
            break;           
        } 

        return $response;
    }
    function sendResponse($response)
    {
        if($response == 401 || $response == 405 || $response == 400)
        {
            http_response_code($response);
        }
        else
        {
            http_response_code($response['HttpCode']);

            if($response['cookie'] != NULL)
            {
                //setcookie("token", $response['cookie'], time()+(60*60*24*10),"/");
                setcookie("token", $response['cookie'],0,"/");
            }
            if($response['HttpBody'] != NULL)
            {
                echo json_encode($response['HttpBody']);
            } 
        }          
    }

    require_once("webAPI.php");
    if(isset($GLOBALS["internalRequest"]) == false)
    {
        $response = ApiRequest($requestMethod,$path,$httpBodyParameters,$queryParameters,$cookies);
        sendResponse($response);
    }
?>