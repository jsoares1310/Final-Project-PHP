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

    public function createElement(string $first_name, string $last_name, string $phone, string $email, string $password, float $wallet_balance = 0.00): void {
        try {
            // Checks if customer exists
            $verify = $this->connection->prepare("SELECT email FROM $this->customer_table WHERE email=?");

            $verify->bind_param("s", $email);
            $verify->execute();
            $verify->bind_result($result);
            $verify->fetch();
            
            if ($result) {
                $verify->close();
                throw new Exception("Customer already exists", 406);
            } else {
                $verify->close();
            }

            // Create new customer
            $query = "INSERT INTO $this->customer_table (first_name, last_name, phone, email, password, wallet_balance) VALUES (?,?,?,?,?,?)";
            $action = $this->connection->prepare($query);
            
            $action->bind_param("sssssd", $first_name, $last_name, $phone, $email, $password, $wallet_balance);

            $action->execute();

            if ($action->affected_rows > 0) {
                http_response_code(201);
                $action->close();
                parent::logMessage($this->log_file, "$email: Customer registered");
            } else {
                $action->close();
                throw new Exception("Customer $email registration failed", 500);
            }
            
        } catch(Exception $error) {
            parent::logMessage($this->log_file, $error->getMessage() . ", Code: " . $error->getCode());
        }
    }

    public function getAll(): string {
        try {
            $output = [];
            $query = "SELECT first_name, last_name, phone, email, wallet_balance, is_blocked FROM $this->customer_table";
            $action = $this->connection->prepare($query);

            if ($action -> execute()) {

                $action->bind_result($first_name, $last_name, $phone, $email, $wallet_balance, $is_blocked);

                while($action->fetch()) {
                    array_push($output, [
                        "first_name" => $first_name,
                        "last_name" => $last_name,
                        "phone" => $phone,
                        "email" => $email,
                        "wallet_balance" => $wallet_balance,
                        "is_blocked" => $is_blocked
                    ]);
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

    // update Customer accordingly to new_data array
        // new_data array should be an associate array
        // with key names being the name of the columns
        // password can be updated here.
        // e.g [
        //     "first_name" => '',
        //     "last_name" => '',
        //     "phone" => '', max 15 char
        //     "email" => '',
        //     "wallet_balance" => 100.90,
        //     "is_blocked" => 0 or 1,
        // ];
    public function updateElement(string $email, array $new_data): void {
        try {
            $count = count(array_keys($new_data));
            $arrKeys = array_keys($new_data);
            $types = "";
            $values = [];

            $query = "UPDATE $this->customer_table SET ";

            for($i=0; $i < $count-1; $i++) {
                $key = $arrKeys[$i];
                $types .= gettype($new_data[$key])[0]; 

                array_push($values, $new_data[$key]);
                $query .= "$key = ?, ";
            }

            //might get an error if the new_data array is empty
            $key = $arrKeys[$count-1];
            $types .= gettype($new_data[$key])[0];
            array_push($values, $new_data[$key]);
            $query .= "$key = ? ";
            $query .= "WHERE email = ?";
            // here push the last value for the update
            // the type appended needs to follow the last value type
            array_push($values, $email);
            $types .= "s";

            $action = $this->connection->prepare($query);

            $action->bind_param($types, ...$values);

            $action->execute();


            if ($action->affected_rows > 0) {
                http_response_code(200);
                $action->close();
                parent::logMessage($this->log_file, "Customer updated $email");
            } elseif ($action->affected_rows < 0) {
                $action->close();
                throw new Exception("Update customer $email: Nothing changed", 204);
            }

        } catch (Exception $error) {
            parent::logMessage($this->log_file, $error->getMessage() . ", Code: " . $error->getCode());
        }
    }

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