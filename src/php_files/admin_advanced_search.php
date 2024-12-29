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
            alert('Please provide only one filter (Car ID, Customer ID, or Reservation Date)');
            window.history.back();
            </script>";
        exit;
    }

    // Base query and dynamic variables
    $query = "";
    $params = [];
    $headers = []; // Table headers

    // Adjust the query based on the provided filter
    if (!empty($car_id)) {
        // Display all reservations for the specified car
        $query = "
            SELECT 
                u.first_name, 
                u.last_name, 
                r.pickup_date, 
                r.return_date, 
                r.total_amount
            FROM reservation r
            JOIN `user` u ON r.user_id = u.user_id
            WHERE r.car_id = :car_id
        ";
        $params[':car_id'] = $car_id;
        $headers = ['First Name', 'Last Name', 'Pick-Up Date', 'Return Date', 'Total Amount'];
    } elseif (!empty($cust_id)) {
        // Display all cars reserved by the specified customer
        $query = "
            SELECT 
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
        $headers = ['Make', 'Model', 'Plate Number', 'Year', 'Pick-Up Date', 'Return Date'];
    } elseif (!empty($res_date)) {
        // Display all cars reserved on the specified date
        $query = "
            SELECT 
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
            JOIN `user` u ON r.user_id = u.user_id
            WHERE r.reservation_date = :reservation_date
        ";
        $params[':reservation_date'] = $res_date;
        $headers = ['Make', 'Model', 'Plate Number', 'Year', 'First Name', 'Last Name', 'Pick-Up Date', 'Return Date'];
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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-gray-400">
    <!-- Header -->
    <header class="bg-gray-900 shadow-md">
        <nav class="container mx-auto flex items-center justify-between p-4 lg:px-8">
            <div class="flex items-center text-sm font-semibold text-green-300 hover:text-green-200 font-mono">
                <a href="#">Query Results</a>
            </div>
        </nav>
    </header>

    <!-- Query Results -->
    <main class="container mx-auto my-10 px-6 lg:px-8">
        <div class="bg-gray-800 shadow-lg rounded-lg p-6">
            <h1 class="text-2xl font-bold text-green-300 mb-6 text-center">Query Results</h1>

            <?php if (!empty($results)): ?>
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-700 text-green-300">
                            <?php foreach ($headers as $header): ?>
                                <th class="border border-gray-700 px-4 py-2"><?php echo htmlspecialchars($header); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $result): ?>
                            <tr class="hover:bg-gray-700 text-center">
                                <?php foreach ($result as $value): ?>
                                    <td class="border border-gray-700 px-4 py-2 text-gray-300"><?php echo htmlspecialchars($value); ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center text-lg mt-6 text-red-400">No results found for the specified filter.</p>
            <?php endif; ?>

            <!-- Back Button -->
            <div class="text-center mt-6">
                <button onclick="window.history.back();" class="bg-green-500 hover:bg-green-600 text-black font-medium py-2 px-6 rounded-md">
                    Back
                </button>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 border-t border-gray-700">
        <div class="container mx-auto py-10 px-4 md:px-8 text-center text-sm text-green-300">
            &copy; 2024 Sawa2na Aktar, Inc. All rights reserved.
        </div>
    </footer>
</body>
</html>
