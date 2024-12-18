<?php
session_start(); 

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Retrieve the data from the POST request
        $car_id = $_POST['car_id'];
        $pickup_date = $_POST['pickup_date'];
        $return_date = $_POST['return_date'];
        $total_amount = $_POST['total_rent'];

        // Validate inputs
        if (empty($car_id) || empty($pickup_date) || empty($return_date) || empty($total_amount)) {
            die("Error: Missing required data.");
        }

        // Check if the user is logged in
        if (!isset($_SESSION['user_id'])) {
            die("Error: User not logged in.");
        }

        $user_id = $_SESSION['user_id']; 
        $reservation_date = date("Y-m-d"); 

        // Query the database to insert the reservation
        require_once "utilities.php";
        $conn = getDatabaseConnection();

        // Prepare the INSERT query
        $query = 
        "   INSERT INTO reservation (user_id, car_id, reservation_date, pickup_date, return_date, total_amount) 
            VALUES (:user_id, :car_id, :reservation_date, :pickup_date, :return_date, :total_amount)
        ";

        $stmt = $conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':car_id', $car_id, PDO::PARAM_INT);
        $stmt->bindParam(':reservation_date', $reservation_date);
        $stmt->bindParam(':pickup_date', $pickup_date);
        $stmt->bindParam(':return_date', $return_date);
        $stmt->bindParam(':total_amount', $total_amount, PDO::PARAM_STR);

        // Execute the query
        $stmt->execute();

    
        // Update cars' status to 'Rented' where the pickup date is today
        $query = 
        " UPDATE car 
          SET status = 'Rented'
          WHERE car_id = :car_id
        ";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':car_id', $car_id, PDO::PARAM_INT);
        $stmt->execute();

        // Success message
        echo "Car statuses updated to 'Rented' for pickup date: " . $current_date;
         echo "<script>
                alert('Reservation successfully created.');
                window.location.href = '../user-dashboard.html';
              </script>";

    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    }
} else {
    echo "Error: Invalid request method.";
}
?>
