<?php
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the data from the POST request
    $car_id = $_POST['car_id'];
    $pickup_date = $_POST['pickup_date'];
    $return_date = $_POST['return_date'];

    // Validate inputs
    if (empty($car_id) || empty($pickup_date) || empty($return_date)) {
        echo "Error: Missing required data.";
        exit;
    }

    $pickup_date_obj = new DateTime($pickup_date);
    $return_date_obj = new DateTime($return_date);

    // Check if return date is after pickup date
    if ($return_date_obj <= $pickup_date_obj) {
        echo "Error: Return date must be after the pick-up date.";
        exit;
    }

    // Calculate rental days
    $interval = $pickup_date_obj->diff($return_date_obj);
    $rental_days = $interval->days;


    // Query the database to calculate the rent
   
    require_once "utilities.php";
    $conn = getDatabaseConnection();

    try {
        $query = "SELECT day_rate FROM car WHERE car_id = :car_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':car_id', $car_id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $day_rate = $result['day_rate'];
            $total_rent = $day_rate * $rental_days;

        } else {
            echo "Error: Car not found.";
        }
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    }

} else {
    echo "Error: Invalid request method.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Details</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1, h2, p { text-align: center; }
        .container { max-width: 600px; margin: 0 auto; text-align: center; }
        button, form { margin-top: 10px; }
        button { padding: 8px 16px; cursor: pointer; border: none; color: #fff; }
        .rent-btn { background-color: green; }
        .back-btn { background-color: gray; }
    </style>
</head>
<body>
    <div class="container">

            <h1>Reservation Details</h1>
            <!-- <p>Car ID: <?php echo htmlspecialchars($car_id); ?></p>  -->
            <p>Pick-up Date: <?php echo htmlspecialchars($pickup_date); ?></p>
            <p>Return Date: <?php echo htmlspecialchars($return_date); ?></p>
            <p>Total Rental Days: <?php echo htmlspecialchars($rental_days); ?></p>
            <p>Day Rate: <?php echo htmlspecialchars($day_rate); ?></p>
            <h2>Total Rent: <?php echo htmlspecialchars($total_rent); ?></h2>

            <!-- Rent Form -->
            <form method="POST" action="confirm_rent.php">
                <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($car_id); ?>">
                <input type="hidden" name="pickup_date" value="<?php echo htmlspecialchars($pickup_date); ?>">
                <input type="hidden" name="return_date" value="<?php echo htmlspecialchars($return_date); ?>">
                <input type="hidden" name="total_rent" value="<?php echo htmlspecialchars($total_rent); ?>">
                <button type="submit" class="rent-btn">Confirm Rent</button>
            </form>

            <!-- Back Button -->
            <button onclick="window.history.back();" class="back-btn">Back</button>
    </div>
</body>
</html>