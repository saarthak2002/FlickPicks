<!-- Deployed site: https://cs4640.cs.virginia.edu/uzn2up/FlickPicks/ -->

<?php
    session_start();

    include('Database.php');

    $error_message = '';

    $db = new DatabaseConnection();

    if($db->dbError()) {
        $error_message = 'There was an internal database error. Please try again later.';
        include('templates/signup.php');
    }

    else {
        // If the user submits the add flickpick form in the modal
        if(isset($_GET['command']) && isset($_SESSION['user_id'])) { // if the user submits the form and a user is logged in
            $command = $_GET['command'];
            if($command === 'add') {
                if( // make sure the request has all the required values
                    isset($_POST['title']) &&
                    isset($_POST['description'])
                ) {
                    $user_id = $_SESSION['user_id'];
                    $title = $_POST['title'];
                    $description = $_POST['description'];

                    // insert into the database the new flickpick
                    $db->query(
                        "insert into flickpicks (user_id, title, description) values ($1, $2, $3);", 
                        $user_id, 
                        $title, 
                        $description
                    );
                    header("Location: mypicks.php"); // redirect the user to the same page
                }
            }
        }
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
        <link rel="stylesheet" type="text/css" href="styles/mypicks.css">

        <!-- Custom Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,700;1,400&display=swap" rel="stylesheet">

    </head>
    
    <body>
        
        <?php if(isset($_SESSION['user_id'])) { ?>
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

                
                <div class="sticky-top my-5 find-films-header" style="top: 0;">
                                
                    <div class="d-md-flex justify-content-between align-items-center">
                        <h1 class="display-2" id="filmSearchHeading">My Picks</h1>
                        <button class="btn btn-primary" id="add-pick-button" data-bs-toggle="modal" data-bs-target="#exampleModal">+ Create FlickPick</button>
                    </div>
                    
                    
                </div>

                <!-- Query the database for all flickpicks the user has created and display them -->
                <div id="my-picks" class="d-flex flex-wrap">
                    <?php
                        $result = $db->query(
                            "select * from flickpicks where user_id = $1;", 
                            $_SESSION['user_id']
                        );
                        if(!empty($result)) {
                            foreach($result as $flickpick) {
                                $res = $db->query(
                                    "select * from users where id = $1",
                                    $flickpick['user_id']
                                );
                    ?>
                                <div class="card d-flex flex-column rounded-5 px-5 py-5 mx-auto my-3 pick-item" style="width: 18rem; box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px; background: #E5E5E5;">
                                    <!-- flickpick details -->
                                    <h5 class="card-title"><?= $flickpick['title']?></h5>
                                    <h6 class="card-subtitle mb-2 text-muted"><?= "By " . $res[0]['firstname'] . " " . $res[0]['lastname']?></h6>
                                    <p class="card-text"><?= $flickpick['description']?></p>
                                    
                                    <!-- View Button -->
                                    <form class="mt-auto text-center" action="flickpickdetails.php" method="post">
                                        <input class="btn btn-primary mt-auto" id="view-button" type="submit" value="View FlickPick">
                                        <input type="hidden" name="flick_pick_id" value="<?= $flickpick['id']?>">
                                    </form>
                                </div>

                    <?php 
                            }
                        }
                    ?>

                    <!-- If there are no flickpicks, display a message -->
                    <?php
                        if(empty($result)) {
                    ?>
                            
                            <div id="noResultsFound" class="mx-auto text-center">
                                <h2>
                                    You have not created any FlickPicks
                                </h2>
                                <h6 class="" style="color: #e5e5e5; font-weight: 200;">Create one now to share the movies you love with the people you love</h6>
                            </div>
                            
                    <?php } ?>
                </div>

                <!-- Create FlickPick Modal -->
                <div class="modal top fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Create A FlickPick</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="?command=add" method="post">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control" id="title" name="title" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button id="modal-save-button" type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Create FlickPick Modal -->

            </div>
        <?php } ?>
        
        <!-- Display an error if the user is not logged in -->
        <?php 
            if(!isset($_SESSION['user_id'])) {
                echo "<h1 style=\"color: white; text-align:center;\">403: Forbidden</h1>";
                echo "<div class=\"row text-center\">";
                    echo "<img style=\"width: 30%;\" alt=\"you shall not pass\" src=\"https://64.media.tumblr.com/09fe9fa3ee48703d9f4e1ffa7bdf2ac5/442b319e11a844f2-76/s400x600/c77faf974244d17101b3010cf1d74e72f7243871.gifv\">";
                echo "</div>";
            }
        ?>
        <!-- Bootstrap CDN JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        
        
    </body>
</html>
