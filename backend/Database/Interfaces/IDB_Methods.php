<?php 
// all methods that returns a string means that is returning a json
interface IDB_Room_Methods {
    public function createElement(int $room_number, string $room_type, int $is_available, string $room_services, float $price_per_night): void;
    public function getAll(): string;
    public function findElement(int $room_number): string;
    public function updateElement(int $room_number, array $new_data): void;
    public function deleteElement(int $room_number): void;
    public function close(): void;
}

// Remember to create new interfaces for the other Database classes
// copy the interface that starts on line 3 and adapt the method parameters
// to fit your new class. Keep the methods names identical.
?>