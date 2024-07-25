<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sermons</title>
    <link href="../assets/styles/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="../assets/styles/custom.css">
    <style>
        
    </style>
</head>
<body>
        <!-- Navigation Bar -->
        <nav class="navbar navbar-expand-lg  bg-primary">
         <a class="navbar-brand text-secondary" href="#">SpiritLife</a>
         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
             <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarNav">
             <ul class="navbar-nav">
                 <li class="nav-item">
                     <a class="nav-link btn text-primary btn-secondary " href="#">Bible</a>
                 </li>
                 <li class="nav-item active">
                     <a class="nav-link text-primary" href="#">Home</a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link text-primary" href="#">Sermons</a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link text-primary" href="#">Authors</a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link text-primary" href="#">About</a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link text-primary" href="#">Contact</a>
                 </li>
             </ul>
         </div>
     </nav>
     
         <!-- Carousel -->
         <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
             <ol class="carousel-indicators">
                 <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                 <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                 <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
             </ol>
             <div class="carousel-inner">
                 <div class="carousel-item active">
                     <img src="../assets/images/uploads/istockphoto-1076215430-1024x1024.jpg" class="d-block w-100" alt="First slide">
                     <div class="carousel-caption animate__animated animate__fadeIn">
                         <h5>First Slide</h5>
                         <p>Description for the first slide.</p>
                     </div>
                 </div>
                 <div class="carousel-item">
                     <img src="../assets/images/uploads/istockphoto-1076215430-1024x1024.jpg" class="d-block w-100" alt="Second slide">
                     <div class="carousel-caption animate__animated animate__fadeIn">
                         <h5>Second Slide</h5>
                         <p>Description for the second slide.</p>
                     </div>
                 </div>
                 <div class="carousel-item">
                     <img src="../assets/images/uploads/istockphoto-1076215430-1024x1024.jpg" class="d-block w-100" alt="Third slide">
                     <div class="carousel-caption animate__animated animate__fadeIn">
                         <h5>Third Slide</h5>
                         <p>Description for the third slide.</p>
                     </div>
                 </div>
             </div>
             <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                 <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                 <span class="sr-only">Previous</span>
             </a>
             <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                 <span class="carousel-control-next-icon" aria-hidden="true"></span>
                 <span class="sr-only">Next</span>
             </a>
         </div>
     
         <!-- Main Content Area -->
         <div class="container mt-5">
             <div class="jumbotron animate__animated animate__fadeInUp">
                 <h1 class="display-4">Welcome to Spirit and Life</h1>
                 <p class="lead">A place to explore and grow in your spiritual journey.</p>
                 <hr class="my-4">
                 <p>Discover sermons, connect with authors, and get inspired.</p>
                 <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>
             </div>
         </div>
         
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
     
         <!-- Footer -->
         <footer class="bg-light text-center text-lg-start mt-5">
             <div class="text-center p-3">
                 © 2024 Spirit and Life. All Rights Reserved.
             </div>
         </footer>
     
         <!-- Bootstrap JavaScript and dependencies -->
         <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
         <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
         <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
     </body>
     </html>
     
