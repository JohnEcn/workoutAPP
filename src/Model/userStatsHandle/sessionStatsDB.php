<?php 
class statsDB
{
    public $pdo = null;
    public function __construct()
    {
        require $_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/databaseConnection/dbConnect.php"; 
        $this->pdo = new PDO($DBserver,$DBusername,$DBpassword);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
    }

    public function updateSessionStats($userID,$sessionStats)
    {
        $statement = $this->pdo->prepare("UPDATE  trainingsession SET sessionStats = ?  WHERE userID = ?");
        $statement->bindParam(1,$sessionStats,PDO::PARAM_STR);
        $statement->bindParam(2,$userID,PDO::PARAM_INT);
        $statement->execute();       
    }  
    public function getSessionStats($userID)
    {
        $statement = $this->pdo->prepare("SELECT sessionStats FROM trainingsession WHERE userID = ?");
        $statement->bindParam(1,$userID,PDO::PARAM_INT);
        $statement->execute(); 
        return $statement->fetchAll(PDO::FETCH_COLUMN);      
    }        
}



