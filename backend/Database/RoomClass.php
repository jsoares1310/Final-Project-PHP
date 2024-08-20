<?php
    require("./Database/DatabaseClass.php");
    require("./Database/Interfaces/IDB_Methods.php");

    class Room extends Database implements IDB_Room_Methods {
        private string $room_table;

        public function __construct()
        {
            parent::__construct();
            $this->room_table = "rooms";
        }

        public function createElement(int $roomNumber, string $roomType, int $isAvailable, string $roomServices, float $pricePerNight): void
        {
           try {
            $verify = $this->connection->prepare("SELECT room_number FROM $this->room_table WHERE room_number=$roomNumber");

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

            $action->bind_param("isisd", $roomNumber, $roomType, $isAvailable, $roomServices, $pricePerNight);

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
                
                $result->bind_result($roomNumber, $roomType, $isAvailable, $roomServices, $pricePerNight);
                

                if ($result->field_count > 0) {
                    while($result->fetch()) {
                        array_push($output, [ "roomNumber" => $roomNumber, "roomType" => $roomType, "isAvailable" => $isAvailable, "roomServices" => $roomServices, "pricePerNight" => $pricePerNight ]);
                    }
                    $result->close();
                }
                parent::logMessage("rooms-db-log.txt", "Get all rooms acessed");
                return json_encode($output);
            } catch(Exception $error) {
                parent::logMessage("rooms-db-log.txt", $error->getMessage() . ", Code: " . $error->getCode());
                throw new Exception("Server Error", 500);
            }
        }

        public function findElement(int $id): string {
            $output = [];
            return json_encode($output);
        }
        public function updateElement(int $id): void {}
        public function deleteElement(int $id): void {}

        public function close():void {
            parent::close();
        }

    }

?>