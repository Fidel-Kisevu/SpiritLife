<?php
session_start();
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php");
    exit();
}

include '../includes/db_connection.php';

// Fetch success and error messages from URL parameters
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> <!-- FontAwesome for icons -->
    <style>
        body{
            height: 3000px;
        }
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
        .sermon-content {
            display: none;
        }
        .show-content .sermon-content {
            display: block;
        }
        .sermon-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out, opacity 0.3s ease;
    opacity: 0;
}

.show-content .sermon-content {
    max-height: 500px; /* Adjust as needed */
    opacity: 1;
}

    </style>
    <script>
     
        function toggleContent(event, element) {
    event.preventDefault(); // Prevent the default anchor behavior
    const content = element.nextElementSibling;
    content.classList.toggle('sermon-content');
    const icon = element.querySelector('i');
    if (content.classList.contains('sermon-content')) {
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    } else {
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    }
}
        

        function showAuthorSermons(authorId) {
            const sermonSections = document.querySelectorAll('.sermon-section');
            sermonSections.forEach(section => {
                if (section.dataset.authorId === authorId) {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });
        }
    </script>
</head>
<body>
 <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="dashboard.php">Home</a>
                </li>
            </ul>
            <a class="btn logout-btn btn-danger" href="logout.php">Logout</a>
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

        <h3>Authors</h3>
        <ul class="list-group mb-4">
            <?php
            $authorSql = "SELECT DISTINCT author FROM sermons";
            $authorResult = $conn->query($authorSql);
            if ($authorResult->num_rows > 0) {
                while ($author = $authorResult->fetch_assoc()) {
                    echo "<li class='list-group-item' style='cursor: pointer;' onclick=\"showAuthorSermons('". htmlspecialchars($author['author']) . "')\">" . htmlspecialchars($author['author']) . "</li>";
                }
            } else {
                echo "<li class='list-group-item'>No authors available.</li>";
            }
            ?>
        </ul>

        <?php
        $sermonSql = "SELECT * FROM sermons ORDER BY date DESC";
        $sermonResult = $conn->query($sermonSql);
        if ($sermonResult->num_rows > 0) {
            while ($sermon = $sermonResult->fetch_assoc()) {
                echo "<div class='sermon-section' data-author-id='" . htmlspecialchars($sermon['author']) . "' style='display: none;'>
                    <div class='card mb-3'>
                        <div class='d-flex flex-column p-3'>
                            <div class='d-flex align-items-start'>
                                <img src='" . htmlspecialchars($sermon['author_image']) . "' class='profile-img mr-3' alt='Author Image'>
                                <div>
                                    <h6 class='mb-1'>" . htmlspecialchars($sermon['author']) . "</h6>
                                    <small class='text-muted'>" . htmlspecialchars($sermon['date']) . "</small>
                                </div>
                            </div>
                            <div class='card-content mt-3'>
                                <h5 class='card-title'>" . htmlspecialchars($sermon['title']) . "</h5>
                              <a href='' class='btn btn-info' onclick='toggleContent(event, this)' data-toggle='tooltip' title='Click to toggle content'><i class='fas fa-chevron-down'></i> Show Content</a>

                                <div class='sermon-content'>
                                    <p class='card-text'>" . htmlspecialchars($sermon['content']) . "</p>
                                </div>
                            </div>
                            <div class='card-content mt-3'>
                                <a href='edit_sermon.php?id=" . $sermon['id'] . "' class='btn btn-warning'>Edit</a>
                                <a href='#' class='btn btn-danger' onclick=\"confirmAction('delete', 'Are you sure you want to delete this sermon?', 'delete_sermon.php?id=" . $sermon['id'] . "')\">Delete</a>
                            </div>
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
