<?php 
    class Staff extends User {

        public function __construct(int $uid, string $fname, string $lname, string $email, string $phone) {
            parent::__construct($uid, $fname, $lname, $email, $phone);
        }

        // Method to manage bookings (approve)
        public function approve_booking(int $booking_id) {

        }

        // Method to manage rooms (add/remove/update)
        public function add_room(int $roomId, array $roomDetails = []) {

        }

        public function del_room(int $roomId) {
        
        }

        public function update_room(int $roomId, array $roomDetails) {
        
        }
    }

?>