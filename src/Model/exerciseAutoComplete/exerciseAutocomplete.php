<?php 

function findMatchingStrings($inputStr){

    $exercisesArray = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/userWorkouts/exerciseData.json"));

    //Find the length of the input string  
    //then compare the inputString with the first x letters of each String in the array
    $resultArray = [];    
    $strToMatch = trim(strtolower($inputStr));
    $inputStrLength = strlen($strToMatch);
    
    for($i = 0;$i < count($exercisesArray); $i++){
        $currentString = substr($exercisesArray[$i][0], 0, $inputStrLength);
        $currentString =trim(strtolower($currentString));

        if($currentString ===$strToMatch){
            array_push($resultArray,$exercisesArray[$i][0]);
        }
    }
    if(count($resultArray) === 0 && strlen($strToMatch) > 1){
        //If the resultArray is empty the function searches again for matches
        //But first deletes the last char from the input string to make easier to match
        //If the inputString is already one char then an empty array is returned
        $reducedInputStr = substr($inputStr, 0, -1);
        return findMatchingStrings($reducedInputStr);
    }
    else{
        return $resultArray ;
    }    
}
?>