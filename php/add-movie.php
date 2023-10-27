<?php
    session_start();
    include('../Database.php');
    function addMovieToFlickPick($flick_pick_id, $movie_id, $movie_title, $movie_poster) {
        $db = new DatabaseConnection();

        $check_existing = $db->query(
            "select * from flickpicks_contents where flickpick_id = $1 and movie_id = $2",
            $flick_pick_id,
            $movie_id
        );

        if(empty($check_existing)) {
            $db->query("insert into flickpicks_contents (flickpick_id, movie_id, title, poster) values ($1, $2, $3, $4);", 
                        $flick_pick_id, 
                        $movie_id, 
                        $movie_title,
                        $movie_poster
            );
        }
    }

    if(!isset($_SESSION['user_id'])) {
        header('Location: ../error.php');
    }
    else {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $flick_pick_id = $_POST['flick_pick_id'];
            $movie_id = $_POST['movie_id'];
            $movie_title = $_POST['movie_title'];
            $movie_poster = $_POST['movie_poster'];
            addMovieToFlickPick($flick_pick_id, $movie_id, $movie_title, $movie_poster);
        }
    
        // echo "add movie with id $movie_id to FlickPick with id $flick_pick_id name: $movie_title poster: $movie_poster";
        header('Location: ../index.php?success=1');
    }   

    
?>