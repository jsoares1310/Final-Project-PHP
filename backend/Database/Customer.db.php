<?php 
require("./Database/DatabaseClass.php");
require("./Database/Interfaces/IDB_Methods.php");
require("./Database/Migrations/CustomerMigration.php");
class Customer extends Database implements IDB_Customer_Methods {
    private string $log_file;
    private string $customer_table;

    public function __construct()
    {
        new CustomerMigration();

        parent::__construct();

        $this->customer_table = "customer";
        $this->log_file = "customer-db-log.txt";
    }

    public function createElement(string $first_name, string $last_name, string $phone, string $email, string $password, float $wallet_balance=0.00): void {}

    public function getAll(): string {
        try {
            $output = [];
            $query = "SELECT first_name, last_name, phone, email, wallet_balance, is_blocked FROM $this->customer_table";
            $action = $this->connection->prepare($query);

            if ($action -> execute()) {

                $action->bind_result($first_name, $last_name, $phone, $email, $wallet_balance, $is_blocked);

                while($action->fetch()) {
                    $output += [
                        "first_name" => $first_name,
                        "last_name" => $last_name,
                        "phone" => $phone,
                        "email" => $email,
                        "wallet_balance" => $wallet_balance,
                        "is_blocked" => $is_blocked
                    ]; 
                }
    
                $action->close();
                http_response_code(200);
            } else {
                $action->close();
                http_response_code(500);
                throw new Exception("Failed to get all Customers", 500);
            }
            
            return json_encode($output);
        } catch(Exception $error) {
            parent::logMessage($this->log_file, $error->getMessage() . ", Code: " . $error->getCode());
        }
    }

    public function findElement(string $email): string {
        try {
            $output = [];
            $query = "SELECT first_name, last_name, phone, email, wallet_balance, is_blocked FROM $this->customer_table WHERE email=?";
            $action = $this->connection->prepare($query);

            $action->bind_param("s", $email);

            if ($action->execute()) {
                $action->bind_result($first_name, $last_name, $phone, $email, $wallet_balance, $is_blocked);
                while($action->fetch()) {
                    $output += [
                        "first_name" => $first_name,
                        "last_name" => $last_name,
                        "phone" => $phone,
                        "email" => $email,
                        "wallet_balance" => $wallet_balance,
                        "is_blocked" => $is_blocked
                    ];
                }
                $action->close();
                http_response_code(200);
            } else {
                $action->close();
                http_response_code(500);
                throw new Exception("Find customer failed", 500);
            }

            return json_encode($output);
        } catch(Exception $error) {
            parent::logMessage($this->log_file, $error->getMessage() . ", Code: " . $error->getCode());
        }
    }

    public function updateElement(string $email, array $new_data): void {}

    public function deleteElement(string $email): void {
        try {
            $query = "DELETE FROM $this->customer_table WHERE email=?";
            $action = $this->connection->prepare($query);

            $action->bind_param("s", $email);

            $action->execute();

            if ($action->affected_rows > 0) {
                http_response_code(200);
                parent::logMessage($this->log_file, "$email : Customer deleted");
                $action->close();
            } else {
                $action->close();
                http_response_code(500);
                throw new Exception("$email : Customer deletion failed", 500);
            }


        }catch(Exception $error) {
            parent::logMessage($this->log_file, $error->getMessage() . ", Code: " . $error->getCode());
        }
    }

    public function logMessage(string $file_name, string $message): void {
        parent::logMessage($file_name, $message);
    }

    public function close(): void {
        parent::close();
    }
}
?>