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
        $output = [];

        return json_encode($output);
    }
    public function findElement(string $email): string {
        $output = [];

        return json_encode($output);
    }
    public function updateElement(string $email, array $new_data): void {}
    public function deleteElement(string $email): void {}
    public function logMessage(string $file_name, string $message): void {
        parent::logMessage($file_name, $message);
    }
    public function close(): void {}
}
?>