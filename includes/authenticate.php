<?php
session_start();
include 'db_connection.php'; // Include the database connection

// Get user input
$username = $_POST['username'];
$password = $_POST['password'];

// Prepare and execute query
$stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    if (password_verify($password, $admin['password'])) {
        // Set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        // Redirect to admin dashboard
        header("Location: ../admin/dashboard.php");
        exit();
    } else {
        echo "Invalid password";
    }
} else {
    echo "No user found";
}

// Close connection
$stmt->close();
$conn->close();
?>
