<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $flick_pick_id = $_POST['flick_pick_id'];
        $movie_id = $_POST['movie_id'];
    }

    echo "add movie with id $movie_id to FlickPick with id $flick_pick_id";
?>