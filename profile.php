<!-- Deployed site: https://cs4640.cs.virginia.edu/uzn2up/FlickPicks/ -->

<?php
    session_start();
    $alert = '';
    include('Database.php');

    $db = new DatabaseConnection();

    if(isset($_SESSION['user_id'])) {
        $user_info = $db->query(
            "select * from users where id=$1",
            $_SESSION['user_id']
        );

        $num_flickpicks = count($db->query(
            "select * from flickpicks where user_id=$1",
            $_SESSION['user_id']
        ));

        $num_comments = count($db->query(
            "select * from comments where user_id=$1",
            $_SESSION['user_id']
        ));

        $num_posts = count($db->query(
            "select * from blog_posts where user_id=$1",
            $_SESSION['user_id']
        ));
    }
    
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

            #logout-button.btn.btn-primary {
                border-radius: 1.9rem;
                background-color: var(--highlight-color);
                border-color: var(--highlight-color);
            }

            #logout-button.btn.btn-primary:hover {
                background-color: var(--text-color-light);
                color: var(--text-color-dark);
                border-color: var(--text-color-dark);
            }

            .stats-item {
                color: #e5e5e5;
            }
            
        </style>

    </head>
    
    <body>
        <!-- User must be logged in to view profile -->
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

                <!-- Profile header -->
                <div class="sticky-top my-5 find-films-header" style="top: 0; padding-bottom: 20px;">
                
                    <div class="d-md-flex justify-content-between align-items-center">
                        <h1 class="display-2" id="filmSearchHeading">Profile</h1>
                        <div>
                            <a class="btn btn-primary" id="logout-button" href="logout.php">Logout</a>
                        </div>
                    </div>
                    
                </div>

                <div class="container p-1">
                    <div class="row">
                        <div class="col-md-2 d-flex align-items-center justify-content-center">
                            <div class="rounded-circle border d-flex justify-content-center align-items-center" style="width:100px;height:100px" alt="Avatar">
                                <svg xmlns="http://www.w3.org/2000/svg" height="4em" viewBox="0 0 512 512">
                                    <style>svg{fill:#fca311}</style>
                                    <path d="M256 288c79.5 0 144-64.5 144-144S335.5 0 256 0 112 64.5 112 144s64.5 144 144 144zm128 32h-55.1c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16H128C57.3 320 0 377.3 0 448v16c0 26.5 21.5 48 48 48h416c26.5 0 48-21.5 48-48v-16c0-70.7-57.3-128-128-128z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="col-md-10 d-flex flex-column justify-content-center">
                            <div class="" style="color: #e5e5e5; font-size: 2.5rem;"><?php echo "{$user_info[0]['firstname']} "; echo "{$user_info[0]['lastname']}"; ?></div>
                            <div class="text-secondary"><?php echo "{$user_info[0]['email']} "; ?></div>
                        </div>
                    </div>
                    <div class="row mt-5 mb-5">
                        <div class="col-md-12 d-flex flex-column justify-content-center align-items-center">
                            <p class="stats-item"><?php echo "Your FlickPicks: $num_flickpicks"; ?></p>
                            <p class="stats-item"><?php echo "Your Blog Posts: $num_posts"; ?></p>
                            <p class="stats-item"><?php echo "Your Comments: $num_comments"; ?></p>
                        </div>
                    </div>
                </div>

            </div>
        <?php } ?>

        <?php if(!isset($_SESSION['user_id'])) {
            echo "<h2 style=\"color: #e5e5e5; text-align: center;\">Invalid request</h2>";
            echo "<h3 style=\"color: #e5e5e5; text-align: center;\">You must be logged in to view the profile</h3>";
        } ?>
            
        <!-- Bootstrap CDN JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        
    </body>
</html>
