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

        <!-- Custom Styles -->
        <link rel="stylesheet" type="text/css" href="styles/main.css">

        <!-- Custom Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,700;1,400&display=swap" rel="stylesheet">

    </head>
    
    <body>

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

            <?php
                if(isset($_SESSION['user_id'])) {
                    echo "you are logged in as:";
                    echo $_SESSION['user_id'];
                }
                else {
                    echo "you are not logged in";
                }
            ?>

            <!-- Nav Bar -->
            <header>
                <nav class="navbar navbar-expand-lg navbar-dark mb-5">
                    <a class="navbar-brand" href="./index.html">
                        <img id="logoImage" src="./resources/logo.png" alt="FlickPicks Logo" />
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">

                        <?php if(isset($_SESSION['user_id'])) { ?>
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="index.php">Search</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="mypicks.php">My Picks</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="https://www.noaa.gov/">Polls</a>
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

            <!-- Search Bar -->
            <div class="sticky-top my-5 find-films-header" style="top: 0;">
                <h1 class="display-2" id="filmSearchHeading">Find Films</h1>
                <form class="pb-5 search-form">
                    <input id="search-bar" type="text" name="search" placeholder="Search" class="px-3 py-3" />
                    <div class="search-bar-buttons">
                        <input class="btn btn-primary" id="search-button" value="Search" type="submit"/>
                        <div class="form-check form-check-inline form-switch ml-5">
                            <input id="adult-toggle" class="form-check-input" type="checkbox" role="switch" aria-labelledby="form-check-label">
                            <label id="form-check-label" class="form-check-label">Adult</label>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Next and Previous Buttons Top -->
            <div class="d-flex justify-content-between mb-5 mx-auto">
                <div>
                    <a id="top-previous-button" class="btn btn-primary mx-4" hidden>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                        </svg>
                        
                    </a>
                </div>
                <div>
                    <p id="page-indicator">

                    </p>
                </div>
                <div>
                    <a id="top-next-button" class="btn btn-primary mx-4" hidden>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                        </svg>
                        
                    </a>
                </div>
            </div>

            <!-- Search Results -->
            <div id="movie-results" class="d-flex flex-wrap">
                <div class="mx-auto text-center d-flex flex-column justify-content-center">
                    <img src="./resources/popcorn.png" alt="outline drawing of popcorn" class="mx-auto" style="width: 180px;"/>
                    <h6 id="noSearchYet">Find your favorite movies</h6>
                </div>
            </div>

            <!-- Next and Previous Buttons Bottom -->
            <div class="d-flex justify-content-center mb-5 mx-auto">
                <a id="previous-button" class="btn btn-primary mx-4" hidden>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                    </svg>
                </a>
                <a id="next-button" class="btn btn-primary mx-4" hidden>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                    </svg>
                </a>
            </div>

            

        </div>
        
        <!-- Bootstrap CDN JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        
        <!-- Custom JavaScript -->
        <script src="js/fetch-movies.js"></script>
        
    </body>
</html>
