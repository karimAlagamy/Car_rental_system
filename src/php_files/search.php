<?php

require_once "utilities.php";

// Connect to the database
$conn = getDatabaseConnection();

try {
    // Retrieve user input
    $office_location = '%' . ($_POST['location'] ?? '') . '%'; // Add % for LIKE partial match
    $pick_up_date = $_POST['pickup_date'] ?? null;
    $return_date = $_POST['return_date'] ?? null;

    // Optional filters
    $make = $_POST['make'] ?? null;
    $model = $_POST['model'] ?? null;
    $number_of_seats = $_POST['seats'] ?? null;

    // Ensure required fields are not empty
    if (empty($office_location) || empty($pick_up_date) || empty($return_date)) {
        die("Error: Location, pick-up date, and return date are required.");
    }

    $current_date = new DateTime();
    $pickup_date_obj = new DateTime($pick_up_date);
    $return_date_obj = new DateTime($return_date);

    // Check if the pick-up date is greater than today
    if ($pickup_date_obj < $current_date) {
        echo "<script>
            alert('Pick-up date must after today.');
            window.history.back();
        </script>";
        exit;
    }

    // Check if the return date is greater than pick-up date
    if ($return_date_obj <= $pickup_date_obj) {
        echo "<script>
            alert('Return date must be after the pick-up date.');
            window.history.back();
        </script>";
        exit;
    }

    // Start with the base query
    $query = 
    "   SELECT c.make, c.model, c.year, c.no_of_seats, c.car_id
        FROM car c
        JOIN office o ON c.office_id = o.office_id
        LEFT JOIN reservation r ON c.car_id = r.car_id
            -- Get cars that are not reserved during the specified period
            AND r.pickup_date <= :return_date
            AND r.return_date >= :pick_up_date
        WHERE o.location LIKE :office_location
        -- Get cars that are not reserved in the specified time period
        AND r.car_id IS NULL
    ";

    // Optional conditions array
    $conditions = [];
    $params = [
        ':office_location' => $office_location,
        ':pick_up_date' => $pick_up_date,
        ':return_date' => $return_date
    ];

    if (!empty($make)) {
        $conditions[] = "c.make = :make";
        $params[':make'] = $make;
    }

    if (!empty($model)) {
        $conditions[] = "c.model = :model";
        $params[':model'] = $model;
    }

    if (!empty($number_of_seats)) {
        $conditions[] = "c.no_of_seats = :number_of_seats";
        $params[':number_of_seats'] = $number_of_seats;
    }

    // Append dynamic conditions if any
    if (!empty($conditions)) {
        $query .= " AND " . implode(" AND ", $conditions);
    }

    // Prepare the statement
    $stmt = $conn->prepare($query);

    // Bind all parameters dynamically
    foreach ($params as $param => $value) {
        $stmt->bindValue($param, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }

    // Execute the query
    $stmt->execute();

    // Fetch the results
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Handle database errors
    echo "Database Error: " . $e->getMessage();
    exit;
} catch (Exception $e) {
    // Handle other errors
    echo "Error: " . $e->getMessage();
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Reservation Results</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto my-10 px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-center text-blue-600 mb-6">Available Cars for Reservation</h1>

        <?php if (!empty($cars)): ?>
            <div class="bg-white shadow-lg rounded-lg p-6">
                <table class="w-full table-auto border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-blue-600 text-white">
                            <th class="border border-gray-300 px-4 py-2">Make</th>
                            <th class="border border-gray-300 px-4 py-2">Model</th>
                            <th class="border border-gray-300 px-4 py-2">Year</th>
                            <th class="border border-gray-300 px-4 py-2">No of Seats</th>
                            <th class="border border-gray-300 px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cars as $car): ?>
                            <tr class="hover:bg-gray-100">
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($car['make']); ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($car['model']); ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($car['year']); ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($car['no_of_seats']); ?></td>
                                <td class="border border-gray-300 px-4 py-2 text-center">
                                    <form method="POST" action="rent.php">
                                        <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($car['car_id']); ?>">
                                        <input type="hidden" name="pickup_date" value="<?php echo htmlspecialchars($pick_up_date); ?>">
                                        <input type="hidden" name="return_date" value="<?php echo htmlspecialchars($return_date); ?>">
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-1 px-4 rounded-md">Reserve</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center text-lg mt-6 text-gray-600">No available cars found for the specified filters.</p>
        <?php endif; ?>

        <!-- Back Button -->
        <div class="flex justify-center mt-6">
            <button onclick="window.history.back();" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-6 rounded-md">Back</button>
        </div>
    </div>
</body>
</html>
