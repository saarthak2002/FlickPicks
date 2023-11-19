<?php
    session_start();
    include('../Database.php');
    $db = new DatabaseConnection();
    if(isset($_SESSION['user_id'])) { // check if user is logged in
        
        $res = $db->query("SELECT * FROM blog_posts LIMIT 20;"); // get blog posts from the database
            
        echo json_encode($res);
        
    }
    else {
        echo json_encode(["err" => "auth err"]);
    }
    
?>