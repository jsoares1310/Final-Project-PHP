<?php
    class Room {
        // An integer or string representing the room's unique number or identifier.
        private $roomNumber;
        // A string indicating the type of room, such as "Single", "Double", "Suite", etc.
        private $roomType;
        // A boolean indicating whether the room is currently available for booking.
        private $isAvailable;
        // A string indicating which room services are available for this room.
        private $roomService;
        // A float or integer representing the cost per night for the room.
        private $pricePerNight;
        function __construct(int $roomNumber, string $roomType, bool $isAvailable, string $roomService, float $pricePerNight) 
        {
            $this->roomNumber = $roomNumber;
            $this->roomType = $roomType;
            $this->isAvailable = $isAvailable;
            $this->roomService = $roomService;
            $this->pricePerNight = $pricePerNight;
        }
        // Returns detailed information about the room
        public function getRoomInfo() {
            return [
                'roomNumber' => $this->roomNumber,
                'roomType' => $this->roomType,
                'isAvailable' => $this->isAvailable,
                'roomService' => $this->roomService,
                'pricePerNight' => $this->pricePerNight
    
            ];
        }
        // Checks if the room is currently available for booking.
        public function checkAvailability(): bool {
            return $this->isAvailable;
        }
        // Books the room, changing its availability status.
        public function bookRoom(int $numberOfNights) {
            if($this->isAvailable) {
                $this->isAvailable = false;
                return "Room {$this->roomNumber} is booked for {$numberOfNights} nights.";
            } else {
                return "Room {$this->roomNumber} is not available";
            }
        }
        // Handles the checkout process, making the room available again
        public function checkout() {
            $this->isAvailable = true;
            return "Room {$this->roomNumber} is now available";
        }
        // Calculates the total cost of the stay based on the number of nights and the price per night.
        public function calculateTotalCost($numberOfNights) {
            return $numberOfNights * $this->pricePerNight;
        }
        // Updates the price per night for the room.
        public function updateRoomPrice(float $newPrice) {
            $this -> pricePerNight = $newPrice;
            return "The price for room {$this->roomNumber} is now {$newPrice} per night";
        }
    }



?>