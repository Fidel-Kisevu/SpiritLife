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
