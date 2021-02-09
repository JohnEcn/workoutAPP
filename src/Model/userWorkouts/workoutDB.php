<?php

class workoutDB
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

    public function insertWorkout($userID,$workoutName)
    {
        //Inserts into userroutine table the userID and a routineID is auto generated     
        $statement = $this->pdo->prepare("INSERT INTO `userroutine` ( `userID`) VALUES (?)");
        $statement->bindParam(1,$userID,PDO::PARAM_INT);
        $statement->execute();

        //Selects the routine IDs that point to the current userID and picks the biggest num
        $statement = $this->pdo->prepare("SELECT MAX(routineID) FROM `userroutine` WHERE userID = ?");
        $statement->bindParam(1,$userID,PDO::PARAM_INT);
        $statement->execute();
        $routineID = $statement->fetchAll(PDO::FETCH_COLUMN)[0];

        //Inserts into routine table the routineID , routineName 
        $statement = $this->pdo->prepare("INSERT INTO `routine` (`routineID`, `routineName`) VALUES (?, ?)");
        $statement->bindParam(1,$routineID,PDO::PARAM_INT);
        $statement->bindParam(2,$workoutName,PDO::PARAM_STR);
        $statement->execute(); 

        return $routineID;
    }
    public function insertExercise($routineID,$exercise)
    {   
        //Inserts the routineID into the routineExercise table to create An exerciseID
        $statement = $this->pdo->prepare("INSERT INTO `routineexercise` (`routineID`) VALUES (? )");
        $statement->bindParam(1,$routineID,PDO::PARAM_INT);
        $statement->execute();

        //Selects the recently created exerciseID that point to the current routineID
        $statement = $this->pdo->prepare("SELECT MAX(exerciseID) FROM `routineexercise` WHERE routineID = ?");
        $statement->bindParam(1,$routineID,PDO::PARAM_INT);
        $statement->execute();
        $exerciseID = $statement->fetchAll(PDO::FETCH_COLUMN)[0];

        //Inserting the exercise into the exercise table with the recently created exerciseID
        $exerciseName = $exercise['name'];
        $exerciseSets = $exercise['sets'];
        $exerciseRest = $exercise['rest'];
        $exerciseIndex = $exercise['index'];

        $statement = $this->pdo->prepare("INSERT INTO `exercise` (`exerciseID`, `exerciseIndex`,`exerciseName`, `exerciseSets`, `exerciseRest`) VALUES (?,?,?,?,?)");
        $statement->bindParam(1,$exerciseID,PDO::PARAM_INT);
        $statement->bindParam(2,$exerciseIndex,PDO::PARAM_INT);
        $statement->bindParam(3,$exerciseName,PDO::PARAM_STR);
        $statement->bindParam(4,$exerciseSets,PDO::PARAM_INT);
        $statement->bindParam(5,$exerciseRest,PDO::PARAM_INT);
        $statement->execute();
    }
    

}








?>