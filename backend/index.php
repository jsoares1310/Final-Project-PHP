<?php 

// echo "Testing server";
// echo "</br>";

// echo "Php working from docker"

require("./Database/Room.db.php");

$db = new Room();
// echo "<br/>";
// print_r($db->getAll());
// $db->createElement(112, 'single', 1, 'WiFi, TV', 7500.90);
// print_r($db->findElement(112));
// $db->deleteElement(105);
// $db->updateElement(101, [
//         // "room_number" => 103,
//         "room_type" => "double",
//         // "is_available" => 0,
//         // "room_service" => "WiFi, TV, Air, Bar",
//         "price_per_night" => 120.55
//     ]);
// $db->close();

// require ("./Database/Customer.db.php");

// $cusDb = new Customer();

// print_r($cusDb->getAll());
// print_r($cusDb->findElement("john.doe@example.com"));
// $cusDb->deleteElement("john.doe@example.com");
// $cusDb->updateElement("john.doe@example.com", [
//     "last_name" => 'eoD',
// ]);
// $cusDb->createElement('victor', 'araujo', '1234567890', 'victor@outlook.com', 'admin');

// $cusDb->close();

?>