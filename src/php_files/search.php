<?php

require_once "utilities.php";

// Connect to the database
$conn = getDatabaseConnection();

try {
    // Retrieve user input
    $office_location = '%' . $_POST['location'] . '%' ?? null; // Add % for LIKE partial match
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

    // Start with the base query
    $query = 
    "   SELECT c.make, c.model, c.year, c.no_of_seats 
        FROM car c
        JOIN office o ON c.office_id = o.office_id
        LEFT JOIN reservation r ON c.car_id = r.car_id
            -- Get cars that are not reserved during the specified period
            AND r.pickup_date <= :return_date
            AND r.return_date >= :pick_up_date
        WHERE o.location LIKE :office_location
        -- Get cars that are not reserved in the specified time period
        AND r.car_id IS NULL
        AND c.status = 'active'
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
    <style>
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        button {
            padding: 6px 12px;
            border: none;
            background-color: #007BFF;
            color: white;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.2s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .back-button {
            display: block;
            margin: 20px auto;
            width: fit-content;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Available Cars for Reservation</h1>

    <?php if (!empty($cars)): ?>
        <table>
            <thead>
                <tr>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>No of Seats</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cars as $car): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($car['make']); ?></td>
                        <td><?php echo htmlspecialchars($car['model']); ?></td>
                        <td><?php echo htmlspecialchars($car['year']); ?></td>
                        <td><?php echo htmlspecialchars($car['no_of_seats']); ?></td>
                        <td>
                            <form method="POST" action="select_car.php">
                                <input type="hidden" name="car_id" >
                                <button type="submit">Reserve</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center;">No available cars found for the specified filters.</p>
    <?php endif; ?>

    <!-- Back Button -->
    <button class="back-button" onclick="window.history.back();">Back</button>
</body>
</html>
