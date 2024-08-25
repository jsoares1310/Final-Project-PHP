<?php
require_once("./Database/DatabaseClass.php"); 
class BookingMigration extends Database {

    private string $log_file;

    public function __construct()
    {
        $this -> log_file = "database-migration.txt";
        parent::__construct();
        $this->checkIfTableExists();
        $this->checkIfDataExists();
        parent::close();
    }

    private function checkIfTableExists() {
        try {
            $query = "SHOW TABLES LIKE 'booking'";
            $action = $this->connection->query($query);
            
            if ($action->num_rows > 0) {
                return;
            } else {
                // if the table staff doesn't exists, create.
                $this->createBookingTable();
            }
        } catch(Exception $error) {
            parent::logMessage($this->log_file, $error->getMessage());
        }
    }

    private function checkIfDataExists() {
        try {
            $query = "SELECT COUNT(*) AS total_rows FROM booking";
            $action = $this->connection->query($query);
            $result = $action->fetch_assoc();

            if ($result["total_rows"] > 0) {
                return;
            } else {
                // if staff have no data, use the insert.
                $this->insertInitialBookingData();
            }

        } catch (Exception $error) {
            parent::logMessage($this->log_file, $error->getMessage());
        }
    }

    private function createBookingTable () {
        try {
            $query = "CREATE TABLE IF NOT EXISTS booking (
                id INT AUTO_INCREMENT PRIMARY KEY,
                customer_email VARCHAR(255) NOT NULL,
                room_number INT NOT NULL,
                check_in DATE NOT NULL,
                check_out DATE NOT NULL,
                status ENUM('pending', 'approved', 'cancelled') DEFAULT 'pending',
                total_price DECIMAL(10, 2) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (customer_email) REFERENCES customer(email) ON DELETE CASCADE,
                FOREIGN KEY (room_number) REFERENCES rooms(room_number) ON DELETE CASCADE,
                UNIQUE KEY unique_booking (room_number, check_in, check_out)
            )";

            $action = $this->connection->query($query);
            if ($action) {
                parent::logMessage($this->log_file, "Booking table created");
            } else {
                throw new Exception("Creation failed", 500);
            }
        } catch(Exception $error) {
            parent::logMessage($this->log_file, "Booking table:" . $error->getMessage());
        }
    }

    private function insertInitialBookingData() {
        try {
            $query = "INSERT INTO booking (customer_email, room_number, check_in, check_out, status, total_price) 
            VALUES 
            ('john.doe@example.com', 101, '2024-09-01', '2024-09-05', 'approved', 400.00)
            ";

            $action = $this->connection->query($query);

            if ($action) {
                parent::logMessage($this->log_file, "Insert default values to Booking");
            } else {
                throw new Exception("Insert default value to Booking failed", 500);
            }
        } catch(Exception $error) {
            parent::logMessage($this->log_file, "Insert booking data: " . $error->getMessage());
        } 
    }
}
?>