<?php

require_once "utilities.php";

// Connect to the database
$conn = getDatabaseConnection();

try {
    // Retrieve user input
    $office_location = $_POST['office_location'];
    $pick_up_date = $_POST['pick_up_date'];
    $return_date = $_POST['return_date'];

    // NOT REQUIRED
    $make = $_POST['make'];
    $model = $_POST['model'];
    $number_of_seats = $_POST['number_of_seats'];
   
} catch (PDOException $e) {
    // Handle any database errors
    echo "Error: " . $e->getMessage();
}

// NOT IMPLEMENTED YET
?>

