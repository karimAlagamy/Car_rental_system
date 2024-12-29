<?php
require_once "utilities.php";

try {
    // Connect to the database
    $conn = getDatabaseConnection();

    // Retrieve and sanitize input values
    $reservationId = $_POST['reservation_id'] ?? null;
    $carId = $_POST['car_id'] ?? null;
    $customerId = $_POST['customer_id'] ?? null;
    $carMake = $_POST['car_make'] ?? null;
    $carModel = $_POST['car_model'] ?? null;
    $officeLocation = $_POST['office_location'] ?? null;
    $pickupDate = $_POST['pickup_date'] ?? null;
    $returnDate = $_POST['return_date'] ?? null;

    // Start the base query
    $query = "
        SELECT 
            r.reservation_id, 
            r.pickup_date, 
            r.return_date, 
            r.total_amount, 

            -- Customer details
            u.user_id AS customer_id, 
            u.first_name AS customer_first_name, 
            u.last_name AS customer_last_name, 
            u.email AS customer_email, 
            u.phone_number AS customer_phone, 

            -- Car details
            c.car_id, 
            c.make AS car_make, 
            c.model AS car_model, 
            c.plate_number, 
            c.no_of_seats, 
            c.year AS car_year, 
            c.status AS car_status, 
            c.day_rate, 

            -- Office details
            o.office_id, 
            o.office_name, 
            o.location AS office_location
        FROM 
            reservation r
        LEFT JOIN 
            car c ON r.car_id = c.car_id
        LEFT JOIN 
            `user` u ON r.user_id = u.user_id
        LEFT JOIN 
            office o ON c.office_id = o.office_id
    ";

    // Initialize conditions and parameters
    $conditions = [];
    $params = [];

    // Add filters based on input
    if (!empty($reservationId)) {
        $conditions[] = "r.reservation_id = :reservationId";
        $params[':reservationId'] = $reservationId;
    }
    if (!empty($carId)) {
        $conditions[] = "c.car_id = :carId";
        $params[':carId'] = $carId;
    }
    if (!empty($customerId)) {
        $conditions[] = "u.user_id = :customerId";
        $params[':customerId'] = $customerId;
    }
    if (!empty($carMake)) {
        $conditions[] = "c.make = :carMake";
        $params[':carMake'] = $carMake;
    }
    if (!empty($carModel)) {
        $conditions[] = "c.model = :carModel";
        $params[':carModel'] = $carModel;
    }
    if (!empty($officeLocation)) {
        $conditions[] = "o.location LIKE :officeLocation";
        $params[':officeLocation'] = "%$officeLocation%";
    }
    if (!empty($pickupDate)) {
        $conditions[] = "r.pickup_date >= :pickupDate";
        $params[':pickupDate'] = $pickupDate;
    }
    if (!empty($returnDate)) {
        $conditions[] = "r.return_date <= :returnDate";
        $params[':returnDate'] = $returnDate;
    }

    // Append conditions to the query if provided
    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    } else {
        // Throw an exception if no filters are provided
        throw new Exception("Please provide at least one filter to search.");
    }

    // Add ordering for consistent display
    $query .= " ORDER BY r.reservation_id";

    // Debugging the query and parameters (optional)
    // error_log("Query: $query");
    // error_log("Parameters: " . print_r($params, true));

    // Prepare and execute query
    $stmt = $conn->prepare($query);
    $stmt->execute($params);

    // Fetch all results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Generate HTML for results
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Filtered Results</title>
        <link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'>
    </head>
    <body class='bg-gray-900 text-green-400'>
        <div class='container mx-auto my-10'>
            <h1 class='text-3xl font-bold mb-6 text-center'>Filtered Results</h1>
            <div class='bg-gray-800 p-6 rounded-lg shadow-lg'>";

    if (!empty($results)) {
        echo "<table class='w-full table-auto border-collapse'>
                <thead>
                    <tr>
                        <th class='border border-gray-700 px-4 py-2'>Reservation ID</th>
                        <th class='border border-gray-700 px-4 py-2'>Customer Name</th>
                        <th class='border border-gray-700 px-4 py-2'>Customer Email</th>
                        <th class='border border-gray-700 px-4 py-2'>Car Make & Model</th>
                        <th class='border border-gray-700 px-4 py-2'>Plate Number</th>
                        <th class='border border-gray-700 px-4 py-2'>Pickup Date</th>
                        <th class='border border-gray-700 px-4 py-2'>Return Date</th>
                        <th class='border border-gray-700 px-4 py-2'>Total Amount</th>
                        <th class='border border-gray-700 px-4 py-2'>Office Location</th>
                    </tr>
                </thead>
                <tbody>";

        foreach ($results as $row) {
            echo "<tr class='hover:bg-gray-700'>
                    <td class='border border-gray-700 px-4 py-2 text-center'>" . htmlspecialchars($row['reservation_id']) . "</td>
                    <td class='border border-gray-700 px-4 py-2 text-center'>" . htmlspecialchars($row['customer_first_name'] . ' ' . $row['customer_last_name']) . "</td>
                    <td class='border border-gray-700 px-4 py-2 text-center'>" . htmlspecialchars($row['customer_email']) . "</td>
                    <td class='border border-gray-700 px-4 py-2 text-center'>" . htmlspecialchars($row['car_make'] . ' ' . $row['car_model']) . "</td>
                    <td class='border border-gray-700 px-4 py-2 text-center'>" . htmlspecialchars($row['plate_number']) . "</td>
                    <td class='border border-gray-700 px-4 py-2 text-center'>" . htmlspecialchars($row['pickup_date']) . "</td>
                    <td class='border border-gray-700 px-4 py-2 text-center'>" . htmlspecialchars($row['return_date']) . "</td>
                    <td class='border border-gray-700 px-4 py-2 text-center'>" . htmlspecialchars($row['total_amount']) . "</td>
                    <td class='border border-gray-700 px-4 py-2 text-center'>" . htmlspecialchars($row['office_location']) . "</td>
                </tr>";
        }

        echo "</tbody>
            </table>";
    } else {
        echo "<p class='text-center text-lg text-red-400'>No matching records found.</p>";
    }

    echo "</div>
        </div>
    </body>
    </html>";

} catch (PDOException $e) {
    echo "<p class='text-red-400'>Database Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
} catch (Exception $e) {
    echo "<p class='text-red-400'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}
?>
