<?php 
// all methods that returns a string means that is returning a json
interface IDB_Methods {
    public function createElement(): void;
    public function getAll(): string;
    public function findElement(int $id): string;
    public function updateElement(int $id): void;
    public function deleteElement(int $id): void;
    public function close(): void;
}


?>