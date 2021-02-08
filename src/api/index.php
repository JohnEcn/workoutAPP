<?php
    
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $requestURL = $_SERVER['REQUEST_URI'];   
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
            elseif($explodedURL[1] == "workout")
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
                        case "POST":echo "POST - $userID";
                                       
                        break;
                    
                        case "PUT":echo "PUT - $userID";
                        
                        break; 
                    
                        case "DELETE":echo "DELETE - $userID";
                        
                        break;
                        case "GET":echo "GET - $userID";
                        
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