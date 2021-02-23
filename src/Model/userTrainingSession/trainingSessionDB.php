<?php

class trainingSessionDB
{
    public $pdo = null;
    public function __construct()
    {
        $DBusername = "root";
        $DBpassword = "";
        $DBserver = "mysql:server=localhost;dbname=workoutapi";

        $this->pdo = new PDO($DBserver,$DBusername,$DBpassword);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
    }

    function saveSession($userID,$workoutID,$currentExercise,$setsRemaining,$exerciseList)
    {
        $statement = $this->pdo->prepare("INSERT INTO `trainingsession` ( `userID`,`workoutID`,`currentExerciseID`,`setsremaining`,`exerciseList`) VALUES (?,?,?,?,?)");
        $statement->bindParam(1,$userID,PDO::PARAM_INT);
        $statement->bindParam(2,$workoutID,PDO::PARAM_INT);
        $statement->bindParam(3,$currentExercise,PDO::PARAM_INT);
        $statement->bindParam(4,$setsRemaining,PDO::PARAM_INT);
        $statement->bindParam(5,$exerciseList,PDO::PARAM_STR);
        $statement->execute();        
    }
    function loadSession($userID)
    {
        $statement = $this->pdo->prepare("SELECT * FROM trainingsession WHERE userID = ? ");
        $statement->bindParam(1,$userID,PDO::PARAM_INT);        
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
        
    }
    function deleteSession($userID)
    {
        $statement = $this->pdo->prepare("DELETE FROM trainingsession WHERE userID = ?");
        $statement->bindParam(1,$userID,PDO::PARAM_INT);
        $statement->execute(); 
    }

}


?>