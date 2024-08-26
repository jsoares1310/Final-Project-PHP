<?php
require_once("./Classes/Customer.db.php");
require_once("./Classes/AbstractClasses/UserClass.php");

class Customer extends User implements IDB_Customer_Methods{
    private float $wallet_balance;
    private string $log_file;
    
    public function __construct(int $uid, string $fname, string $lname, string $email, string $phone, float $wallet_balance) {    
        $this->wallet_balance=$wallet_balance;
        parent::__construct($uid, $fname, $lname, $email, $phone);
        $this->customer = new Customer();
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
    public function add_funds(float $wallet_balance){
        try {
            $this->customer->updateElement($this->email,['wallet_balance' => $wallet_balance]);
            if (http_response_code() == 201) {
                echo "Balance updated!";
            }
            return;
        } catch(Exception $error) {
            $this->staff->logMessage($this->log_file, $error->getMessage());
        }
    }

    }
    public function book_room(User $user, Room $room, string $roomnumber){
    // Fetch available rooms from database, then show available ones. Then update the database to 
        
   

    }
    public function cancel_room(User $user, Room $room, string $roomnumber){
    // Book the Room
        
   

    }

    public function service_used(User $user, string $service){
        // Store information for the service requested via Option Tag in the Front End
        // Breakfast
        // Lunch
        // Dinner
        // Parking
        }
    

    



?>