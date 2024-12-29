<?php
require_once "utilities.php";

// Connect to the database
$conn = getDatabaseConnection();

try {
    // Retrieve inputs
    $car_id = $_POST['car_id'] ?? null;
    $plate_number = $_POST['plate_number'] ?? null;
    $status = $_POST['status'] ?? null;

    // Validate inputs
    if (empty($status)) {
        throw new Exception("New status is required.");
    }

    if (empty($car_id) && empty($plate_number)) {
        throw new Exception("You must provide either Car ID or Plate Number.");
    }

    // Build the query to fetch the current status
    $query = "SELECT `status` FROM car WHERE ";
    $params = [];

    if (!empty($car_id)) {
        $query .= "car_id = :car_id";
        $params[':car_id'] = $car_id;
    } elseif (!empty($plate_number)) {
        $query .= "plate_number = :plate_number";
        $params[':plate_number'] = $plate_number;
    }

    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    foreach ($params as $param => $value) {
        $stmt->bindValue($param, $value, is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    $stmt->execute();

    $currentStatus = $stmt->fetchColumn();

    // Check if the car exists and if the status is already the same
    if (!$currentStatus) {
        throw new Exception("No matching car found with the provided details.");
    }

    if ($currentStatus === $status) {
        throw new Exception("The car is already in the specified mode.");
    }

    // Build the update query
    $updateQuery = "UPDATE car SET `status` = :status WHERE ";
    $updateParams = [':status' => $status];

    if (!empty($car_id)) {
        $updateQuery .= "car_id = :car_id";
        $updateParams[':car_id'] = $car_id;
    } elseif (!empty($plate_number)) {
        $updateQuery .= "plate_number = :plate_number";
        $updateParams[':plate_number'] = $plate_number;
    }

    // Prepare the update statement
    $updateStmt = $conn->prepare($updateQuery);

    // Bind parameters
    foreach ($updateParams as $param => $value) {
        $updateStmt->bindValue($param, $value, is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }

    // Execute the update query
    $updateStmt->execute();

    // Provide success feedback
    echo "<script>
            alert('Car mode updated successfully.');
            window.location.href = '../change_car_mode.html';
          </script>";

} catch (PDOException $e) {
    // Handle database errors
    echo "<script>
            alert('Database Error: " . addslashes($e->getMessage()) . "');
            window.history.back();
          </script>";
    exit;
} catch (Exception $e) {
    // Handle validation or general errors
    echo "<script>
            alert('Error: " . addslashes($e->getMessage()) . "');
            window.history.back();
          </script>";
    exit;
}
?>
