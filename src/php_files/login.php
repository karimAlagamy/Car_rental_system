<?php

require_once "utilities.php";

// Connect to the database
$conn = getDatabaseConnection();

try {
    // Retrieve user input
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT first_name, password, user_type, username FROM user WHERE email = ?");

    // Execute the statement with the parameter
    $stmt->execute([$email]);

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Retrieve values from the result
        $retrieved_name = $result['first_name'];
        $retrieved_password = $result['password'];
        $user_type = $result['user_type'];
        $username = $result['username'];

        // Verify the password
        if ($password === $retrieved_password && $retrieved_name != "root") {
            // Redirect based on user type
            if ($user_type === 'C') {
                echo "<script>
                        alert('Welcome, " . htmlspecialchars($username) . "!');
                        window.location.href = '../index.html';
                      </script>";
            } elseif ($user_type === 'A') {
                echo "<script>
                        alert('Welcome, Admin " . htmlspecialchars($username) . "!');
                        window.location.href = '../admin.html';
                      </script>";
            }
        } else {
            // Invalid credentials
            echo "<script>
                    alert('Invalid email or password. Please try again.');
                    window.location.href = '../login.html';
                  </script>";
        }
    } else {
        // No matching user found
        echo "<script>
                alert('Invalid email or password. Please try again.');
                window.location.href = '../login.html';
              </script>";
    }
} catch (PDOException $e) {
    // Handle any database errors
    echo "<script>
            alert('Error: " . $e->getMessage() . "');
            window.location.href = '../login.html';
          </script>";
}

// Close the connection
$conn = null;
?>
