<?php
    
    ini_set('session.use_cookies', '0');
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $requestURL = $_SERVER['REQUEST_URI'];    
    if (strpos($requestURL, '?') !== false) 
    {
        $requestURL = substr($requestURL, 0, strpos($requestURL, "?"));  
    }
    $httpBody = file_get_contents('php://input');
    $cookies = $_COOKIE;
    $explodedURL = array_slice(explode ( "/" , $requestURL ), 4);    
    $httpBodyParameters =  json_decode($httpBody, true);      
    
    function ApiRequest($requestMethod,$explodedURL,$httpBodyParameters,$cookies)
    {   
        $response = [];
        if($explodedURL[0] == "user")
        {
            if($explodedURL[1] == "auth")
            {
                switch($requestMethod)
                {
                    case "POST":
                        $response["response"] = userAuth($httpBodyParameters);
                        if(array_key_exists("token", $response["response"]))
                        {
                            setcookie("token", $response["response"]["token"],time()+3600);
                            unset($response["response"]["token"]);
                        }                        
                    break;
                    
                    case "PUT":
                        $response["response"] = userSignUp($httpBodyParameters);
                    break; 
                    
                    case "DELETE":
                        $response["response"] = endAuth($cookies);
                        setcookie("token", "deleted",time()-3600);
                    break;
                    
                    default:
                        //echo userAuth documentation
                    break;                      
                }
            }
            elseif($explodedURL[1] == "workouts")
            {
                $userID = checkAuth($cookies);

                if($userID == NULL)
                {   
                    $response["status"] = "401 (UNATHORIZED)";
                    $response["message"] = "NOT AUTHORIZED TO ACCESS WORKOUTS";
                }
                else
                {
                     switch($requestMethod)
                    {
                        case "POST":$response["response"] = saveWorkout($httpBodyParameters,$userID);
                                       
                        break;
                    
                        case "PUT":
                            if(isset($_GET['wid']) && $_GET['wid'] !="" && isset($_GET['newName']) && $_GET['newName'] !="")
                            {
                                $workoutID = $_GET['wid'];
                                $newName =  $_GET['newName'];
                                $response["response"] = alterWorkoutName($workoutID,$userID,$newName); 
                            }
                            elseif(isset($_GET['wid']) && $_GET['wid'] !="" && $explodedURL[2] == "exercises")
                                {   $workoutID = $_GET['wid'];
                                    $response["response"] = addNewExercise($workoutID,$userID,$httpBodyParameters);
                            }    
                            else
                            {
                                $response["status"] = "400 (BAD REQUEST)";
                                $response["message"] = "REQUIRED FIELDS MISSING";
                            }              
                        break; 
                    
                        case "DELETE":
                            if(isset($_GET['wid']) && $_GET['wid'] !="" && isset($_GET['exid']) && $_GET['exid'] !="" && $explodedURL[2] == "exercises")
                            {
                                $workoutID = $_GET['wid'];
                                $exerciseID =  $_GET['exid'];
                                $response["response"] = removeExercise($workoutID,$userID,$exerciseID);
                            }
                            else
                            {
                                $response["response"]["status"] = "400 (BAD REQUEST)";
                                $response["response"]["message"] = "REQUIRED FIELDS MISSING";
                            }   
                        
                        break;
                        case "GET":
                            if(isset($_GET['wid']) && $_GET['wid'] !="")
                            {
                                $workoutID = $_GET['wid']; 
                                $response["response"] = getWorkout($workoutID,$userID);   
                            }
                            elseif(count($_GET)==1)
                            {
                                $response["response"] = getWorkoutList($userID);   
                            }
                            else
                            {
                                $response["status"] = "400 (BAD REQUEST)";
                                $response["message"] = "REQUIRED FIELDS MISSING";
                            }                   
                        break;
                    
                        default: echo "DEFAULT";
                        
                        break;                      
                    }
                }
            }
            elseif($explodedURL[1] == "trainSession")
            {

            }
        }
        elseif($explodedURL[0] == "whatever")
        {

        }
        return $response;
    }
    require_once("webAPI.php");
    echo json_encode(ApiRequest($requestMethod,$explodedURL,$httpBodyParameters,$cookies));
   

?>