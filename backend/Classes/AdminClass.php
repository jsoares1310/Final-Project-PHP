<?php 
require_once("./Database/Staff.db.php");
require_once("./Database/Customer.db.php");
require_once("./Classes/AbstractClasses/UserClass.php");
class AdminController extends User {
    private Staff $staff;
    private string $log_file;

    private string $role;

    public function __construct(int $uid, string $fname, string $lname, string $email, string $phone) {
        parent::__construct($uid, $fname, $lname, $email, $phone);
        $this->role = "admin";
        $this->log_file = "admin-controller.txt";
        $this->staff = new Staff();
    }

    // Method to manage users (add/remove/lock/unlock)
    public function add_user(string $pwd) {
        try {
            $this->staff->createElement($this->fname, $this->lname, $this->phone, $this->email, $pwd, $this->role);
            if (http_response_code() == 201) {
                echo "Staff created";
            }
            return;
        } catch(Exception $error) {
            $this->staff->logMessage($this->log_file, $error->getMessage());
        }
    }

    public function edit_user(string $email, array $new_data) {
        try {
            $staffDb = new Staff();
            $result = $staffDb->findElement($email);

            $decodedResult = json_decode($result, true);

            if (empty($decodedResult)) {
                echo "This Email Not Exists";
                return;
            }

            $staffDb->updateElement($email, $new_data);
            if (http_response_code() == 200) {
                echo "User Info Updated";
            } else {
                throw new Exception("User Edit failed", http_response_code());
            }
        } catch (Exception $error) {
            $this->staff->logMessage($this->log_file, $error->getMessage());
        }
    }

    public function block_user(string $email, array $new_data) {
        try {
            $customerDb = new Customer();
            $result = $customerDb->findElement($email);

            $decodedResult = json_decode($result, true);

            if (empty($decodedResult)) {
                echo "This Email Not Exists";
                return;
            }

            $customerDb->updateElement($email, $new_data);
            if (http_response_code() == 200) {
                echo "This Customer blocked";
            } else {
                throw new Exception("Failed To Block", http_response_code());
            }
        } catch (Exception $error) {
            $this->staff->logMessage($this->log_file, $error->getMessage());
        }
    }

    public function unblock_user(string $email, array $new_data) {
        try {
            $customerDb = new Customer();
            $result = $customerDb->findElement($email);

            $decodedResult = json_decode($result, true);

            if (empty($decodedResult)) {
                echo "This Email Not Exists";
                return;
            }

            $customerDb->updateElement($email, $new_data);
            if (http_response_code() == 200) {
                echo "This Customer Unblocked";
            } else {
                throw new Exception("Failed To Unblock", http_response_code());
            }
        } catch (Exception $error) {
            $this->staff->logMessage($this->log_file, $error->getMessage());
        }
    }

    
    // Method to manage rooms (add/remove/update)
    public function add_room($room_number, $room_type, $is_available, $room_services, $price_per_night) {
        try {            
            $roomDb = new Room();
            $result = $roomDb->findElement($room_number);

            $decodedResult = json_decode($result, true);
            if (!empty($decodedResult)) {
                echo "This Room Number Already Exists";
                return;
            }

            $roomDb->createElement($room_number, $room_type, $is_available, $room_services, $price_per_night);
            if (http_response_code() == 200) {
                echo "New Room Added";
            } else {
                throw new Exception("Room Addition failed", http_response_code());
            }

        } catch (Exception $error) {
            $this->staff->LogMessage($this->log_file, $error->getMessage());
        }
    }

    public function del_room(int $room_number) {
        try {
            $roomDb = new Room();
            $result = $roomDb->findElement($room_number);

            $decodedResult = json_decode($result, true);

            if (empty($decodedResult)) {
                echo "This Room Number does not exist";
                return;
            }

            $roomDb->deleteElement($room_number);
            if (http_response_code() == 200) {
                echo "Room Deleted";
            } else {
                throw new Exception("Room deletion failed", http_response_code());
            }

        } catch (Exception $error) {
            $this->staff->LogMessage($this->log_file, $error->getMessage());
        }
    }

    public function update_room(int $room_number, array $new_data) {
        try {
            $roomDb = new Room();
            $result = $roomDb->findElement($room_number);

            $decodedResult = json_decode($result, true);

            if (empty($decodedResult)) {
                echo "This Room Number does not exist";
                return;
            }

            $roomDb->updateElement($room_number, $new_data);
            if (http_response_code() == 200) {
                echo "Room Info Updated";
            } else {
                throw new Exception("Room update failed", http_response_code());
            }
        } catch (Exception $error) {
            $this->staff->logMessage($this->log_file, $error->getMessage());
        }
    }

    public function closeConnection() {
        $this->staff->close();
    }
}
?>
