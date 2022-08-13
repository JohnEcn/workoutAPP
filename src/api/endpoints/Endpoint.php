<?php
Class Endpoint
{
    private $response = ["HttpCode"=>"","HttpBody"=>"","cookie"=>""];

    protected function setResponse($code,$body,$cookie)
    {
        $this->response["HttpCode"] = $code;
        $this->response["HttpBody"]  = $body;  
        $this->response["cookie"] = $cookie; 
    }
    public function getResponse()
    {
        return $this->response;        
    }
}
?>