<?php 
require_once('./Classes/LogClass.php');
require_once('./Database/Config.php');

class Database {
    // server name ex: localhost
    private string $serverName;
    // Databse user
    private string $userName;
    // Database password
    private string $pwd;
    // Database name
    private string $dbName;
    // mysqli variable for connection to database
    protected mysqli $connection;

    //Log class
    private Log $logger;

    public function __construct()
    {
        // Here the server name is the same name as the db service
        // defined in the docker-compose.yml
        // it needs to be that way because the code is running inside docker network
        $this -> serverName = SERVER_NAME;
        $this -> userName = USER;
        $this -> pwd = PWD;
        $this -> dbName = DATABASE;
        $this -> logger = new Log();

        // connect to database as soon as the class is created.
        $this->connect();
    }

    // Connect to Database
    private function connect(): void {
       try {
        $this->connection = new mysqli(
            $this -> serverName,
            $this -> userName,
            $this -> pwd,
            $this -> dbName,
            PORT
        );

        if ($this->connection->connect_error) {
            $this->logMessage("db-connection-error.txt", "Connection Error");
            die("Connection failed: " . $this->connection->connect_error);
         } //else {
        //     echo "Connected";
        // }
       } catch (Exception $error) {
            $this->logMessage("db-connection-error.txt", $error->getMessage());
            throw new Exception("Server Error", 500);
       }
    }

    protected function close(): void {
        $this->connection->close();
    }

    protected function logMessage(string $fileName, string $message): void {
        $dateTime = date_format(new DateTime('now', new DateTimeZone("America/Vancouver")), "Y:m:d H:i:s");
        $this->logger->write_file($fileName, "$message $dateTime");
    }

}


?>