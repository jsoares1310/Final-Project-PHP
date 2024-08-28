<?php 
require_once("./Database/Staff.db.php");
require_once("./Database/Room.db.php");
require_once("./Database/Booking.db.php");
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
        public function approve_booking(int $booking_id, array $new_data) {
            try {            
                $bookingDb = new Booking();
                $result = $bookingDb->findElement($booking_id);

                $decodedResult = json_decode($result, true);
                if (empty($decodedResult)) {
                    echo "Booking ID Not Exists";
                    return;
                }

                $bookingDb->updateElement($booking_id, $new_data);
                $bookingDb->close();
            } catch (Exception $error) {
                $this->staff->LogMessage($this->log_file, $error->getMessage());
            }
        }

        // Method to manage rooms (add/remove/update)
        public function add_room($room_number, $room_type, $is_available, $room_services, $price_per_night) {
            try {            
                $roomDb = new Room();
                $result = $roomDb->findElement($room_number);

                $decodedResult = json_decode($result, true);
                if (!empty($decodedResult)) {
                    $roomDb->close();
                    echo "This Room Number Already Exists";
                    return;
                }

                $roomDb->createElement($room_number, $room_type, $is_available, $room_services, $price_per_night);
                if (http_response_code() == 200) {
                    echo "New Room Added";
                } else {
                    $roomDb->close();
                    throw new Exception("Room Addition failed", http_response_code());
                }
                $roomDb->close();
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
                    $roomDb->close();
                    echo "This Room Number does not exist";
                    return;
                }

                $roomDb->deleteElement($room_number);
                if (http_response_code() == 200) {
                    echo "Room Deleted";
                } else {
                    $roomDb->close();
                    throw new Exception("Room deletion failed", http_response_code());
                }
                $roomDb->close();

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
                    $roomDb->close();
                    echo "This Room Number does not exist";
                    return;
                }

                $roomDb->updateElement($room_number, $new_data);
                if (http_response_code() == 200) {
                    echo "Room Info Updated";
                } else {
                    $roomDb->close();
                    throw new Exception("Room update failed", http_response_code());
                }
                $roomDb->close();
            } catch (Exception $error) {
                $this->staff->logMessage($this->log_file, $error->getMessage());
            }
        }

        public function closeConnection() {
            $this->staff->close();
        }

        public function LogMessage(string $message) {
            $this->staff->LogMessage($this->log_file, $message);
        }
    }

?>