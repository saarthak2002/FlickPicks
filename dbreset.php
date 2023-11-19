<?php

    include('Database.php');
    $db = new DatabaseConnection();

    if($db->dbError()) {
        echo "error connecting to DB";
    }
    else {
        echo "connected to db";
    }

    // $res = $db->query("DROP TABLE IF EXISTS users;");
    // $res = $db->query("CREATE TABLE users (
    //     id int GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    //     firstname text,
    //     lastname text,
    //     email text,
    //     password text
    // );");

    // $res = $db->query("DROP TABLE IF EXISTS flickpicks;");
    // $res = $db->query("CREATE TABLE flickpicks (
    //     id int GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    //     user_id int,
    //     title text,
    //     description text
    // );");

    // $res = $db->query("DROP TABLE IF EXISTS flickpicks_contents;");
    // $res = $db->query("CREATE TABLE flickpicks_contents (
    //     id int GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    //     flickpick_id int,
    //     movie_id int,
    //     title text,
    //     poster text
    // );");

    // $res = $db->query("DROP TABLE IF EXISTS shareable;");
    // $res = $db->query("CREATE TABLE shareable (
    //     id int GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    //     flickpick_id int,
    //     share_id text
    // );");

    // $res = $db->query("DROP TABLE IF EXISTS blog_posts;");
    // $res = $db->query("CREATE TABLE blog_posts (
    //     id int GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    //     user_id int,
    //     author text,
    //     title text,
    //     description text,
    //     content text
    // );");

    // $res = $db->query("DROP TABLE IF EXISTS comments;");
    // $res = $db->query("CREATE TABLE comments (
    //     id int GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    //     user_id int,
    //     post_id int,
    //     comment_author text,
    //     comment_text text
    // );");
    
    $db->close();
?>