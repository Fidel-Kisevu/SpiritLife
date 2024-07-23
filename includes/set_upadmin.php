<?php
include 'db_connection.php'; // Include the database connection

// Define admin details
$username = 'admin'; // Admin username
$password = 'AdminDelfy'; // Admin password (will be hashed)
$email = 'fideliskisevu@gmail.com'; // Admin email

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO admins (username, password, email) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $hashedPassword, $email);

// Execute the statement
if ($stmt->execute()) {
    echo "Admin account created successfully.";
} else {
    echo "Error: " . $stmt->error;
}

// Close connection
$stmt->close();
$conn->close();
?>
