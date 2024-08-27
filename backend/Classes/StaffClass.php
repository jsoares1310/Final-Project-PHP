<?php 
require_once("./Database/Staff.db.php");
require_once("./Database/Room.db.php");
require_once("./Classes/AbstractClasses/UserClass.php");
class StaffController extends User {
        private Staff $staff;
        private string $log_file;

        public function __construct(int $uid, string $fname, string $lname, string $email, string $phone) {
            parent::__construct($uid, $fname, $lname, $email, $phone);
            $this->staff = new Staff();
            $this->log_file = "staff-access.txt";
        }

        // Method to manage bookings (approve)
        public function approve_booking(int $booking_id) {

        }

        // Method to manage rooms (add/remove/update)
        public function add_room(int $roomId, array $roomDetails = []) {

        }

        public function del_room(int $room_number) {
            try {
                $roomDb = new Room();
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

        public function update_room(int $roomId, array $roomDetails) {
        
        }

        public function closeConnection() {
            $this->staff->close();
        }

        public function LogMessage(string $fileName, string $message) {
            $this->staff->LogMessage($fileName, $message);
        }
    }

?>