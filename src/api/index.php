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
                        errorMessage("400");
                    break;  
                    
                }
            }
            elseif($explodedURL[1] == "workout")
            {

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