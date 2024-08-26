<?php
// Always make sure to use only require_once instead of require
require_once("./Database/Migrations/Migrations.php");
new Migrations();
$uri = $_SERVER['REQUEST_URI'];
print_r($uri);
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

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
        break;
}

?>