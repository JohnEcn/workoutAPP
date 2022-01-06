<?php

class DBconnect
{
    public $pdo = null;
    public function __construct()
    {
        require $_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/Model/databaseConnection/dbConnect.php"; 
        $this->pdo = new PDO($DBserver,$DBusername,$DBpassword);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
    }

    public function userLogin($user,$pass)
    {
        $statement = $this->pdo->prepare("SELECT userID , password FROM user WHERE username = ?");
        $statement->bindParam(1,$user,PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    public function insertUser($user,$pass,$mail)
    {        
        $statement = $this->pdo->prepare("INSERT INTO `user` (`username`, `password`, `email`,`creationDate`) VALUES (?,?,?,now())");
        $statement->bindParam(1,$user,PDO::PARAM_STR);
        $statement->bindParam(2,$pass,PDO::PARAM_STR);
        $statement->bindParam(3,$mail,PDO::PARAM_STR);
        $statement->execute();
        return "SUCCESS";
    }
    public function checkUserUnique($user)
    {
        $statement = $this->pdo->prepare("SELECT * FROM user WHERE username = ?");
        $statement->bindParam(1,$user,PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);        
    }
    public function insertToken($userID,$token)
    {
        $statement = $this->pdo->prepare("UPDATE user SET token = ?, tokenCreationDate = (CURRENT_TIMESTAMP) WHERE userID = ?");
        $statement->bindParam(1,$token,PDO::PARAM_STR);
        $statement->bindParam(2,$userID,PDO::PARAM_INT);
        $statement->execute();   
    }
    public function retrieveToken($userID)
    {
        $statement = $this->pdo->prepare("SELECT token FROM user WHERE userID = ?");
        $statement->bindParam(1,$userID,PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);        
    }
    public function retrieveUserID($token)
    {
        $statement = $this->pdo->prepare("SELECT userID , username   FROM user WHERE token = ?");
        $statement->bindParam(1,$token,PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);        
    }
}

?>