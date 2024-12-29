<?php
require_once "utilities.php";

// Connect to the database
$conn = getDatabaseConnection();

try {
    // Retrieve inputs
    $car_id = $_POST['car_id'] ?? null;
    $plate_number = $_POST['plate_number'] ?? null;
    $office_id = $_POST['office_id'] ?? null;

    // Validate inputs - at least one field is required
    if (empty($car_id) && empty($plate_number) && empty($office_id)) {
        throw new Exception("At least one field (Car ID, Plate Number, or Office ID) is required to delete a car.");
    }

    // Build the query dynamically based on input
    $query = "DELETE FROM car WHERE ";
    $conditions = [];
    $params = [];

    if (!empty($car_id)) {
        $conditions[] = "car_id = :car_id";
        $params[':car_id'] = $car_id;
    }
    if (!empty($plate_number)) {
        $conditions[] = "plate_number = :plate_number";
        $params[':plate_number'] = $plate_number;
    }
    if (!empty($office_id)) {
        $conditions[] = "office_id = :office_id";
        $params[':office_id'] = $office_id;
    }

    // Combine conditions
    $query .= implode(" OR ", $conditions);

    // Prepare the statement
    $stmt = $conn->prepare($query);

    // Bind parameters
    foreach ($params as $param => $value) {
        $stmt->bindValue($param, $value, is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }

    // Execute the query
    $stmt->execute();

    // Check if any rows were affected
    if ($stmt->rowCount() > 0) {
        echo "<script>
                alert('Car deleted successfully.');
                window.location.href = '../delete_cars.html';
              </script>";
    } else {
        echo "<script>
                alert('No car found with the provided details.');
                window.history.back();
              </script>";
    }

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
