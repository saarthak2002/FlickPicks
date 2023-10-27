<?php

    session_start(); // join the existing session
    session_destroy(); // delete it
    header("Location: index.php"); // redirect to home page
    
?>