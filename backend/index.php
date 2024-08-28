<?php
// Always make sure to use only require_once instead of require
require_once("./Database/Migrations/Migrations.php");
require_once("./Classes/StaffClass.php");
require_once("./Classes/AdminClass.php");
new Migrations();
$uri = $_SERVER['REQUEST_URI'];
print_r($uri . "\n");
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

    case '/staff/delete/room':
        try {
            if (isset($_POST['room_number'])) {
                $staff = new StaffController(1, 'test', 'test', 'test@test.com', '12345678909');
                $room_n = (int)$_POST['room_number'];
                $staff->del_room($room_n);
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