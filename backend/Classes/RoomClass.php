<?php
    require ("../Database/RoomClass.php");
    class Room {
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
        public function bookRoom(int $numberOfNights) {
            if($this->is_available) {
                $this->is_available = false;
                return "Room {$this->room_number} is booked for {$numberOfNights} nights.";
            } else {
                return "Room {$this->room_number} is not available";
            }
        }
        // Handles the checkout process, making the room available again
        public function checkout() {
            $this->is_available = true;
            return "Room {$this->room_number} is now available";
        }
        // Calculates the total cost of the stay based on the number of nights and the price per night.
        public function calculateTotalCost($numberOfNights) {
            return $numberOfNights * $this->price_per_night;
        }
        // Updates the price per night for the room.
        public function updateRoomPrice(float $newPrice) {
            $this -> price_per_night = $newPrice;
            return "The price for room {$this->room_number} is now {$newPrice} per night";
        }
    }



?>