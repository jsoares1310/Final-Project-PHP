<?php 
require_once("./Database/DatabaseClass.php"); 
class CustomerMigration extends Database {

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
            $query = "SHOW TABLES LIKE 'customer'";
            $action = $this->connection->query($query);
            
            if ($action->num_rows > 0) {
                return;
            } else {
                // if the table customer doesn't exists, create.
                $this->createCustomerTable();
            }
        } catch(Exception $error) {
            parent::logMessage($this->log_file, $error->getMessage());
        }
    }

    private function checkIfDataExists() {
        try {
            $query = "SELECT COUNT(*) AS total_rows FROM customer";
            $action = $this->connection->query($query);
            $result = $action->fetch_assoc();

            if ($result["total_rows"] > 0) {
                return;
            } else {
                // if customer have no data, use the insert.
                $this->insertInitialCustomerData();
            }

        } catch (Exception $error) {
            parent::logMessage($this->log_file, $error->getMessage());
        }
    }

    private function createCustomerTable () {
        try {
            $query = "CREATE TABLE IF NOT EXISTS customer (
            id INT AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
		    phone VARCHAR(15) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            wallet_balance DECIMAL(10, 2) DEFAULT 0.00,
            is_blocked TINYINT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";

            $action = $this->connection->query($query);
            if ($action) {
                parent::logMessage($this->log_file, "Customer table created");
            } else {
                throw new Exception("Customer table creation failed", 500);
            }
        } catch(Exception $error) {
            parent::logMessage($this->log_file, "Customer table:" . $error->getMessage());
        }
    }

    private function insertInitialCustomerData() {
        try {
            $query = "INSERT INTO customer (first_name, last_name, phone, email, password, wallet_balance, is_blocked) VALUES ('John', 'Doe', '1234567890', 'john.doe@example.com', '123456', 150.00, 0)";

            $action = $this->connection->query($query);

            if ($action) {
                parent::logMessage($this->log_file, "Insert default values to Customer");
            } else {
                throw new Exception("Insert default value to customer failed", 500);
            }
        } catch(Exception $error) {
            parent::logMessage($this->log_file, "Insert Customer Data: " . $error->getMessage());
        } 
    }

}


?>