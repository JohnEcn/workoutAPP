<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userAuth/User.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/api/endpoints/Endpoint.php");
Class userAuthEndpoint extends Endpoint
{
    
    public function userAuth($parameters)
    {       
        if($parameters != null && array_key_exists ( "user" , $parameters ) && array_key_exists ( "pass" , $parameters ))
        {       
            try
            {
                $user = new User();
                $user->loginUser($parameters["user"],$parameters["pass"]);
                $token = $user->getToken();

                parent::setResponse(200,NULL,$token);
            }
            catch(Exception $e)
            {  
                parent::setResponse(401,["message"=>$e->getMessage()],NULL);               
            }
        }
        else
        {
            parent::setResponse(400,NULL,NULL);
        }
    }
    public function userSignUp($parameters)
    {  

        if($parameters !=null && array_key_exists ( "user" , $parameters ) && array_key_exists ( "pass" , $parameters ) && array_key_exists ( "email" , $parameters ) && array_key_exists ( "passconf" , $parameters ))
        {       
            try
            {
                $user = new User();
                $user->newUser($parameters["user"],$parameters["email"],$parameters["pass"],$parameters["passconf"]);

                parent::setResponse(201,NULL,NULL);
            }
            catch(Exception $e)
            {
                parent::setResponse(409,["message"=>$e->getMessage()],NULL);
            }
        }
        else
        {
            parent::setResponse(400,NULL,NULL);   
        }   
    }
    public function endAuth($token)
    {
        try
        {
            $user = new User();
            $user->authenticateUser($token);
            $user->logoutUser();

            parent::setResponse(200,NULL,"deleted");
        }
        catch(Exception $e)
        {
            parent::setResponse(401,["message"=>$e->getMessage()],NULL);
        }
    }
    public function identifyUser($token)
    {   
        try
        {
            $user = new User();
            $user->authenticateUser($token);
            return $user->getUserId();
        }
        catch(Exception $e)
        {
            return NULL;
        }
    }
}
?>