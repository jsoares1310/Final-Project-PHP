<?php
require_once('./Database/Migrations/RoomsMigrations.php');
require_once("./Database/Migrations/StaffMigration.php");
require_once("./Database/Migrations/CustomerMigration.php");
require_once('./Database/Migrations/BookingMigration.php');

/**
 * Constructor for the Migrations class.
 * @return void Create all essential tables and inital data
 */
class Migrations {
    public function __construct()
    {
        // create initial tables and data
        new RoomsMigrations();
        new StaffMigration();
        new CustomerMigration();
        new BookingMigration();
    }
}
?>