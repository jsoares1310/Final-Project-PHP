<?php
    require("./Database/DatabaseClass.php");
    require("./Database/Interfaces/IDB_Methods.php");
    require('./Database/Migrations/RoomsMigrations.php');
    class Room extends Database implements IDB_Room_Methods {
        private string $room_table;
        private string $log_file;

        public function __construct()
        {
            // create initial tables and data
            new RoomsMigrations();
            // initialize parent
            parent::__construct();
            // table name for the database
            $this->room_table = "rooms";
            $this->log_file = "rooms-db-log.txt";
        }

        // create new room
        // the parameter isAvailable is typed as int but the values
        // that it accepts is only 0 or 1
        public function createElement(int $room_number, string $room_type, int $is_available, string $room_services, float $price_per_night): void
        {
           try {
            $verify = $this->connection->prepare("SELECT room_number FROM $this->room_table WHERE room_number=?");

            $verify->bind_param("i", $room_number);
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
                parent::logMessage($this->log_file, "New Room Added");
            }
            $action->close();
           } catch (Exception $error) {
                parent::logMessage($this->log_file, $error->getMessage() . ", Code: " . $error->getCode());
           }

        }

        public function getAll(): string {
            try {
                $result = null;
                $output = [];
                
                $result = $this->connection->prepare("SELECT room_number, room_type, is_available, room_service, price_per_night FROM $this->room_table");

                if ($result->execute()) {
                    $result->bind_result($room_number, $room_type, $is_available, $room_services, $price_per_night);
                    while($result->fetch()) {
                        array_push($output, [ "roomNumber" => $room_number, "roomType" => $room_type, "isAvailable" => $is_available, "roomServices" => $room_services, "pricePerNight" => $price_per_night ]);
                    }
                    $result->close();
                } else {
                    $result->close();
                    throw new Exception("Failed to get all rooms", 500);
                }
                parent::logMessage($this->log_file, "Get all rooms accessed");
                return json_encode($output);
            } catch(Exception $error) {
                parent::logMessage($this->log_file, $error->getMessage() . ", Code: " . $error->getCode());
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

                    parent::logMessage($this->log_file, "Find Room Accessed");
                }

                $result->close();
                
                return json_encode($output);
            } catch(Exception $error) {
                parent::logMessage($this->log_file, $error->getMessage() . ", Code: " . $error->getCode());
            }
        }

        // update Room accordingly to new_data array
        // new_data array should be an associate array
        // with key names being the name of the columns
        // e.g [
        //     "room_number" => 101,
        //     "room_type" => "double",
        //     "is_available" => true,
        //     "room_services" => "WiFi, TV",
        //     "price_per_night" => 120.50
        // ];
        public function updateElement(int $room_number, array $new_data): void {
            try {
                $count = count(array_keys($new_data));
                $arrKeys = array_keys($new_data);
                $types = "";
                $values = [];

                $query = "UPDATE $this->room_table SET ";

                for($i=0; $i < $count-1; $i++) {
                        $key = $arrKeys[$i];
                        $types .= gettype($new_data[$key])[0]; 
                        
                        array_push($values, $new_data[$key]);
                        $query .= "$key = ?, ";
                }

                $key = $arrKeys[$count-1];
                $types .= gettype($new_data[$key])[0];
                array_push($values, $new_data[$key]);
                $query .= "$key = ? ";
                $query .= "WHERE room_number = ?";
                array_push($values, $room_number);
                $types .= "i";

                $action = $this->connection->prepare($query);

                $action->bind_param($types, ...$values);

                $action->execute();


                if ($action->affected_rows > 0) {
                    http_response_code(200);
                    $action->close();
                    parent::logMessage($this->log_file, "Update Room $room_number Successfull");
                } elseif($action->affected_rows < 0){
                    $action->close();
                    throw new Exception("Update room $room_number: Nothing changed", 204);
                }

            } catch (Exception $error) {
                parent::logMessage($this->log_file, $error->getMessage() . ", Code: " . $error->getCode());
                http_response_code(500);
            }
        }

        //Delete a room by it's number
        public function deleteElement(int $room_number): void {
            try {
                $result = $this->connection->prepare("DELETE FROM $this->room_table WHERE room_number = ?");

                $result->bind_param("i", $room_number);

                $result->execute();

                if ($result->affected_rows > 0) {
                    http_response_code(200);
                    parent::logMessage($this->log_file, "Room $room_number was deleted");
                } else {
                    $result->close();
                    throw new Exception("Room couldn't be deleted", 400);
                }

                $result->close();
                return;
            } catch(Exception $error) {
                parent::logMessage($this->log_file, $error->getMessage() . ", Code: " . $error->getCode());
            }
        }

        public function close():void {
            parent::close();
        }

    }

?>