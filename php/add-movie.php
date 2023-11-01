<?php
    session_start();
    include('../Database.php');
    
    function addMovieToFlickPick($flick_pick_id, $movie_id, $movie_title, $movie_poster) {
        $db = new DatabaseConnection();

        // query the database to see if this movie already exists in the FlickPick
        $check_existing = $db->query(
            "select * from flickpicks_contents where flickpick_id = $1 and movie_id = $2",
            $flick_pick_id,
            $movie_id
        );

        if(empty($check_existing)) {
            // if the movie does not exist, add it to the corresponding FlickPick
            $db->query("insert into flickpicks_contents (flickpick_id, movie_id, title, poster) values ($1, $2, $3, $4);", 
                        $flick_pick_id, 
                        $movie_id, 
                        $movie_title,
                        $movie_poster
            );

            return true;
        }
        else {
            return false;
        }
    }

    // check if user is logged in
    if(!isset($_SESSION['user_id'])) {
        header('Location: ../error.php');
    }
    else {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $flick_pick_id = $_POST['flick_pick_id'];
            $movie_id = $_POST['movie_id'];
            $movie_title = $_POST['movie_title'];
            $movie_poster = $_POST['movie_poster'];
            // add the movie to the database
            $ret = addMovieToFlickPick($flick_pick_id, $movie_id, $movie_title, $movie_poster);
            if($ret) { // redirect to homepage, different messages displayed based on if movie exists or not
                header("Location: ../index.php?success=1");
            }
            else {
                header("Location: ../index.php?success=2");
            }
        }
        else { // error
            header('Location: ../error.php');
        }
    }   

?>