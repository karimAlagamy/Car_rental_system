<?php

// Connection details
$servername = "127.0.0.1"; 
$username = "root";
$password = "";
$dbname = "registration_form";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Start transaction
    $conn->begin_transaction();

    try {
        // Get user input
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $address = $_POST["address"];
        $username = $_POST["username"];
        $password = md5($_POST["password"]); // Use MD5 to hash the password

        // Insert new user into the database
        $stmt = $conn->prepare("INSERT INTO user (name, surname, email, phone, address, username, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $name, $surname, $email, $phone, $address, $username, $password);
        $stmt->execute();

        // Commit the transaction
        $conn->commit();
        echo "<script>
                alert('Registration successful! Please log in.');
                window.location.href = 'index.html';
              </script>";
    } catch (mysqli_sql_exception $e) {
        $conn->rollback();

        // Check if the error is a duplicate key violation
        if ($e->getCode() == 1062) { // MySQL error code for duplicate entry
            echo "<script>
                    alert('Error: Duplicate entry detected (email or username already exists).');
                    window.location.href = 'registration.html';
                  </script>";
        } else {
            echo "<script>
                    alert('Error: " . $e->getMessage() . "');
                    window.location.href = 'registration.html';
                  </script>";
        }
    }

    // Close the statement
    if (isset($stmt)) {
        $stmt->close();
    }
}

// Close the connection
$conn->close();
?>
