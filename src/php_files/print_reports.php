<?php
require_once "utilities.php";

try {
    // Connect to the database
    $conn = getDatabaseConnection();

    // Retrieve form data
    $reportType = $_POST['report-type'] ?? null;
    $startDate = $_POST['start-date'] ?? null;
    $endDate = $_POST['end-date'] ?? null;
    $carId = $_POST['car-id'] ?? null;
    $specificDate = $_POST['specific-date'] ?? null;
    $customerId = $_POST['customer-id'] ?? null;

    // Initialize query and parameters
    $query = "";
    $params = [];

    // Generate SQL query based on report type
    switch ($reportType) {
        case "reservations-period":
            $query = "SELECT r.reservation_id, c.make AS car_make, c.model AS car_model, r.pickup_date, r.return_date, r.total_amount, u.first_name, u.last_name
                      FROM reservation r
                      JOIN car c ON r.car_id = c.car_id
                      JOIN `user` u ON r.user_id = u.user_id
                      WHERE r.pickup_date >= :startDate AND r.return_date <= :endDate";
            $params[':startDate'] = $startDate;
            $params[':endDate'] = $endDate;
            break;

        case "reservations-car":
            $query = "SELECT r.reservation_id, u.first_name, u.last_name, r.pickup_date, r.return_date, r.total_amount
                      FROM reservation r
                      JOIN `user` u ON r.user_id = u.user_id
                      WHERE r.car_id = :carId AND r.pickup_date >= :startDate AND r.return_date <= :endDate";
            $params[':carId'] = $carId;
            $params[':startDate'] = $startDate;
            $params[':endDate'] = $endDate;
            break;

        case "car-status":
            $query = "SELECT c.car_id, c.make, c.model, c.status
                      FROM car c
                      WHERE c.status IN ('Active', 'Rented', 'Out of Service') OR c.last_update_date = :specificDate";
            $params[':specificDate'] = $specificDate;
            break;

       case "customer-reservations":
        $query = "SELECT 
                    u.first_name, 
                    u.last_name, 
                    c.make, 
                    c.model, 
                    c.plate_number, 
                    r.pickup_date, 
                    r.return_date, 
                    r.total_amount
                FROM reservation r
                INNER JOIN car c ON r.car_id = c.car_id
                INNER JOIN `user` u ON r.user_id = u.user_id
                WHERE r.user_id = :customerId";
        $params[':customerId'] = $customerId;
        break;

        case "daily-payments":
            $query = "SELECT DATE(r.pickup_date) AS day, SUM(r.total_amount) AS total_payments
                      FROM reservation r
                      WHERE r.pickup_date >= :startDate AND r.return_date <= :endDate
                      GROUP BY DATE(r.pickup_date)";
            $params[':startDate'] = $startDate;
            $params[':endDate'] = $endDate;
            break;

        default:
            throw new Exception("Invalid report type selected.");
    }

    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    $stmt->execute($params);

    // Fetch the results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Start HTML output
    echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Report</title>
    <link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'>
</head>
<body class='bg-gray-900 text-green-400'>
    <div class='container mx-auto my-10'>
        <h1 class='text-3xl font-bold mb-6'>Generated Report</h1>
        <div class='bg-gray-800 p-6 rounded-lg shadow-lg'>
            <table class='w-full table-auto border-collapse'>
                <thead>
                    <tr>";

    // Output table headers dynamically
    if (!empty($results)) {
        foreach (array_keys($results[0]) as $column) {
            echo "<th class='border border-gray-700 px-4 py-2'>" . htmlspecialchars(ucwords(str_replace('_', ' ', $column))) . "</th>";
        }
    } else {
        echo "<p>No results found for the selected criteria.</p>";
    }

    echo "</tr>
                </thead>
                <tbody>";

    // Output table rows dynamically
    foreach ($results as $row) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td class='border border-gray-700 px-4 py-2 text-center'>" . htmlspecialchars($value) . "</td>";
        }
        echo "</tr>";
    }

    echo "      </tbody>
            </table>
        </div>
    </div>
</body>
</html>";
} catch (PDOException $e) {
    // Handle database errors
    echo "<p class='text-red-400'>Database Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
} catch (Exception $e) {
    // Handle general errors
    echo "<p class='text-red-400'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}
?>
