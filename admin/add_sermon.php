<?php
include '../includes/db_connection.php';

// Get user input
$title = $_POST['title'];
$content = $_POST['content'];
$author = $_POST['author'];

// Handle file upload
$author_image = '';
if (isset($_FILES['author_image']) && $_FILES['author_image']['error'] == 0) {
    $upload_dir = '../assets/images/uploads/'; // Directory to save uploaded files
    $upload_file = $upload_dir . basename($_FILES['author_image']['name']);
    if (move_uploaded_file($_FILES['author_image']['tmp_name'], $upload_file)) {
        $author_image = $upload_file;
    } else {
        echo "Error uploading file.";
        exit();
    }
}

// Prepare and bind
$sql = "INSERT INTO sermons (title, content, author, author_image) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $title, $content, $author, $author_image);

if ($stmt->execute()) {
    header("Location: dashboard.php");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
