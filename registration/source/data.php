<?php

//Class that uses singleton pattern to instatiate a single database connection
//for use anywhere in the file. All database processes are handled here
class Connection {

    private static $conn = null;

    private $connection = null;

    private function __construct() {
        $servername = "localhost";
        $username = "root";
        $password = "s0meThingToR3member";
        $dbname = "cs435p3";

        try {
            $this->connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // set the PDO error mode to exception
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage(); 
        }
    }

    public static function getConnection() {
        if (self::$conn === null) {
            self::$conn = new self();
        }
        return self::$conn->connection;
    }

}

    //gets presentation slots
    function GetSlots() {
        $db = Connection::getConnection();
        $query = "SELECT * FROM presentation_slots ORDER BY id";
        $statement = $db->prepare($query);
        $statement->execute();
        $presentSlots = $statement->fetchAll();
        $statement->closeCursor();

        return $presentSlots;
    }

    //Checks if UMID entered is already registered in the system
    function CheckIdUnique($umid) {
        $db = Connection::getConnection();
        $query = "SELECT * FROM students WHERE umid = :umId";
        $statement = $db->prepare($query);
        $statement->bindValue(':umId', $umid);
        $statement->execute();
        if ($statement->rowCount() == 0) {
            $statement->closeCursor();
            return true;
        }
        else {
            $statement->closeCursor();
            return false;
        }
    }

    //adds a new registrant 
    function AddRegistrant($umid, $firstName, $lastName, $projectTitle, $email, $phoneNumber, $slotId, $selectedDate) {
        $insertDb = Connection::getConnection();
        $query = "INSERT INTO students (umid, first_name, last_name, project_title, email, phone_number, time_slot, presentation_date)
                  VALUES (:umid, :firstName, :lastName, :projectTitle, :email, :phoneNumber, :timeSlot, :presentationDate)";
        $statement = $insertDb->prepare($query);
        $statement->bindValue(':umid', $umid);
        $statement->bindValue(':firstName', $firstName);
        $statement->bindValue(':lastName', $lastName);
        $statement->bindValue(':projectTitle', $projectTitle);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':phoneNumber', $phoneNumber);
        $statement->bindValue(':timeSlot', $slotId);
        $statement->bindValue(':presentationDate', $selectedDate);
        $statement->execute();
        $statement->closeCursor();

        $query = "UPDATE presentation_slots
                  SET slots_left = slots_left - 1
                  WHERE id = :slotId AND slots_left > 0";
        $statement = $insertDb->prepare($query);
        $statement->bindValue(':slotId', $slotId);
        $statement->execute();
        $statement->closeCursor();
    }

    //changes a students registration 
    function ChangeRegistration($umid, $firstName, $lastName, $projectTitle, $email, $phoneNumber, $slotId, $selectedDate) {
        $insertDb = Connection::getConnection();

        //Fetch old reigstration slot
        $query = "SELECT * FROM students WHERE umid = :umId";
        $statement = $insertDb->prepare($query);
        $statement->bindValue(':umId', $umid);
        $statement->execute();
        $result = $statement->fetch();
        $oldSlot = $result["time_slot"];
        $statement->closeCursor();

        //Delete old registration slot
        $query = "DELETE FROM students WHERE umid = :umid";
        $statement = $insertDb->prepare($query);
        $statement->bindValue(':umid', $umid);
        $statement->execute();
        $statement->closeCursor();

        //Update remaining seats for previous slot
        $query = "UPDATE presentation_slots
                  SET slots_left = slots_left + 1
                  WHERE id = :slotId AND slots_left < 6";
        $statement = $insertDb->prepare($query);
        $statement->bindValue(':slotId', $oldSlot);
        $statement->execute();
        $statement->closeCursor();

        //Register student for new slot
        $query = "INSERT INTO students (umid, first_name, last_name, project_title, email, phone_number, time_slot, presentation_date)
                  VALUES (:umid, :firstName, :lastName, :projectTitle, :email, :phoneNumber, :timeSlot, :presentationDate)";
        $statement = $insertDb->prepare($query);
        $statement->bindValue(':umid', $umid);
        $statement->bindValue(':firstName', $firstName);
        $statement->bindValue(':lastName', $lastName);
        $statement->bindValue(':projectTitle', $projectTitle);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':phoneNumber', $phoneNumber);
        $statement->bindValue(':timeSlot', $slotId);
        $statement->bindValue(':presentationDate', $selectedDate);
        $statement->execute();
        $statement->closeCursor();

        //Update remaining seats for new slot
        $query = "UPDATE presentation_slots
                  SET slots_left = slots_left - 1
                  WHERE id = :slotId AND slots_left > 0";
        $statement = $insertDb->prepare($query);
        $statement->bindValue(':slotId', $slotId);
        $statement->execute();
        $statement->closeCursor();
    }

    //gets all students registered for a presentation
    function GetRegistrants() {
        $db = Connection::getConnection();

        $query = "SELECT * FROM students ORDER BY time_slot";
        $statement = $db->prepare($query);
        $statement->execute();
        $registeredSlots = $statement->fetchAll();
        $statement->closeCursor();

        return $registeredSlots;
    }
?>