<?php
require_once("./Classes/Customer.db.php");
require_once("./Classes/AbstractClasses/UserClass.php");
require_once("./Classes/Booking.db.php");
require_once("./Classes/RoomClass.php");
require_once("./Classes/Room.db.php");

class CustomerController extends User {
    private float $wallet_balance;
    private Customer $customer;
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

    // Fetch available rooms from database, then show available ones. Then update the database to 
    // *****Logic still needs to be updated including Booking Class*****
    public function book_room(string $email,int $room_number, bool $is_available, float $price_per_night, float $wallet_balance){
        try {
            $room = new Room();
            
            if($is_available && $price_per_night < $wallet_balance){
               $this->customer->updateElement($this->email,['wallet_balance' => $wallet_balance - $price_per_night]);
               $room->updateElement($room_number,['is_available' => false]);
               $room->close();
            }else{
            print_r('Insufficient funds');
        }}
        catch(Exception $error){
            $this->customer->logMessage($this->log_file, $error->getMessage());
        }
    }

    public function cancel_room(int $room_number, bool $is_available){
    // Cancel the Room
        try{
            $room_number->updateElement($room_number,['is_available' => true]);
            print_r('Sorry. No refunds allowed :(')
        }   
        catch(Exception $error){

        }

    }

    public function service_used(User $user, string $service){
        // Store information for the service requested via Option Tag in the Front End
        // Breakfast
        // Lunch
        // Dinner
        // Parking
        }
    

}

?>