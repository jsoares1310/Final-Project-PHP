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

    public function add_funds(float $wallet_balance){
        try {
            $this->customer->updateElement($this->email,['wallet_balance' => $wallet_balance]);
            if (http_response_code() == 201) {
                echo "Balance updated!";
            }
            return;
        } catch(Exception $error) {
            $this->customer->logMessage($this->log_file, $error->getMessage());
        }
    }

    public function book_room(Room $room_number, Room $is_available, Room $price_per_night, $wallet_balance){
    // Fetch available rooms from database, then show available ones. Then update the database to 
    try {
        if($is_available && $price_per_night < $wallet_balance){

    }
}
    catch(Exception $error){

    }
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