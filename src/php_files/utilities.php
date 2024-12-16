<?php
// Database Connection
function getDatabaseConnection() {
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Car_rental_system";

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Insert Data into Office
function insertOffice($officeName, $location) {
    $conn = getDatabaseConnection();
    $sql = "INSERT INTO office (office_name, location) VALUES (:office_name, :location)";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute(['office_name' => $officeName, 'location' => $location]);
        echo "New office inserted successfully!<br>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Insert Data into Customer
function insertCustomer($firstName, $lastName, $email, $phoneNumber, $address, $username, $password) {
    $conn = getDatabaseConnection();
    $sql = "INSERT INTO customer (first_name, last_name, email, phone_number, address, username, password) 
            VALUES (:first_name, :last_name, :email, :phone_number, :address, :username, :password)";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone_number' => $phoneNumber,
            'address' => $address,
            'username' => $username,
            'password' => password_hash($password, PASSWORD_BCRYPT) // Secure hashed password
        ]);
        echo "New customer inserted successfully!<br>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch Cars by Office
function getCarsByOffice($officeId) {
    $conn = getDatabaseConnection();
    $sql = "SELECT * FROM car WHERE office_id = :office_id";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute(['office_id' => $officeId]);
        $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $cars;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Make a Reservation
function makeReservation($customerId, $carId, $reservationDate, $pickupDate, $returnDate, $totalAmount) {
    $conn = getDatabaseConnection();
    $sql = "INSERT INTO reservation (customer_id, car_id, reservation_date, pickup_date, return_date, total_amount) 
            VALUES (:customer_id, :car_id, :reservation_date, :pickup_date, :return_date, :total_amount)";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'customer_id' => $customerId,
            'car_id' => $carId,
            'reservation_date' => $reservationDate,
            'pickup_date' => $pickupDate,
            'return_date' => $returnDate,
            'total_amount' => $totalAmount
        ]);
        echo "Reservation created successfully!<br>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Process Payment
function processPayment($reservationId, $amount, $paymentDate) {
    $conn = getDatabaseConnection();
    $sql = "INSERT INTO payment (reservation_id, amount, payment_date) 
            VALUES (:reservation_id, :amount, :payment_date)";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'reservation_id' => $reservationId,
            'amount' => $amount,
            'payment_date' => $paymentDate
        ]);
        echo "Payment processed successfully!<br>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch Reservations by Customer
function getReservationsByCustomer($customerId) {
    $conn = getDatabaseConnection();
    $sql = "SELECT * FROM reservation WHERE customer_id = :customer_id";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute(['customer_id' => $customerId]);
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $reservations;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// add search results handling for search.html

?>