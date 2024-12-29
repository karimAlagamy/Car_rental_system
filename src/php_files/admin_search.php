<?php

require_once "utilities.php";

// Connect to the database
$conn = getDatabaseConnection();

try {
    // Step 1: Retrieve user input
    $filters = [
        'make' => $_POST['make'] ?? null,
        'model' => $_POST['model'] ?? null,
        'day_rate' => $_POST['day_rate'] ?? null,
        'plate_number' => $_POST['plate_number'] ?? null,
        'car_id' => $_POST['car_id'] ?? null,
        'office_id' => $_POST['office_id'] ?? null,
        'status' => $_POST['status'] ?? null,
        'no_of_seats' => $_POST['no_of_seats'] ?? null,
    ];

    // Step 1: Build the dynamic query to filter cars
    $carQuery = "SELECT car_id, make, model, plate_number, year, day_rate, status, no_of_seats, office_id FROM car WHERE 1=1";
    $carParams = [];

    foreach ($filters as $key => $value) {
        if (!empty($value)) {
            $carQuery .= " AND $key = :$key";
            $carParams[":$key"] = $value;
        }
    }

    $carStmt = $conn->prepare($carQuery);
    $carStmt->execute($carParams);
    $cars = $carStmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cars)) {
        echo "<script>
            alert('No cars found for the given filters.');
            window.history.back();
            </script>";
        exit;
    }

    // Step 2: Get reservations for the found cars
    $carIds = array_column($cars, 'car_id');
    $reservationQuery = "
        SELECT 
            reservation_id, 
            car_id, 
            pickup_date, 
            return_date, 
            total_amount 
        FROM reservation 
        WHERE car_id IN (" . implode(',', array_fill(0, count($carIds), '?')) . ")
    ";
    $reservationStmt = $conn->prepare($reservationQuery);
    $reservationStmt->execute($carIds);
    $reservations = $reservationStmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($reservations)) {
        echo "<script>
            alert('No reservations found for the selected cars.');
            window.history.back();
            </script>";
        exit;
    }

    // Step 3: Get customer details for the reservations
    $reservationIds = array_column($reservations, 'reservation_id');
    $customerQuery = "
        SELECT 
            r.reservation_id, 
            u.user_id AS customer_id, 
            u.first_name, 
            u.last_name, 
            u.email, 
            u.phone_number, 
            r.pickup_date, 
            r.return_date, 
            r.total_amount 
        FROM reservation r
        JOIN `user` u ON r.user_id = u.user_id
        WHERE r.reservation_id IN (" . implode(',', array_fill(0, count($reservationIds), '?')) . ")
    ";
    $customerStmt = $conn->prepare($customerQuery);
    $customerStmt->execute($reservationIds);
    $customers = $customerStmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($customers)) {
        echo "<script>
            alert('No customers found for the reservations.');
            window.history.back();
            </script>";
        exit;
    }
} catch (PDOException $e) {
    echo "Database Error: " . htmlspecialchars($e->getMessage());
    exit;
} catch (Exception $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piped Query Results</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-green-400">
    <main class="container mx-auto my-10 px-6 lg:px-8">
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold text-green-400 mb-6">Cars Matching Your Filters</h1>
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-700 text-green-300">
                        <th class="border border-gray-700 px-4 py-2">Car ID</th>
                        <th class="border border-gray-700 px-4 py-2">Make</th>
                        <th class="border border-gray-700 px-4 py-2">Model</th>
                        <th class="border border-gray-700 px-4 py-2">Plate Number</th>
                        <th class="border border-gray-700 px-4 py-2">Year</th>
                        <th class="border border-gray-700 px-4 py-2">Day Rate</th>
                        <th class="border border-gray-700 px-4 py-2">Status</th>
                        <th class="border border-gray-700 px-4 py-2">No. of Seats</th>
                        <th class="border border-gray-700 px-4 py-2">Office ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cars as $car): ?>
                        <tr class="hover:bg-gray-700 text-center">
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($car['car_id']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($car['make']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($car['model']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($car['plate_number']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($car['year']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($car['day_rate']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($car['status']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($car['no_of_seats']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($car['office_id']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="bg-gray-800 p-6 rounded-lg shadow-lg mt-8">
            <h1 class="text-2xl font-bold text-green-400 mb-6">Reservations for Selected Cars</h1>
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-700 text-green-300">
                        <th class="border border-gray-700 px-4 py-2">Reservation ID</th>
                        <th class="border border-gray-700 px-4 py-2">Car ID</th>
                        <th class="border border-gray-700 px-4 py-2">Pickup Date</th>
                        <th class="border border-gray-700 px-4 py-2">Return Date</th>
                        <th class="border border-gray-700 px-4 py-2">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                        <tr class="hover:bg-gray-700 text-center">
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($reservation['reservation_id']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($reservation['car_id']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($reservation['pickup_date']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($reservation['return_date']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($reservation['total_amount']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="bg-gray-800 p-6 rounded-lg shadow-lg mt-8">
            <h1 class="text-2xl font-bold text-green-400 mb-6">Customer Details for Reservations</h1>
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-700 text-green-300">
                        <th class="border border-gray-700 px-4 py-2">Reservation ID</th>
                        <th class="border border-gray-700 px-4 py-2">Customer ID</th>
                        <th class="border border-gray-700 px-4 py-2">Name</th>
                        <th class="border border-gray-700 px-4 py-2">Email</th>
                        <th class="border border-gray-700 px-4 py-2">Phone</th>
                        <th class="border border-gray-700 px-4 py-2">Pickup Date</th>
                        <th class="border border-gray-700 px-4 py-2">Return Date</th>
                        <th class="border border-gray-700 px-4 py-2">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                        <tr class="hover:bg-gray-700 text-center">
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($customer['reservation_id']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($customer['customer_id']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($customer['email']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($customer['phone_number']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($customer['pickup_date']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($customer['return_date']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($customer['total_amount']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
