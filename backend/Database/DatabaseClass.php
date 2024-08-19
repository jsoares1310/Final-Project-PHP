<?php 


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
    private mysqli $connection;

    public function __construct(string $userName, string $pwd, string $dbName)
    {
        // Here the server name is the same name as the db service
        // defined in the docker-compose.yml
        // it needs to be that way because the code is running inside docker network
        $this -> serverName = "db";
        $this -> userName = $userName;
        $this -> pwd = $pwd;
        $this -> dbName = $dbName;

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
            3306 
        );

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
         } //else {
        //     echo "Connected";
        // }
       } catch (Exception $error) {
            throw new Exception("Server Error", 500);
       }
    }

    public function getAll(string $table, string $query = null): string {
        $result = null;
        $output = [];
        if ($query == null) {
         $result = $this->connection->query("SELECT * FROM $table");
        } elseif($query !== null) {
            $result = $this->connection->query($query);
        }

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($output, $row);
            }
        }

        return json_encode($output);
    }

    public function close(): void {
        $this->connection->close();
    }

}


?>