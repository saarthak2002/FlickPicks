<?php
    session_start();
    include('../Database.php');
    $db = new DatabaseConnection();
    if(isset($_SESSION['user_id'])) { // check user is logged in
        if(isset($_POST['title']) && isset($_POST['content']) && isset($_POST['description'])) { // check post request has all parameters
            // get the details of the user
            $res = $db->query(
                "select * from users where id = $1",
                $_SESSION['user_id']
            );

            $user_id = $_SESSION['user_id'];
            $author = $res[0]['firstname'] . " " . $res[0]['lastname'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $content = $_POST['content'];

            // Add the new blog post to the database
            $db->query("insert into blog_posts (user_id, author, title, description, content) values ($1, $2, $3, $4, $5);", 
                        $user_id, 
                        $author, 
                        $title,
                        $description,
                        $content
            );
            
            echo json_encode(["success" => "blog post added"]);
        }
        else {
            echo json_encode(["err" => "invalid post request"]);
        }
    }
    else {
        echo json_encode(["err" => "auth err"]);
    }
    
?>