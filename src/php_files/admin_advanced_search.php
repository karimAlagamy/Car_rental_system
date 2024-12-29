<?php

require_once "utilities.php";

// Connect to the database
$conn = getDatabaseConnection();

try {
    // Retrieve user input
    $res_date = $_POST['reservation_date'] ?? null;
    $car_id = $_POST['car_id'] ?? null;
    $cust_id = $_POST['customer_id'] ?? null;

    // Count non-empty inputs
    $non_empty_inputs = array_filter([$res_date, $car_id, $cust_id]);

    // Ensure only one input is provided
    if (count($non_empty_inputs) !== 1) {
        echo "<script>
            alert('Please provide only one filter(Car ID, Customer ID, or Reservation Date)');
            window.history.back();
            </script>";
    }

    // Base query and dynamic variables
    $query = "";
    $params = [];

    // Adjust the query based on the provided filter
    if (!empty($car_id)) {
        // Display all reservations for the specified car
        $query = 
        "   SELECT 
                u.first_name, 
                u.last_name, 
                r.pickup_date, 
                r.return_date, 
                r.total_amount
            FROM reservation r
            JOIN user u ON r.user_id = u.user_id
            WHERE r.car_id = :car_id
        ";
        $params[':car_id'] = $car_id;

    } elseif (!empty($cust_id)) {
        // Display all cars reserved by the specified customer
        $query = 
        "   SELECT 
                c.make, 
                c.model, 
                c.plate_number, 
                c.year, 
                r.pickup_date, 
                r.return_date
            FROM reservation r
            JOIN car c ON r.car_id = c.car_id
            WHERE r.user_id = :user_id
        ";
        $params[':user_id'] = $cust_id;

    } elseif (!empty($res_date)) {
        // Display all cars reserved on the specified date
        $query = 
        "   SELECT 
                c.make, 
                c.model, 
                c.plate_number, 
                c.year, 
                u.first_name, 
                u.last_name, 
                r.pickup_date, 
                r.return_date
            FROM reservation r
            JOIN car c ON r.car_id = c.car_id
            JOIN user u ON r.user_id = u.user_id
            WHERE r.reservation_date = :reservation_date
        ";
        $params[':reservation_date'] = $res_date;
    }

    // Prepare the statement
    $stmt = $conn->prepare($query);

    // Bind parameters
    foreach ($params as $param => $value) {
        $stmt->bindValue($param, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }

    // Execute the query
    $stmt->execute();

    // Fetch the results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Query Results</title>
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
    <h1 style="text-align: center;">Query Results</h1>

    <?php if (!empty($results)): ?>
        <table>
            <thead>
                <tr>
                    <?php if (!empty($car_id)): ?>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Pick-Up Date</th>
                        <th>Return Date</th>
                        <th>Total Amount</th>
                    <?php elseif (!empty($cust_id)): ?>
                        <th>Make</th>
                        <th>Model</th>
                        <th>Plate Number</th>
                        <th>Year</th>
                        <th>Pick-Up Date</th>
                        <th>Return Date</th>
                    <?php elseif (!empty($res_date)): ?>
                        <th>Make</th>
                        <th>Model</th>
                        <th>Plate Number</th>
                        <th>Year</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Pick-Up Date</th>
                        <th>Return Date</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $result): ?>
                    <tr>
                        <?php foreach ($result as $key => $value): ?>
                            <td><?php echo htmlspecialchars($value); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center;">No results found for the specified filter.</p>
    <?php endif; ?>

    <!-- Back Button -->
    <button class="back-button" onclick="window.history.back();">Back</button>
</body>
</html>
