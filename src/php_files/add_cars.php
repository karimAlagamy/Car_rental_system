<?php
require_once "utilities.php";

// Connect to the database
$conn = getDatabaseConnection();

try {
    //retrieve inputs
    $car_id = $_POST['car_id'];
    $plate_num = $_POST['plate_number'];
    $office_id = $_POST['office_id'];
    $day_rate = $_POST['day_rate'];
    $car_stat = $_POST['status'];
    $car_make = $_POST['make'];
    $car_model = $_POST['model'];
    $year = $_POST['year'];
    $num_seats = $_POST['seats'];

    $query = "INSERT INTO car (car_id, plate_number, office_id, day_rate, `status`, make, model, `year`, no_of_seats)
              VALUES (:car_id, :plate_num, :office_id, :day_rate, :status, :make, :model, :year, :no_of_seats)";

    $params = [
        ':car_id' => $car_id,
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
        $stmt->bindValue($param, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    
    // Execute the query
    $stmt->execute();

    echo "Car successfully added";

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