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
            $query = "SELECT r.reservation_id, r.car_id, r.customer_id, r.pickup_date, r.return_date, r.total_amount
                      FROM reservation r
                      WHERE r.pickup_date >= :startDate AND r.return_date <= :endDate";
            $params[':startDate'] = $startDate;
            $params[':endDate'] = $endDate;
            break;

        case "reservations-car":
            $query = "SELECT r.reservation_id, r.customer_id, r.pickup_date, r.return_date, r.total_amount
                      FROM reservation r
                      WHERE r.car_id = :carId AND r.pickup_date >= :startDate AND r.return_date <= :endDate";
            $params[':carId'] = $carId;
            $params[':startDate'] = $startDate;
            $params[':endDate'] = $endDate;
            break;

        case "car-status":
            $query = "SELECT c.car_id, c.make, c.model, c.status
                      FROM car c
                      WHERE c.last_update_date = :specificDate";
            $params[':specificDate'] = $specificDate;
            break;

        case "customer-reservations":
            $query = "SELECT r.reservation_id, r.car_id, r.pickup_date, r.return_date, r.total_amount
                      FROM reservation r
                      WHERE r.customer_id = :customerId AND r.pickup_date >= :startDate AND r.return_date <= :endDate";
            $params[':customerId'] = $customerId;
            $params[':startDate'] = $startDate;
            $params[':endDate'] = $endDate;
            break;

        case "daily-payments":
            $query = "SELECT r.payment_date, SUM(r.total_amount) as total_payments
                      FROM reservation r
                      WHERE r.payment_date >= :startDate AND r.payment_date <= :endDate
                      GROUP BY r.payment_date";
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

    // Return the results as JSON for rendering in the HTML page
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'data' => $results]);

} catch (PDOException $e) {
    // Handle database errors
    echo json_encode(['status' => 'error', 'message' => "Database Error: " . $e->getMessage()]);
    exit;
} catch (Exception $e) {
    // Handle general errors
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}
