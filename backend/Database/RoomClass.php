<?php
    require("./Database/DatabaseClass.php");
    require("./Database/Interfaces/IDB_Methods.php");
    require('./Database/Migrations/RoomsMigrations.php');
    class Room extends Database implements IDB_Room_Methods {
        private string $room_table;

        public function __construct()
        {
            // create initial tables and data
            new RoomsMigrations();
            // initialize parent
            parent::__construct();
            // table name for the database
            $this->room_table = "rooms";
        }

        // create new room
        // the parameter isAvailable is typed as int but the values
        // that it accepts is only 0 or 1
        public function createElement(int $room_number, string $room_type, int $is_available, string $room_services, float $price_per_night): void
        {
           try {
            $verify = $this->connection->prepare("SELECT room_number FROM $this->room_table WHERE room_number=$room_number");

            $verify->execute();
            $verify->bind_result($r);
            $verify->fetch();
            
            if ($r) {
                $verify->close();
                throw new Exception("Room already exists", 406);
            } else {
                $verify->close();
            }

            $action = $this->connection->prepare("INSERT INTO $this->room_table (room_number, room_type, is_available, room_service, price_per_night) VALUES (?,?,?,?,?)");

            $action->bind_param("isisd", $room_number, $room_type, $is_available, $room_services, $price_per_night);

            $action->execute();

            if ($action->affected_rows > 0) {
                http_response_code(201);
                parent::logMessage("rooms-db-log.txt", "New Room Added");
            }
            $action->close();
           } catch (Exception $error) {
                parent::logMessage("rooms-db-log.txt", $error->getMessage() . ", Code: " . $error->getCode());
           }

        }

        public function getAll(): string {
            try {
                $result = null;
                $output = [];
                
                $result = $this->connection->prepare("SELECT room_number, room_type, is_available, room_service, price_per_night FROM $this->room_table");

                $result->execute();
                
                $result->bind_result($room_number, $room_type, $is_available, $room_services, $price_per_night);
                

                if ($result->field_count > 0) {
                    while($result->fetch()) {
                        array_push($output, [ "roomNumber" => $room_number, "roomType" => $room_type, "isAvailable" => $is_available, "roomServices" => $room_services, "pricePerNight" => $price_per_night ]);
                    }
                    $result->close();
                }
                parent::logMessage("rooms-db-log.txt", "Get all rooms accessed");
                return json_encode($output);
            } catch(Exception $error) {
                parent::logMessage("rooms-db-log.txt", $error->getMessage() . ", Code: " . $error->getCode());
                throw new Exception("Server Error", 500);
            }
        }

        // find room by room number
        public function findElement(int $room_number): string {
            try {
                $output = [];

                $result = $this->connection->prepare("SELECT room_number, room_type, is_available, room_service, price_per_night FROM $this->room_table WHERE room_number = ?");

                $result->bind_param("i", $room_number);

                $result->execute();

                $result->bind_result($froom_number, $room_type, $is_available, $room_services, $price_per_night);

                if ($result->fetch()) {
                    $output = ["roomNumber" => $froom_number, "roomType" => $room_type, "isAvailable" => $is_available, "roomServices" => $room_services, "pricePerNight" => $price_per_night];

                    parent::logMessage("rooms-db-log.txt", "Find Room Accessed");
                }

                $result->close();
                
                return json_encode($output);
            } catch(Exception $error) {
                parent::logMessage("rooms-db-log.txt", $error->getMessage() . ", Code: " . $error->getCode());
            }
        }
        
        public function updateElement(int $room_number): void {}
        public function deleteElement(int $room_number): void {}

        public function close():void {
            parent::close();
        }

    }

?>