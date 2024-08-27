<?php
    require ("../Database/RoomClass.php");
    class RoomController {
        // An integer or string representing the room's unique number or identifier.
        private $room_number;
        // A string indicating the type of room, such as "Single", "Double", "Suite", etc.
        private $room_type;
        // A boolean indicating whether the room is currently available for booking.
        private $is_available;
        // A string indicating which room services are available for this room.
        private $room_services;
        // A float or integer representing the cost per night for the room.
        private $price_per_night;
        // A room Object
        private Room $room;
        function __construct(int $room_number, string $room_type, bool $is_available, string $room_services, float $price_per_night) 
        {
            $this->room_number = $room_number;
            $this->room_type = $room_type;
            $this->is_available = $is_available;
            $this->room_services = $room_services;
            $this->price_per_night = $price_per_night;
            $this->room = new Room();
        }
        // Returns detailed information about the room
        public function getRoomInfo(int $room_number) {
            try {
                $this->room->findElement($room_number);
            } catch(Exception $error) {
                
            }
        }
        // Checks if the room is currently available for booking.
        public function checkAvailability(): bool {
            return $this->is_available;
        }

        // Books the room, changing its availability status.
        public function bookRoom(int $room_number) {
            try {
                $this->room->updateElement($room_number, ["is_available" => false]);
            } catch(Exception $error) {

            }
        }

        // Handles the checkout process, making the room available again
        public function checkout(int $room_number) {
            try {
                $this->room->updateElement($room_number, ["is_available" => true]);
            } catch(Exception $error) {

            }
        }

        // Calculates the total cost of the stay based on the number of nights and the price per night.
        public function calculateTotalCost($numberOfNights) {
            return $numberOfNights * $this->price_per_night;
        }

        // Updates the price per night for the room.
        public function updateRoomPrice(int $room_number, float $new_price) {
            try {
                $this->room->updateElement($room_number, ["price_per_night" => $new_price]);
            } catch(Exception $error) {

            }   
        }
    }



?>