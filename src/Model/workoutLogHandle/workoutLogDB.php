<?php
class workoutLogDB
{
    public $pdo = null;
    public function __construct()
    {
        require $_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/databaseConnection/dbConnect.php"; 
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
    function retrieveSessionLogs($userID)
    {
        $statement = $this->pdo->prepare("SELECT * FROM workoutSessionsLog WHERE userID = ? ");            
        $statement->bindParam(1,$userID,PDO::PARAM_INT);        
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }
    function retrieveExerciseLogs($exercise,$userID)
    {
        $userSessions = $this->retrieveSessionLogs($userID);
        if(count($userSessions) == 0)
        {
            return [];
        }
        $userSessionsStr = implode(',',$userSessions);
        $statement = $this->pdo->prepare("SELECT * FROM exerciseLog WHERE sessionID IN (".$userSessionsStr.") AND exerciseName = ?");            
        $statement->bindParam(1,$exercise,PDO::PARAM_STR);    
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }  
    function retrieveExercisesCount($userID)
    {
        $userSessions = $this->retrieveSessionLogs($userID);
        if(count($userSessions) == 0)
        {
            return [];
        }
        $userSessionsStr = implode(',',$userSessions);
        $statement = $this->pdo->prepare("SELECT exerciseName , count(exerciseName) AS count FROM exerciseLog WHERE sessionID IN (".$userSessionsStr.") GROUP BY exerciseName ORDER BY count DESC");            
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    } 
    function retrieveExerciseRP($exercise,$userID)
    {
        $userSessions = $this->retrieveSessionLogs($userID);
        if(count($userSessions) == 0)
        {
            return [];
        }
        $userSessionsStr = implode(',',$userSessions);
        $statement = $this->pdo->prepare("SELECT MAX(oneRepMax) AS 1RP FROM exerciseLog WHERE sessionID IN (".$userSessionsStr.") AND exerciseName = ?");   
        $statement->bindParam(1,$exercise,PDO::PARAM_STR);             
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    } 
    function retrieveRoutinesLogs($userID)
    {
        $statement = $this->pdo->prepare("SELECT workoutID , count(workoutID) AS times_performed FROM workoutsessionslog WHERE userID = ? GROUP BY workoutID ORDER BY times_performed DESC"); 
        $statement->bindParam(1,$userID,PDO::PARAM_INT);             
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }        
}
?>