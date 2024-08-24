<?php 
class StaffMigration extends Database {

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
            $query = "SHOW TABLES LIKE 'staff'";
            $action = $this->connection->query($query);
            
            if ($action->num_rows > 0) {
                return;
            } else {
                // if the table staff doesn't exists, create.
                $this->createStaffTable();
            }
        } catch(Exception $error) {
            parent::logMessage($this->log_file, $error->getMessage());
        }
    }

    private function checkIfDataExists() {
        try {
            $query = "SELECT COUNT(*) AS total_rows FROM staff";
            $action = $this->connection->query($query);
            $result = $action->fetch_assoc();

            if ($result["total_rows"] > 0) {
                return;
            } else {
                // if staff have no data, use the insert.
                $this->insertInitialStaffData();
            }

        } catch (Exception $error) {
            parent::logMessage($this->log_file, $error->getMessage());
        }
    }

    private function createStaffTable () {
        try {
            $query = "CREATE TABLE IF NOT EXISTS staff (
            id INT AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
		    phone VARCHAR(15) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'staff') NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";

            $action = $this->connection->query($query);
            if ($action) {
                parent::logMessage($this->log_file, "Staff table created");
            } else {
                throw new Exception("Creation failed", 500);
            }
        } catch(Exception $error) {
            parent::logMessage($this->log_file, "Staff table:" . $error->getMessage());
        }
    }

    private function insertInitialStaffData() {
        try {
            $query = "INSERT INTO staff (first_name, last_name, phone, email, password, role) VALUES 
            ('John', 'Doe', '1234567890', 'john.doe@staff.com', '123456', 'staff'),
            ('Admin', 'Admin', '09876543212', 'admin@admin.com', '123456', 'admin')";

            $action = $this->connection->query($query);

            if ($action) {
                parent::logMessage($this->log_file, "Insert default values to Staff");
            } else {
                throw new Exception("Insert default value to staff failed", 500);
            }
        } catch(Exception $error) {
            parent::logMessage($this->log_file, "Insert staff data: " . $error->getMessage());
        } 
    }
}
?>