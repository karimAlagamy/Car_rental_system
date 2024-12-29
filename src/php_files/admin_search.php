<?php
require_once "utilities.php";

// Connect to the database
$conn = getDatabaseConnection();

try {
    //retrieve inputs
    

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