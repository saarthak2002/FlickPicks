<!-- Deployed site: https://cs4640.cs.virginia.edu/uzn2up/FlickPicks/ -->

<?php
    session_start();
    $alert = '';
?>

<!DOCTYPE html>
<html lang="en-us">
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <meta name="author" content="Saarthak Gupta">
        <meta name="description" content="A platform to find and share the movies you love with the people you love.">
        <meta name="keywords" content="Movies, Films, Search, Reviews, Ratings">   
        
        <title>FlickPicks</title>

        <!-- Bootstrap CSS CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"> 
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <!-- Custom Styles -->
        <link rel="stylesheet" type="text/css" href="styles/main.css">

        <!-- Custom Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,700;1,400&display=swap" rel="stylesheet">

        <style>
            .card {
                
            }
            .card:hover {
                transform: scale(1);
                cursor: auto;
            }
            #view-button.btn.btn-primary {
                border-radius: 1.9rem;
                background-color: var(--text-color-dark);
                border-color: var(--text-color-dark);
            }

            #view-button.btn.btn-primary:hover {
                background-color: var(--text-color-light);
                color: var(--text-color-dark);
                border-color: var(--text-color-dark);
            }

            #add-post-button.btn.btn-primary {
                border-radius: 1.9rem;
                background-color: var(--highlight-color);
                border-color: var(--highlight-color);
            }

            #add-post-button.btn.btn-primary:hover {
                background-color: var(--text-color-light);
                color: var(--text-color-dark);
                border-color: var(--text-color-dark);
            }

            .card-title {
                color: black !important;
            }
            
        </style>

        <script>
            function fetchBlogPosts() {
                // make AJAX request to get blog posts from backend
                $.ajax({
                    url: 'php/fetch-posts.php', // ajax requests that cosumes JSON backend
                    method: 'GET',
                    data: {},
                    dataType: 'json',
                    success: function(response) {
                        for (var i = 0; i < response.length; i++) {
                            var post = response[i]; // This is a javascript object
                            console.log(post);
                            // Use the object properties to populate the cards
                            $("#blog-post-container").append( // update the DOM
                                `
                                    <div class="col-md-6 post">
                                        <div class="card h-100" style="box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px; background: #E5E5E5;">
                                            <div class="card-body">
                                                <h5 class="card-title">${post.title}</h5>
                                                <h6>by ${post.author}</h6>
                                                <p class="card-text" style="font-weight: 100;">${post.description}</p>
                                                <a href="viewpost.php?id=${post.id}" id="view-button" class="btn btn-primary">Read More</a>
                                            </div>
                                        </div>
                                    </div>
                                `
                            );
                        }
                        
                        // add animation event handler to each card
                        var cards = document.querySelectorAll('.card');
                        cards.forEach(function(card) {
                            card.addEventListener('mouseover', function() {
                                this.style.borderWidth = '5px';
                                this.style.borderColor = '#FCA311';
                            });
                            card.addEventListener('mouseout', function() {
                                this.style.borderWidth = '0px';
                            });
                        });
                        
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        </script>

    </head>
    
    <body>
        <!-- User must be logged in to view blog -->
        <?php if(isset($_SESSION['user_id'])) { ?>       
            <!-- Display messages on homepage for various actions -->
            <?php
                if(isset($_GET['success'])) {
                    if($_GET['success'] == 1) {
                        $alert = 'Movie added to FlickPick';
                    }
                    if($_GET['success'] == 2) {
                        $alert = 'Movie already added to FlickPick';
                    }
                    if($_GET['success'] == 3) {
                        $alert = 'Deleted movie from FlickPick';
                    }
                    if($_GET['success'] == 4) {
                        $alert = 'Movie does not exist in FlickPick';
                    }
                }
            ?>

            <?php
                if (!empty($alert)) {
            ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> <?= $alert?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>  
            <?php } ?>
            <div class="container">

                <!-- Nav Bar -->
                <header>
                    <nav class="navbar navbar-expand-lg navbar-dark mb-5">
                        <a class="navbar-brand" href="./index.php">
                            <img id="logoImage" src="./resources/logo.png" alt="FlickPicks Logo" />
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                            <!-- Show the user a complete navbar if they are logged in, else display login/signup buttons -->
                            <?php if(isset($_SESSION['user_id'])) { ?>
                                <ul class="navbar-nav">
                                    <li class="nav-item">
                                        <a class="nav-link" href="index.php">Search</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="mypicks.php">My Picks</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="blog.php">Blog</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="https://oceana.org/resources/ways-to-give/">Profile</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="logout.php">Logout</a>
                                    </li>
                                </ul>
                            <?php } ?>
                            <?php if(!isset($_SESSION['user_id'])) { ?>
                                <ul class="navbar-nav">
                                    <li class="nav-item">
                                        <a class="nav-link" href="login.php">Login</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="signup.php">Signup</a>
                                    </li>
                                </ul>
                            <?php } ?>
                        </div>
                    </nav>
                </header>

                <!-- Blog -->
                <div class="sticky-top my-5 find-films-header" style="top: 0; padding-bottom: 20px;">
                
                    <div class="d-md-flex justify-content-between align-items-center">
                        <h1 class="display-2" id="filmSearchHeading">Blog</h1>
                        <div>
                            <a class="btn btn-primary" id="add-post-button" href="makepost.php">+ Add Post</a>
                        </div>
                    </div>
                    
                </div>

                <!-- Blog posts are dynamically added here -->
                <div class="container mb-5">
                    <div id="blog-post-container" class="row g-4">

                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if(!isset($_SESSION['user_id'])) {
            echo "<h2 style=\"color: #e5e5e5; text-align: center;\">Invalid request</h2>";
            echo "<h3 style=\"color: #e5e5e5; text-align: center;\">You must be logged in to view the blog</h3>";
        } ?>
            
        <!-- Bootstrap CDN JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        
        <script>
            fetchBlogPosts(); // call the function to fetch posts
        </script>
    </body>
</html>
