<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sermons</title>
    <link href="../assets/styles/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }
        .profile-img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
        .card-body {
            padding: 20px;
        }
        .card-content {
            margin-top: 20px;
        }
        .card-title {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }
        .card-text {
            font-size: 1rem;
            color: #333;
        }
        .author-meta {
            display: flex;
            align-items: center;
        }
        .author-meta div {
            margin-left: 15px;
        }
        .author-name {
            font-weight: bold;
            font-size: 1rem;
        }
        .author-date {
            color: #6c757d;
            font-size: 0.875rem;
        }
        .sermon-content p {
            margin-bottom: 0.7rem;
            line-height: 1.6;
        }
        .sermon-content em {
            display: block;
            margin: 1rem 0;
            padding: 0.5rem;
            font-style: italic;
            background-color: #f8f9fa;
            border-left: 4px solid #dee2e6;
        }
        .sermon-content blockquote {
            padding: 1rem;
            margin: 1rem 0;
            border-left: 5px solid #ccc;
            background: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container mt-5 ">
        <?php
        include '../includes/db_connection.php';

        $sql = "SELECT id, title, content, author, author_image, DATE_FORMAT(date, '%M %d, %Y') as formatted_date FROM sermons ORDER BY date DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Assuming content contains paragraphs separated by newline characters
                $paragraphs = explode("\n", htmlspecialchars($row['content']));
                $formattedContent = '';

                foreach ($paragraphs as $paragraph) {
                    // Check if the paragraph contains a Bible verse marker (e.g., [verse])
                    if (strpos($paragraph, '[verse]') !== false) {
                        // Remove the [verse] marker and wrap in <em> tags
                        $formattedContent .= '<em>' . str_replace('[verse]', '', $paragraph) . '</em>';
                    } else {
                        $formattedContent .= '<p>' . $paragraph . '</p>';
                    }
                }

                echo "<div class='card'>
                    <div class='card-body d-flex flex-column'>
                        <div class='d-flex align-items-start'>
                            <img src='" . htmlspecialchars($row['author_image']) . "' class='profile-img mr-3' alt='Author Image'>
                            <div>
                                <h6 class='mb-1'>" . htmlspecialchars($row['author']) . "</h6>
                                <small class='text-muted'>" . htmlspecialchars($row['formatted_date']) . "</small>
                            </div>
                        </div>
                        <div class='card-content mt-3'>
                            <h5 class='card-title'>" . htmlspecialchars($row['title']) . "</h5>
                            <div class='sermon-content'>" . $formattedContent . "</div>
                        </div>
                    </div>
                </div>";
            }
        } else {
            echo "<p>No sermons available.</p>";
        }

        $conn->close();
        ?>
    </div>
    <script src="../assets/scripts/jquery.min.js"></script>
    <script src="../assets/scripts/popper.min.js"></script>
    <script src="../assets/scripts/bootstrap.min.js"></script>
</body>
</html>
