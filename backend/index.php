<?php
// Always make sure to use only require_once instead of require
require_once("./Database/Migrations/Migrations.php");
require_once("./Classes/StaffClass.php");
new Migrations();
$uri = $_SERVER['REQUEST_URI'];
//print_r($uri);

// print_r($_SERVER);

switch ($uri) {
    case '/login':
        echo ' Not implemented yet';
        break;

    case '/staff/login':
        echo ' Not implemented yet';
        break;

    case '/register':
        echo ' Not implemented yet';
        break;
    
    case '/customer':
        echo ' Not implemented yet';
        break;

    case '/rooms':
        echo ' Not implemented yet';
        break;

    case '/booking':
        echo ' Not implemented yet';
        break;

    case '/staff':
        echo ' Not implemented yet';
        break;
    
    case '/staff/update/room': 
        try {
            if (isset($_POST['room_number'])) {
                $staff = new StaffController(1, 'test', 'test', 'test@test.com', '12345678909');
                //update_room(int $room_number, array $new_data)
                $staff->update_room((int)$_POST['room_number'], $_POST['room_type'], $_POST['is_available'], $_POST['room_services'], $_POST['price_per_night']);

                echo $_POST['room_number'] . " is added";
            } else {
                throw new Exception("key doesn't exist");
            }
        } catch (Exception $error) {
            echo $error->getMessage();
        }
        break;

    case '/staff/add/room': 
        try {
            if (isset($_POST['room_number'])) {
                $staff = new StaffController(1, 'test', 'test', 'test@test.com', '12345678909');
                $staff->add_room((int)$_POST['room_number'], $_POST['room_type'], $_POST['is_available'], $_POST['room_services'], $_POST['price_per_night']);
            } else {
                throw new Exception("key doesn't exist");
            }
        } catch (Exception $error) {
            echo $error->getMessage();
        }
        break;
        
    case '/staff/delete/room':
        try {
            if (isset($_POST['room_number'])) {
                $staff = new StaffController(1, 'test', 'test', 'test@test.com', '12345678909');
                $room_n = (int)$_POST['room_number'];
                $staff->del_room($room_n);

                echo $_POST['room_number'] . " is deleted";
            } else {
                throw new Exception("key doesn't exist");
            }
        } catch (Exception $error) {
            echo $error->getMessage();
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
        break;
}

?>