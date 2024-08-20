<?php 
class Admin extends User {

    public function __construct(int $uid, string $fname, string $lname, string $email, string $phone) {
        parent::__construct($uid, $fname, $lname, $email, $phone);
    }

    // Method to manage users (add/remove/lock/unlock)
    public function add_user(int $uid, string $fname, string $lname, string $email, string $phone) {

    }

    public function edit_user(int $uid) {

    }

    public function del_user(int $uid) {

    }

    public function lock_user(int $uid) {
    
    }

    public function unlock_user(int $uid) {

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
