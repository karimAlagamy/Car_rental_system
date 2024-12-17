<?php

require_once "utilities.php";

// Connect to the database
$conn = getDatabaseConnection();

try {
    // Retrieve user input
    $office_location = $_POST['location'];
    $pick_up_date = $_POST['pickup_date'];
    $return_date = $_POST['return_date'];

    // NOT REQUIRED
    $make = $_POST['make'];
    $model = $_POST['model'];
    $number_of_seats = $_POST['seats'];
   
} catch (PDOException $e) {
    // Handle any database errors
    echo "Error: " . $e->getMessage();
}
if(empty($make) && empty($model) && empty($number_of_seats)){
    $stmt = $conn->prepare("SELECT c.make, c.model, c.year, c.no_of_seats, c.plate_number 
    FROM car c JOIN reservation r ON c.car_id = r.car_id
    JOIN office o ON c.office_id = o.office_id
    WHERE o.location = :office_location AND ((r.pickup_date > :pick_up_date AND r.pickup_date > :return_date) OR (r.return_date < :pick_up_date))
    ");
    // Bind parameters
    $stmt->bindParam(':office_location', $office_location, PDO::PARAM_STR);
    $stmt->bindParam(':pick_up_date', $pick_up_date, PDO::PARAM_STR);
    $stmt->bindParam(':return_date', $return_date, PDO::PARAM_STR);
    // Execute the statement
    $stmt->execute();

    // Fetch the results and display them in an HTML table
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                    <th>Plate Number</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cars as $car): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($car['make']); ?></td>
                        <td><?php echo htmlspecialchars($car['model']); ?></td>
                        <td><?php echo htmlspecialchars($car['year']); ?></td>
                        <td><?php echo htmlspecialchars($car['no_of_seats']); ?></td>
                        <td><?php echo htmlspecialchars($car['plate_number']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center;">No available cars found for the specified dates and location.</p>
    <?php endif; ?>
</body>
</html>

