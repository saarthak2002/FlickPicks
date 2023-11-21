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

            #submit-post-button.btn.btn-primary {
                border-radius: 1.9rem;
                background-color: var(--highlight-color);
                border-color: var(--highlight-color);
            }

            #submit-post-button.btn.btn-primary:hover {
                background-color: var(--text-color-light);
                color: var(--text-color-dark);
                border-color: var(--text-color-dark);
            }

            .form-group {
                margin-bottom: 20px;
            }

            label {
                color: #e5e5e5;
            }

            #form-container {
                margin-bottom: 50px;
            }

            #title {
                background-color: var(--text-color-light);
            }

            #description {
                background-color: var(--text-color-light);
            }

            #body {
                background-color: var(--text-color-light);
            }

            #title, #body, #description {
                background-color: var(--text-color-light);
                --bs-focus-ring-color: var(--highlight-color);
            }

            #title:focus, #body:focus, #description:focus {
                border-color: var(--highlight-color);
                box-shadow: 0 0 0 0.2rem rgba(252, 163, 17, 0.25);
            }
           
        </style>

        <script>
            var warning = '';
            function submitPost(event) {
                event.preventDefault();
                console.log('pots!!!!');

                var title = document.getElementById('title').value;
                var description = document.getElementById('description').value;
                var postContent = document.getElementById('body').value;

                // client side input validation
                $('#title-warning').text('');
                $('#description-warning').text('');
                $('#body-warning').text('');

                if (title.length < 10) {
                    $('#title-warning').text('Title must be at least 10 characters long.');
                    return;
                }

                if (description.length < 20) {
                    $('#description-warning').text('Description must be at least 20 characters long.');
                    return;
                }

                if (postContent.length < 50) {
                    $('#body-warning').text('The post body must be at least 50 characters long.');
                    return;
                }

                $.ajax({
                    url: 'php/add-post.php',
                    method: 'POST',
                    data: { title: title, content: postContent, description: description},
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        window.location='blog.php';
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        </script>
    </head>
    
    <body>
        <?php if(isset($_SESSION['user_id'])) { ?>       
            

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
                                        <a class="nav-link" href="profile.php">Profile</a>
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

                <!-- Search Bar -->
                <div class="sticky-top my-5 find-films-header" style="top: 0;">
                
                    <div class="d-md-flex justify-content-between align-items-center">
                        <h1 class="display-2" id="filmSearchHeading">New Post</h1>

                            
                    </div>
                    
                </div>

                <div class="container" id="form-container">
                    
                    <form>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" placeholder="Enter the title">
                            <div id="title-warning" class="text-danger"></div>
                        </div>

                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" id="description" placeholder="Enter the description">
                            <div id="description-warning" class="text-danger"></div>
                        </div>

                        <div class="form-group">
                            <label for="body">Body</label>
                            <textarea class="form-control" id="body" rows="5" placeholder="Enter the post content"></textarea>
                            <div id="body-warning" class="text-danger"></div>
                        </div>

                        <button id="submit-post-button" class="btn btn-primary" style="width: 150px;" onClick="submitPost(event);" >Post</button>
                    </form>
                    
                </div>

                

            </div>
        <?php } ?>
        <?php if(!isset($_SESSION['user_id'])) {
            echo "<h2>Invalid request</h2>";
        } ?>
            
        <!-- Bootstrap CDN JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        
        <script>
            // using jQuery to provide dynamic behavior (intteracting with user)
            
            // title input validation
            $('#title').on('input', function() {
                var titleText = $('#title').val();
                var warningElement = $('#title-warning');

                if (titleText.length < 10) {
                    warningElement.text('Title must be at least 10 characters long.');
                }
                else {
                    warningElement.text('');
                }
            });

            // description input validation
            $('#description').on('input', function() {
                var descriptionText = $('#description').val();
                var warningElement = $('#description-warning');

                if (descriptionText.length < 20) {
                    warningElement.text('Description must be at least 20 characters long.');
                }
                else {
                    warningElement.text('');
                }
            });

            // body content input validation
            $('#body').on('input', function() {
                var bodyText = $('#body').val();
                var warningElement = $('#body-warning');

                if (bodyText.length < 50) {
                    warningElement.text('The post body must be at least 50 characters long.');
                }
                else {
                    warningElement.text('');
                }
            });


        </script>
    </body>
</html>
