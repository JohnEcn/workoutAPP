<?php
class workoutLogDB
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

    function addSessionEntry($userID,$workoutID,$date)
    {
        $statement = $this->pdo->prepare("INSERT INTO `workoutSessionsLog` (`userID`, `workoutID`,`dateCompleted`) VALUES (?,?,?);SELECT @@IDENTITY");            
        $statement->bindParam(1,$userID,PDO::PARAM_INT);
        $statement->bindParam(2,$workoutID,PDO::PARAM_INT);
        $statement->bindParam(3,$date,PDO::PARAM_STR);
        $statement->execute();

        $statement = $this->pdo->prepare("SELECT @@IDENTITY");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_COLUMN);        
    }  
    
    function insertExercLog($sessionId,$exercName,$totalVolume,$oneRepMax)
    {
        $statement = $this->pdo->prepare("INSERT INTO `exerciseLog` (`sessionID`, `exerciseName`,`totalVolume`,`oneRepMax`) VALUES (?,?,?,?)");            
        $statement->bindParam(1,$sessionId,PDO::PARAM_INT);
        $statement->bindParam(2,$exercName,PDO::PARAM_STR);
        $statement->bindParam(3,$totalVolume,PDO::PARAM_INT);
        $statement->bindParam(4,$oneRepMax,PDO::PARAM_INT);
        $statement->execute();
    }
}
?>