<?php
require_once "utilities.php";

// Connect to the database
$conn = getDatabaseConnection();

try {
    $res_date = $_POST['reservation_date'] ?? null;
    $car_id = $_POST['car_id'] ?? null;
    $cust_id = $_POST['customer_id'] ?? null;


    $query = 
    "   SELECT u.first_name, u.last_name, c.make, c.model, c.plate_number, c.year, r.pickup_date, r.return_date, r.total_amount
        FROM user u JOIN reservation r ON u.customer_id = r.customer_id
        JOIN car c ON r.car_id = c.car_id
        WHERE 
    ";
    
    $conditions = [];
    $params = [];

    if(!empty($res_date))
    {
        $conditions[] = "r.reservation_date = :reservation_date";
        $params[':reservation_date'] = $res_date;
    }

    if(!empty($car_id))
    {
        $conditions[] = "r.car_id = :car_id";
        $params[':car_id'] = $car_id;
    }

    if(!empty($cust_id))
    {
        $conditions[] = "r.customer_id = :customer_id";
        $params[':customer_id'] = $cust_id;
    }

    if (!empty($conditions)) {
        $query .= implode(" AND ", $conditions);
    }

        // Prepare the statement
        $stmt = $conn->prepare($query);

        // Bind all parameters dynamically
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
    
        // Execute the query
        $stmt->execute();
    
        // Fetch the results
        $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
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