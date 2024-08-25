<?php
    require_once("./Database/DatabaseClass.php");
    require_once("./Database/Interfaces/IDB_Methods.php");
    require_once('./Database/Migrations/BookingMigration.php');
    class Booking extends Database implements IDB_Booking_Methods {
        private string $booking_table;
        private string $log_file;

        public function __construct()
        {
            // create initial tables and data
            new BookingMigration();
            // initialize parent
            parent::__construct();
            // table name for the database
            $this->booking_table = "booking";
            $this->log_file = "booking-db-log.txt";
        }

        // create new booking
        // the parameter status accepted values are:
        // 'pending', 'approved', 'cancelled'
        public function createElement(string $customer_email, int $room_number, string $check_in, string $check_out, string $status, float $total_price): void
        {
           try {
            $verify = $this->connection->prepare("SELECT room_number FROM $this->booking_table WHERE room_number=?");

            $verify->bind_param("i", $room_number);
            $verify->execute();
            $verify->bind_result($result);
            $verify->fetch();
            
            if ($result) {
                $verify->close();
                http_response_code(400);
                throw new Exception("Booking already exists", 400);
            } else {
                $verify->close();
            }

            $action = $this->connection->prepare("INSERT INTO $this->booking_table (customer_email, room_number, check_in, check_out, status, total_price) VALUES (?,?,?,?,?,?)");

            $action->bind_param("sisssd", $customer_email, $room_number, $check_in, $check_out, $status, $total_price);

            $action->execute();

            if ($action->affected_rows > 0) {
                http_response_code(201);
                parent::logMessage($this->log_file, "New booking added");
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
                
                $result = $this->connection->prepare("SELECT id, customer_email, room_number, check_in, check_out, status, total_price FROM $this->booking_table");

                if ($result->execute()) {
                    $result->bind_result($id, $customer_email, $room_number, $check_in, $check_out, $status, $total_price);
                    while($result->fetch()) {
                        array_push($output, [
                            "id" => $id,
                            "customer_email" => $customer_email, 
                            "room_number" => $room_number, 
                            "check_in" => $check_in,
                            "check_out" => $check_out,
                            "status" => $status,
                            "total_price" => $total_price
                        ]);
                    }
                    $result->close();
                } else {
                    $result->close();
                    http_response_code(500);
                    throw new Exception("Failed to get all bookings", 500);
                }
                parent::logMessage($this->log_file, "Get all bookings accessed");
                http_response_code(200);
                return json_encode($output);
            } catch(Exception $error) {
                parent::logMessage($this->log_file, $error->getMessage() . ", Code: " . $error->getCode());
                throw new Exception("Server Error", 500);
            }
        }

        // find a booking by id number
        public function findElement(int $booking_id): string {
            try {
                $output = [];

                $result = $this->connection->prepare("SELECT id, customer_email, room_number, check_in, check_out, status, total_price FROM $this->booking_table WHERE id = ?");

                $result->bind_param("i", $booking_id);

                $result->execute();

                $result->bind_result($id, $customer_email, $room_number, $check_in, $check_out, $status, $total_price);

                if ($result->fetch()) {
                    $output = [
                        "id" => $id,
                        "customer_email" => $customer_email, 
                        "room_number" => $room_number, 
                        "check_in" => $check_in,
                        "check_out" => $check_out,
                        "status" => $status,
                        "total_price" => $total_price
                    ];
                }

                $result->close();
                http_response_code(200);
                parent::logMessage($this->log_file, "Find booking accessed");
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
        public function updateElement(int $booking_id, array $new_data, string $update_image=null): void {
            try {
                $count = count(array_keys($new_data));
                $arrKeys = array_keys($new_data);
                $types = "";
                $values = [];

                $query = "UPDATE $this->booking_table SET ";

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
                    $action->close();
                    parent::logMessage($this->log_file, "Update Room $room_number Successfull");
                } elseif($action->affected_rows < 0){
                    $action->close();
                    throw new Exception("Update room $room_number: Nothing changed", 204);
                }

                http_response_code(200);
            } catch (Exception $error) {
                parent::logMessage($this->log_file, $error->getMessage() . ", Code: " . $error->getCode());
                http_response_code(500);
            }
        }

        //Delete a booking by it's id number
        public function deleteElement(int $booking_id): void {
            try {
                $result = $this->connection->prepare("DELETE FROM $this->booking_table WHERE room_number = ?");

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

        public function logMessage(string $file_name, string $message): void {
            parent::logMessage($file_name, $message);
        }

        public function close():void {
            parent::close();
        }

    }

?>