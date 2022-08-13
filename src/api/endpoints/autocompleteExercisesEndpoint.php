<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/exerciseAutoComplete/exerciseAutoComplete.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/api/endpoints/Endpoint.php");

Class autocompleteExercisesEndpoint extends Endpoint
{
    function autoComplete($queryParameters)
    {
        try
        {
            if(isset($queryParameters['str']) != true || $queryParameters['str'] == "")
            {
                throw new Exception();
            }

            $results = findMatchingStrings($queryParameters['str']); 
            if($results == null )
            {
                parent::setResponse(204,NULL,NULL);
            } 
            else
            {
                parent::setResponse(200,$results,NULL);
            }
        }
        catch(Exception $e)
        {
            parent::setResponse(400,NULL,NULL);            
        }
    }
}
?>