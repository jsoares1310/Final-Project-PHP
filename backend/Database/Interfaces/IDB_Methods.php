<?php 
// all methods that returns a string means that is returning a json
interface IDB_Room_Methods {
    public function createElement(int $roomNumber, string $roomType, int $isAvailable, string $roomServices, float $pricePerNight): void;
    public function getAll(): string;
    public function findElement(int $id): string;
    public function updateElement(int $id): void;
    public function deleteElement(int $id): void;
    public function close(): void;
}

// Remember to create new interfaces for the other Database classes
// copy the interface that starts on line 3 and adapt the method parameters
// to fit your new class. Keep the methods names identical.
?>