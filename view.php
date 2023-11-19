<?php
    session_start();
    include('Database.php');
    if(isset($_GET['share'])) {
        $db = new DatabaseConnection();
        $share_id = $_GET['share'];
        
        $check_existing = $db->query(
            "select * from shareable where share_id = $1;",
            $share_id
        );
        if(empty($check_existing)) {
            echo "<h1 style=\"color: #e5e5e5; text-align: center;\">This FlickPick is Private or Does Not Exist</h1>";
            echo "<p style=\"color: #e5e5e5; text-align: center;\">Request the owner to share it to view movies</p>";
        }
        else {
            $flick_pick_id = $check_existing[0]['flickpick_id'];
        }
    }
    else {
        echo "invalid";
    }

    // fetch all the movies in a flickpick from the database
    function fetchFlickPickMovies($flick_pick_id) {
        $db = new DatabaseConnection();
        $res = $db->query(
            "select * from flickpicks_contents where flickpick_id = $1;",
            $flick_pick_id
        );
        return $res;
    }

    // get the information about a flickpick from the database
    function fetchFlickPickDetails($flick_pick_id) {
        $db = new DatabaseConnection();
        $res = $db->query(
            "select * from flickpicks where id = $1;",
            $flick_pick_id
        );
        return $res;
    }

    function fetchUserDetails($user_id) {
        $db = new DatabaseConnection();
        $res = $db->query(
            "select * from users where id = $1;",
            $user_id
        );
        return $res;
    }

    if(isset($flick_pick_id)) {
        $flick_pick_details = fetchFlickPickDetails($flick_pick_id);
        $user_details = fetchUserDetails($flick_pick_details[0]['user_id']);
        $flick_pick_movies = fetchFlickPickMovies($flick_pick_id); // get all the movies from the database
    }
?>

<!-- Deployed site: https://cs4640.cs.virginia.edu/uzn2up/FlickPicks/ -->

<!DOCTYPE html>
<html lang="en-us">
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <meta name="author" content="Saarthak Gupta">
        <meta name="description" content="A platform to find and share the movies you love with the people you love.">
        <meta name="keywords" content="Movies, Films, Search, Reviews, Ratings">   

        <meta property="og:title" content="<?php echo "{$flick_pick_details[0]['title']}"; ?>" />
        <meta property="og:description" content="<?php echo "{$flick_pick_details[0]['description']}"; ?>" />
        <meta property="og:image" content="<?php echo "{$flick_pick_movies[0]['poster']}"; ?>" />
        
        <title>FlickPicks</title>

        <!-- Bootstrap CSS CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"> 

        <!-- Custom Styles -->
        <link rel="stylesheet" type="text/css" href="styles/main.css">
        <link rel="stylesheet" type="text/css" href="styles/flickpickdetails.css">

        <!-- Custom Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,700;1,400&display=swap" rel="stylesheet">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <script>

        </script>

    </head>
    
    <body>
        
        <?php if(isset($flick_pick_id)) { ?>
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
                        </div>
                    </nav>
                </header>

                <!-- flickpick title and add movie button -->
                <div class="sticky-top my-5 find-films-header" style="top: 0;">
                                
                    <div class="d-md-flex justify-content-between align-items-center">
                        <?php
                            
                        ?>
                        <h3 class="display-3" id="filmSearchHeading"><?= $flick_pick_details[0]['title']?></h3>
                        <h4 style="color: #e5e5e5;">by <?php echo "{$user_details[0]["firstname"]} {$user_details[0]["lastname"]}"; ?> </h4>
                    </div>
                </div>

                <div class="my-5" id="flickpick-desc-div">
                    <p style="font-weight: 100; color: #e5e5e5; text-align: center;">
                        <?php
                            echo "{$flick_pick_details[0]['description']}";
                        ?>
                    </p>
                </div>

                <!-- Flick pick contents display -->
                <div class="container mb-5">
                    <div class="row" id="movie-list">
                        <!-- Movie rows are dynamically added here -->
                        <?php
                            // $flick_pick_movies = fetchFlickPickMovies($flick_pick_id); // get all the movies from the database
                            if(!empty($flick_pick_movies)) {
                                foreach($flick_pick_movies as $movie) {
                                    $flickpicks_contents_id = $movie['id'];

                                    echo "<div class=\"col-12 justify-content-between movie-row\">";

                                        // movie poster and title
                                        echo "<div class=\"movie-row-item\">";
                                            
                                            if($movie['poster'] === '../resources/default-card-image.png') {
                                                echo "<img src=\"resources/default-card-image.png\" alt=\"{$movie['title']} poster\" class=\"movie-image\">";
                                            }
                                            else {
                                                echo "<img src=\"{$movie['poster']}\" alt=\"{$movie['title']} poster\" class=\"movie-image\">";
                                            }
                                            
                                            echo "<h3 style=\"color: #e5e5e5;\">{$movie['title']}</h3>";
                                        echo "</div>";

                                        // Row buttons
                                        echo "<div class=\"movie-row-item-btn\">";
                                            // Info button 
                                            echo "<form action=\"php/form-handler.php\" method=\"post\" style=\"margin-right: 5px;\">";
                                                
                                                // hidden form fields to maintain state of info page
                                                echo "<input type=\"hidden\" name=\"search\" value=\"{$movie['title']}\">";
                                                echo "<input type=\"hidden\" name=\"id\" value=\"{$movie['movie_id']}\">";
                                                    
                                                echo "<button type=\"submit\" class=\"btn btn-primary\" id=\"info-button\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Information\">";
                                                    echo "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-info-circle\" viewBox=\"0 0 16 16\">";
                                                        echo "<path d=\"M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z\"></path>";
                                                        echo "<path d=\"m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z\"></path>";
                                                    echo "</svg>";
                                                echo "</button>";
                                                    
                                            echo "</form>";

                                        echo "</div>";

                                    echo "</div>";
                                    
                                }
                            }
                            else { // if there are no movies added yet, display an appropriate message
                                echo "<div id=\"noResultsFound\" class=\"mx-auto text-center\">";
                                    echo "<h2>";
                                        echo "You have not added any movies to this FlickPick";
                                    echo "</h2>";
                                    echo "<h6 style=\"color: #e5e5e5; font-weight: 200;\">Search for movies and add them to this FlickPick.</h6>";
                                echo "</div>";
                            }
                        ?>
                        
                    </div>
                </div>
            </div>
        
        <?php } ?>
        
        
        <!-- Bootstrap CDN JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        
        <!-- Script to activate bootstrap tooltips -->
        <script type="text/javascript">
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })
        </script>
    </body>
</html>
