<?php

$servername = "127.0.0.1"; 
$username = "root";
$password = "";
$dbname = "registration_form";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

// Prepare and bind
$stmt = $conn->prepare("SELECT name, password FROM user WHERE email = ?");
$stmt->bind_param("s", $email);

// Execute the statement
$stmt->execute();

// Bind result variables
$stmt->bind_result($username, $hashed_password);

// Fetch the result and verify the password
if ($stmt->fetch()) {
    if (md5($password) === $hashed_password && $username != "root") {
        echo "Welcome, " . htmlspecialchars($username) . "!";
    } else {
        echo "<script>
                alert('Invalid email or password. Please try again.');
                window.location.href = 'login.html';
              </script>";
    }
} else {
    echo "<script>
            alert('Invalid email or password. Please try again.');
            window.location.href = 'login.html';
          </script>";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>