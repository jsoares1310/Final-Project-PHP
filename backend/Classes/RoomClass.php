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
    }



?>