<?php
session_start();
include '../includes/db_connection.php'; // Include the database connection

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate inputs
    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
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
                $error = "Invalid password.";
            }
        } else {
            $error = "No user found.";
        }

        // Close connection
        $stmt->close();
    }
    $conn->close();
}
?>
