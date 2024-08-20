<?php
class Customer extends User{
    public function __construct(int $uid, string $fname, string $lname, string $email, string $phone) {
        parent::__construct($uid, $fname, $lname, $email, $phone);
    }
    public function book_room(User $user){

    }

    public function requested_service(User $user, string $service){
        // Store information for the service requested via Option Tag in the Front End
        // Breakfast
        // Lunch
        // Dinner
        // Parking
        }
    }

    



?>