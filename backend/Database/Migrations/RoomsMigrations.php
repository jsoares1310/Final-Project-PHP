<?php 

// require("./Database/DatabaseClass.php");
class RoomsMigrations extends Database {
    
    public function __construct() {
        
        parent::__construct();
        $this->checkIfTableExists();
        $this->checkIfDataExists();
        parent::close();
    }


    private function checkIfDataExists() {
        try {
            $query = "SELECT COUNT(*) AS total_rows FROM rooms";
            $action = $this->connection->query($query);
            $result = $action->fetch_assoc();

            if ($result["total_rows"] > 0) {
                return;
            } else {
                // if rooms have no data, use the insert.
                $this->insertInitialData();
            }

        } catch (Exception $error) {
            parent::logMessage("database-migration.txt", $error->getMessage());
        }
    }

    private function checkIfTableExists() {
        try {
            $query = "SHOW TABLES LIKE 'rooms'";
            $action = $this->connection->query($query);
            // $result = $action->fetch_assoc();
            if ($action->num_rows > 0) {
                return;
            } else {
                // if the table rooms doesn't exists, create.
                $this->createTable();
            }

        } catch (Exception $error) {
            parent::logMessage("database-migration.txt", $error->getMessage());
        }
    }

    private function createTable() {
        try {
            $query = "CREATE TABLE IF NOT EXISTS rooms (
	                id INT AUTO_INCREMENT PRIMARY KEY,
                    room_number SMALLINT NOT NULL UNIQUE,
                    room_type ENUM('single', 'double', 'suite') NOT NULL,
                    is_available TINYINT NOT NULL DEFAULT 1,
                    room_service VARCHAR(400) NOT NULL,
                    price_per_night FLOAT NOT NULL
                    )";
            if ($this->connection->query($query)) {
                parent::logMessage("database-migration.txt", "Room Table Created");
            } else {
                throw new Exception("Room table creation failed", 500);
            }
        } catch (Exception $error) {
            parent::logMessage("database-migration.txt", $error->getMessage());
        }
    }

    private function insertInitialData() {
        try {
            $query = "INSERT INTO rooms (room_number, room_type, is_available, room_service, price_per_night) VALUES
                        (101, 'single', 1, 'WiFi, TV', 75.00),
                        (102, 'double', 1, 'WiFi, TV, Mini Bar', 120.00),
                        (103, 'suite', 1, 'WiFi, TV, Mini Bar, Jacuzzi', 250.00),
                        (104, 'single', 0, 'WiFi, TV', 80.00),
                        (105, 'double', 1, 'WiFi, TV', 115.00),
                        (106, 'suite', 0, 'WiFi, TV, Mini Bar', 270.00),
                        (107, 'single', 1, 'WiFi', 70.00),
                        (108, 'double', 0, 'WiFi, TV, Mini Bar, Room Service', 140.00),
                        (109, 'suite', 1, 'WiFi, TV, Mini Bar, Jacuzzi, Room Service', 300.00),
                        (110, 'single', 1, 'WiFi, TV, Mini Bar', 85.00)";
            if ($this->connection->query($query)) {
                parent::logMessage("database-migration.txt", "Inserted default rooms to the table");
            } else {
                throw new Exception("Room insertion failed", 500);
            }
        } catch (Exception $error) {
            parent::logMessage("database-migration.txt", $error->getMessage());
        }
    }

}

?>