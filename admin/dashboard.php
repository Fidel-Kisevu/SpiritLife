<?php
session_start();
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php");
    exit();
}

include '../includes/db_connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-img {
            border-radius: 50%;
            width: 60px;
            height: 60px;
            object-fit: cover;
        }
        .card-body {
            display: flex;
            align-items: flex-start;
        }
        .card-content {
            flex: 1;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="dashboard.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

        <h2 class="mb-4">Add New Sermon</h2>
        <form action="add_sermon.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" class="form-control" id="author" name="author" required>
            </div>
            <div class="form-group">
                <label for="author_image">Author Image</label>
                <input type="file" class="form-control-file" id="author_image" name="author_image">
            </div>
            <button type="submit" class="btn btn-primary">Add Sermon</button>
        </form>

        <h2 class="mt-5 mb-4">Manage Sermons</h2>
        <?php
        $sql = "SELECT * FROM sermons ORDER BY date DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='card mb-3'>
                    <div class='card-body d-flex flex-column'>
                        <div class='d-flex align-items-start'>
                            <img src='" . htmlspecialchars($row['author_image']) . "' class='profile-img mr-3' alt='Author Image'>
                            <div>
                                <h6 class='mb-1'>" . htmlspecialchars($row['author']) . "</h6>
                                <small class='text-muted'>" . htmlspecialchars($row['date']) . "</small>
                            </div>
                        </div>
                        <div class='card-content mt-3'>
                            <h5 class='card-title'>" . htmlspecialchars($row['title']) . "</h5>
                            <p class='card-text'>" . htmlspecialchars($row['content']) . "</p>
                        </div>
                       <div class='card-content mt-3 '> 
                             <a href='edit_sermon.php?id=" . $row['id'] . "' class='btn btn-warning'>Edit</a>
                             <a href='delete_sermon.php?id=" . $row['id'] . "' class='btn btn-danger'>Delete</a>
                        </div>
                    </div>
                </div>";
            }
        } else {
            echo "<div class='alert alert-info'>No sermons available.</div>";
        }

        $conn->close();
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
