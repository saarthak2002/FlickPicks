<!-- Deployed site: https://cs4640.cs.virginia.edu/uzn2up/FlickPicks/ -->

<?php
    session_start();
    include('Database.php');

    $error_message = '';

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
        <link rel="stylesheet" type="text/css" href="styles/flickpickdetails.css">

        <!-- Custom Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,700;1,400&display=swap" rel="stylesheet">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <script>
            $('document').ready(function() {

                // share button handler - generate link to share FlickPick
                $('#share-button').on('click', function() {
                    var fp_id = $('#share-flick-pick-id').val();
                    console.log(fp_id);
                    $.ajax({
                        url: 'php/make-shareable.php',
                        method: 'POST',
                        data: { flick_pick_id: fp_id },
                        dataType: 'json',
                        success: function(response) {
                            console.log(response);
                            $('#shareLink').html(`<a href="${response.url}">${response.url}</a>`);
                            $('#shareModal').modal('show');
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                });

                // stop sharing button handler - make FlickPick private
                $('#stop-share-button').on('click', function() {
                    var fp_id = $('#share-flick-pick-id').val();
                    console.log(fp_id);
                    $.ajax({
                        url: 'php/stop-share.php',
                        method: 'POST',
                        data: { flick_pick_id: fp_id },
                        dataType: 'json',
                        success: function(response) {
                            console.log(response);
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                });
            });
        </script>

    </head>
    
    <body>
        
        <?php if(isset($_SESSION['user_id']) && isset($_POST['flick_pick_id'])) { ?>
            <div class="container">

                <?php
                    $flick_pick_id = $_POST['flick_pick_id'];
                ?>

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
                                        <a class="nav-link" href="profile.php">Profile</a>
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
                            $flick_pick_details = fetchFlickPickDetails($flick_pick_id);
                        ?>
                        <h3 class="display-3" id="filmSearchHeading"><?= $flick_pick_details[0]['title']?></h3>
                        <div id="header-btns">
                            <a class="btn btn-primary" id="add-movie-button" href="index.php" style="margin-right: 10px;">+ Add Movie</a>
                            <button class="btn btn-primary" id="share-button">Share</button>
                            <?php
                                echo "<input type=\"hidden\" id=\"share-flick-pick-id\" name=\"flick_pick_id\" value=\"{$flick_pick_id}\">";
                            ?>
                        </div>
                        
                    </div>
                </div>

                <!-- Flick pick contents display -->
                <div class="container mb-5">
                    <div class="row" id="movie-list">
                        <!-- Movie rows are dynamically added here -->
                        <?php
                            $flick_pick_movies = fetchFlickPickMovies($flick_pick_id); // get all the movies from the database
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
                                            
                                            // Delete Button
                                            echo "<form action=\"php/delete-movie.php\" method=\"post\" style=\"margin-left: 5px;\">";

                                                // hidden form field to pass info about which movie to delete
                                                echo "<input type=\"hidden\" name=\"flickpicks_contents_id\" value=\"$flickpicks_contents_id\">";

                                                echo "<button type=\"submit\" class=\"btn btn-outline-danger\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete\">";
                                                    echo "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-trash\" viewBox=\"0 0 16 16\">";
                                                        echo "<path d=\"M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z\"></path>";
                                                        echo "<path d=\"M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z\"></path>";
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

            <!-- Share modal -->
            <div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="shareModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="shareModalLabel">Share FlickPick</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            <!-- <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button> -->
                        </div>
                        <div class="modal-body" id="shareModalBody">
                            <!-- Response content will be displayed here -->
                            <p id="modal-first-para">You can share your FlickPick using this link:</p>
                            <p id="shareLink"></p>
                            <button type="button" class="btn btn-danger" id="stop-share-button" data-bs-dismiss="modal">Stop Sharing</button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        
        <!-- Display error if user is not logged in or invalid post data -->
        <?php 
            if(!isset($_SESSION['user_id']) || !isset($_POST['flick_pick_id'])) {
                echo "<h1 style=\"color: white; text-align:center;\">403: Forbidden</h1>";
                echo "<div class=\"row text-center\">";
                    echo "<img style=\"width: 30%;\" alt=\"you shall not pass\" src=\"https://64.media.tumblr.com/09fe9fa3ee48703d9f4e1ffa7bdf2ac5/442b319e11a844f2-76/s400x600/c77faf974244d17101b3010cf1d74e72f7243871.gifv\">";
                echo "</div>";
            }
        ?>
        
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
