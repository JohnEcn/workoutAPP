<?php

class DBCreate
{
    public $pdo = null;
    private $tableNames = ["exercise","exerciselog","routine","routineexercise","trainingsession","user","userdeletedroutine","userroutine","usersession","workoutsession","workoutsessionslog"];
   
    public function __construct()
    {
        require_once "./dbConnect.php";        
        $this->pdo = new PDO($DBserver,$DBusername,$DBpassword);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
    }

    public function dropAll()
    {   
        $names = $this->tableNames;        
        for($i = 0; $i < count($names);$i++)
        {
            $statement = "DROP TABLE IF EXISTS " . $names[$i];
            $statement = $this->pdo->prepare($statement);
            $statement->execute();
        }          
    }
    public function createAll()
    { 
        $statements[0] = "CREATE TABLE user 
        (
            userID int NOT NULL AUTO_INCREMENT, 
            username varchar(20),
            password varchar(255),
            email varchar(255),
            creationDate DATETIME,
            tokenCreationDate DATE,
            token varchar(255),
            PRIMARY KEY (userID)
        )";
        
        $statements[1] = "CREATE TABLE userroutine 
        (
            routineID int NOT NULL AUTO_INCREMENT, 
            userID int NOT NULL,             
            PRIMARY KEY (routineID)
        )";
        
        $statements[2] = "CREATE TABLE routine 
        (
            routineID int NOT NULL, 
            routineName varchar(25), 
            creationDate DATETIME,            
            PRIMARY KEY (routineID)
        )"; 
        
        $statements[3] = "CREATE TABLE routineexercise 
        (
            routineID int NOT NULL, 
            exerciseID int NOT NULL AUTO_INCREMENT,         
            PRIMARY KEY (exerciseID)
        )";  

        $statements[4] = "CREATE TABLE exercise 
        (
            exerciseID int NOT NULL, 
            exerciseIndex int NOT NULL, 
            exerciseName varchar(255),
            exerciseSets int NOT NULL, 
            exerciseRest int NOT NULL,            
            PRIMARY KEY (exerciseID)
        )";

        $statements[5] = "CREATE TABLE trainingsession 
        (
            userID int NOT NULL, 
            workoutID int NOT NULL, 
            currentExerciseID int NOT NULL, 
            setsRemaining int NOT NULL,    
            exerciseList varchar(5000),
            sessionStats varchar(5000),
            lastModified DATETIME,           
            PRIMARY KEY (userID)
        )";  

        $statements[6] = "CREATE TABLE workoutSessionsLog 
        (
            sessionID int NOT NULL AUTO_INCREMENT,
            userID int NOT NULL, 
            workoutID int NOT NULL, 
            dateCompleted DATETIME,           
            PRIMARY KEY (sessionID)
        )";  

        $statements[7] = "CREATE TABLE exerciseLog 
        (
            sessionID int NOT NULL, 
            exerciseName varchar(255), 
            totalVolume int NOT NULL,    
            oneRepMax int NOT NULL,     
            PRIMARY KEY (sessionID,exerciseName)
        )";  
            
       for($i = 0; $i < 8;$i++)
        {
            $statement = $this->pdo->prepare($statements[$i]);
            $statement->execute();
        }
                 
    }


}

$db = new DBCreate;
if(isset($_GET['action']) && $_GET['action'] == "dropDB")
{
    $db->dropAll();
    echo"All tables dropped";
}
elseif(isset($_GET['action']) && $_GET['action'] == "createDB")
{
    $db->createAll();
    echo"All tables created";
}
else
{
    http_response_code(400);
}


?>