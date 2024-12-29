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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto my-10 px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg p-6 border border-gray-300">
            <h1 class="text-3xl font-bold text-center text-blue-600 mb-6">Reservation Details</h1>
            
            <div class="text-center mb-6">
                <!-- Reservation Details -->
                <p class="text-lg">Pick-up Date: <span class="font-bold"><?php echo htmlspecialchars($pickup_date); ?></span></p>
                <p class="text-lg">Return Date: <span class="font-bold"><?php echo htmlspecialchars($return_date); ?></span></p>
                <p class="text-lg">Total Rental Days: <span class="font-bold"><?php echo htmlspecialchars($rental_days); ?></span></p>
                <p class="text-lg">Day Rate: <span class="font-bold">$<?php echo htmlspecialchars($day_rate); ?></span></p>
                <h2 class="text-xl font-bold mt-4">Total Rent: <span class="text-blue-600">$<?php echo htmlspecialchars($total_rent); ?></span></h2>
            </div>

            <!-- Rent Form -->
            <form method="POST" action="confirm_rent.php" class="text-center">
                <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($car_id); ?>">
                <input type="hidden" name="pickup_date" value="<?php echo htmlspecialchars($pickup_date); ?>">
                <input type="hidden" name="return_date" value="<?php echo htmlspecialchars($return_date); ?>">
                <input type="hidden" name="total_rent" value="<?php echo htmlspecialchars($total_rent); ?>">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-md">Confirm Rent</button>
            </form>

            <!-- Back Button -->
            <div class="text-center mt-4">
                <button onclick="window.history.back();" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-6 rounded-md">Back</button>
            </div>
        </div>
    </div>
</body>
</html>
