<?php
include '../includes/db_connection.php';
session_start();
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php");
    exit();
}

$success = '';
$error = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $author = $_POST['author'];

        // Handle file upload
        $author_image = $_POST['current_image']; // Default to current image if no new image is uploaded
        if (isset($_FILES['author_image']) && $_FILES['author_image']['error'] == 0) {
            $upload_dir = '../assets/images/uploads/';
            $upload_file = $upload_dir . basename($_FILES['author_image']['name']);
            if (move_uploaded_file($_FILES['author_image']['tmp_name'], $upload_file)) {
                $author_image = $upload_file;
            } else {
                $error = "Error uploading file.";
                header("Location: edit_sermon.php?id=$id&error=" . urlencode($error));
                exit();
            }
        }

        // Prepare and bind
        $sql = "UPDATE sermons SET title = ?, content = ?, author = ?, author_image = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $title, $content, $author, $author_image, $id);

        if ($stmt->execute()) {
            $success = "Sermon updated successfully!";
            header("Location: dashboard.php?success=" . urlencode($success));
            exit();
        } else {
            $error = "Error: " . $stmt->error;
            header("Location: edit_sermon.php?id=$id&error=" . urlencode($error));
            exit();
        }

        $stmt->close();
    } else {
        $sql = "SELECT * FROM sermons WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $sermon = $result->fetch_assoc();
        $stmt->close();
    }
} else {
    header("Location: dashboard.php");
    exit();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sermon</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function confirmUpdate() {
            return confirm("Are you sure you want to update this sermon?");
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Sermon</h1>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <form action="edit_sermon.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data" onsubmit="return confirmUpdate();">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($sermon['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea class="form-control" id="content" name="content" rows="5" required><?php echo htmlspecialchars($sermon['content']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" class="form-control" id="author" name="author" value="<?php echo htmlspecialchars($sermon['author']); ?>" required>
            </div>
            <div class="form-group">
                <label for="author_image">Author Image</label>
                <input type="file" class="form-control-file" id="author_image" name="author_image">
                <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($sermon['author_image']); ?>">
                <?php if ($sermon['author_image']) : ?>
                    <img src="<?php echo htmlspecialchars($sermon['author_image']); ?>" alt="Author Image" style="max-width: 200px; margin-top: 10px;">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Update Sermon</button>
        </form>
    </div>
</body>
</html>
