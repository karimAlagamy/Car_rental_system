<?php

require_once "utilities.php";

// Connect to the database
$conn = getDatabaseConnection();

try {
    // Retrieve user input
    $office_location = $_POST['office_location'];
    $pick_up_date = $_POST['pick_up_date'];
    $return_date = $_POST['return_date'];

   
   
} catch (PDOException $e) {
    // Handle any database errors
    echo "Error: " . $e->getMessage();
}

// NOT IMPLEMENTED YET
?>

