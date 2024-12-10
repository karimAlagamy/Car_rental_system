<?php

require_once "utilities.php";

// Connect to the database
$conn = getDatabaseConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Start transaction
    $conn->beginTransaction(); // Correct method name

    try {
        // Get user input
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $address = $_POST["address"];
        $username = $_POST["username"];
        $password = $_POST["password"]; // Use password_hash for secure hashing

        // Insert new user into the database
        $stmt = $conn->prepare(
            "INSERT INTO user (first_name, last_name, email, phone_number, address, username, password) 
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([$name, $surname, $email, $phone, $address, $username, $password]);

        // Commit the transaction
        $conn->commit();

        // Success message and redirection
        echo "<script>
                alert('Registration successful! Please log in.');
                window.location.href = '../login.html';
              </script>";
    } catch (PDOException $e) {
        // Rollback the transaction in case of error
        $conn->rollBack();

        // Check if the error is a duplicate key violation
        if ($e->errorInfo[1] == 1062) { // MySQL error code for duplicate entry
            echo "<script>
                    alert('Error: Duplicate entry detected (email or username already exists).');
                    window.location.href = 'registration.html';
                  </script>";
        } else {
            // General error handling
            echo "<script>
                    alert('Error: " . $e->getMessage() . "');
                    window.location.href = 'registration.html';
                  </script>";
        }
    }

    // Close the statement
    if (isset($stmt)) {
        $stmt = null; // PDO uses null to close statements
    }
}

// Close the connection
$conn = null; // PDO uses null to close connections
?>
