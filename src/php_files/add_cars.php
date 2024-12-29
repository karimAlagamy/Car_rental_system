<?php
require_once "utilities.php";

// Connect to the database
$conn = getDatabaseConnection();

try {
    // Retrieve inputs and validate
    $plate_num = $_POST['plate_number'] ?? null;
    $office_id = $_POST['office_id'] ?? null;
    $day_rate = $_POST['day_rate'] ?? null;
    $car_stat = $_POST['status'] ?? null;
    $car_make = $_POST['make'] ?? null;
    $car_model = $_POST['model'] ?? null;
    $year = $_POST['year'] ?? null;
    $num_seats = $_POST['seats'] ?? null;

    // Validate mandatory fields
    if (empty($plate_num) || empty($office_id) || empty($day_rate) || empty($car_stat) || empty($car_make) || empty($car_model) || empty($year) || empty($num_seats)) {
        throw new Exception("All fields are required. Please fill in all the details.");
    }

    // Ensure numeric fields are valid
    if (!is_numeric($day_rate) || $day_rate <= 0) {
        throw new Exception("Day rate must be a positive number.");
    }

    if (!is_numeric($year) || strlen($year) !== 4 || $year < 1900 || $year > intval(date("Y"))) {
        throw new Exception("Year must be a valid 4-digit number.");
    }

    if (!is_numeric($num_seats) || $num_seats <= 0) {
        throw new Exception("Number of seats must be a positive integer.");
    }

    // Prepare the SQL query
    $query = "INSERT INTO car (car_id, plate_number, office_id, day_rate, `status`, make, model, `year`, no_of_seats)
              VALUES (:car_id, :plate_num, :office_id, :day_rate, :status, :make, :model, :year, :no_of_seats)";

    $params = [
        ':plate_num' => $plate_num,
        ':office_id' => $office_id,
        ':day_rate' => $day_rate,
        ':status' => $car_stat,
        ':make' => $car_make,
        ':model' => $car_model,
        ':year' => $year,
        ':no_of_seats' => $num_seats
    ];

    // Prepare the statement
    $stmt = $conn->prepare($query);

    // Bind all parameters dynamically
    foreach ($params as $param => $value) {
        $stmt->bindValue($param, $value, is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }

    // Execute the query
    $stmt->execute();

    // Provide success feedback
    echo "<script>
                alert('Car added successfully.');
                window.location.href = '../add_cars.html';
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
