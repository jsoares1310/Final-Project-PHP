<?php
// Always make sure to use only require_once instead of require
require_once("./Database/Migrations/Migrations.php");
require_once("./Classes/StaffClass.php");
require_once("./Classes/AdminClass.php");
require_once("./Classes/CustomerClass.php");
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
    
        // Finish customer route commands:
    case '/customer':
        echo ' Not implemented yet';
        // $custom = new CustomerController();
        // $custom->book_room();

        break;

    case '/rooms':
        echo ' Not implemented yet';
        break;

    case '/booking':
        echo ' Not implemented yet';
        break;
    
    case '/admin/edit/user':
        try {
            if (isset($_POST['email'])) {
                $admin = new AdminController(1, 'test', 'test', 'test@test.com', '12345678909');
                $makeArray = [];
                foreach(['first_name', 'last_name', 'phone', 'role'] as $key){
                    $makeArray[$key] = $_POST[$key];    
                }
                print_r($makeArray);
                //echo $_POST['email'];
                $admin->edit_user($_POST['email'], $makeArray);
                
            } else {
                throw new Exception("key doesn't exist");
            }
        } catch (Exception $error) {
            echo $error->getMessage();
        }
        break;

    case '/admin/block/user':
        try {
            if (isset($_POST['email'])) {
                $admin = new AdminController(1, 'test', 'test', 'test@test.com', '12345678909');              
                $makeArray['is_blocked'] = 1;    

                $admin->block_user($_POST['email'], $makeArray);
                
            } else {
                throw new Exception("key doesn't exist");
            }
        } catch (Exception $error) {
            echo $error->getMessage();
        }
        break;
    case '/admin/unblock/user':
        try {
            if (isset($_POST['email'])) {
                $admin = new AdminController(1, 'test', 'test', 'test@test.com', '12345678909');              
                $makeArray['is_blocked'] = 0;    

                $admin->unblock_user($_POST['email'], $makeArray);
                
            } else {
                throw new Exception("key doesn't exist");
            }
        } catch (Exception $error) {
            echo $error->getMessage();
        }
        break;

    case '/staff':
        echo ' Not implemented yet';
        break;
    case '/staff/approve/room':
        try {
            if (isset($_POST['id'])) {
                $staff = new StaffController(1, 'test', 'test', 'test@test.com', '12345678909');
                $makeArray = [];
                foreach(['status'] as $key){
                    $makeArray[$key] = $_POST[$key];    
                }
                //print_r($makeArray);

                $staff->approve_booking($_POST['id'], $makeArray);
                
            } else {
                throw new Exception("key doesn't exist");
            }
        } catch (Exception $error) {
            echo $error->getMessage();
        }
        break;
    case '/staff/update/room': 
        try {
            if (isset($_POST['room_number'])) {
                $staff = new StaffController(1, 'test', 'test', 'test@test.com', '12345678909');
                $makeArray = [];
                foreach(['room_number', 'room_type', 'is_available', 'room_service', 'price_per_night'] as $key){
                    $makeArray[$key] = $_POST[$key];    
                }
                //print_r($makeArray);

                $staff->update_room((int)$_POST['room_number'], $makeArray);
                
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
                $staff->add_room((int)$_POST['room_number'], $_POST['room_type'], (bool)$_POST['is_available'], $_POST['room_service'], (float)$_POST['price_per_night']);
                $staff->LogMessage($_SERVER['REMOTE_ADDR'] . ": Staff member added new room");
                $staff->closeConnection();
            } else {
                http_response_code(400);
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