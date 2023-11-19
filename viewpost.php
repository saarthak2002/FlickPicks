<!-- Deployed site: https://cs4640.cs.virginia.edu/uzn2up/FlickPicks/ -->

<?php
    session_start();
    include('Database.php');
    $db = new DatabaseConnection();
    $alert = '';
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
    if(isset($_GET['id'])) {
        // Get the post details from the database
        $res = $db->query(
            "select * from blog_posts where id = $1",
            $id
        );

        // Get the comments on the post from the database
        $post_comments = $db->query(
            "select * from comments where post_id = $1",
            $id
        );
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <!-- Custom Styles -->
        <link rel="stylesheet" type="text/css" href="styles/main.css">

        <!-- Custom Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,700;1,400&display=swap" rel="stylesheet">

        <style>
            #blog-container{
                color: #e5e5e5;
            }

            #post-comment-btn.btn.btn-primary {
                border-radius: 1.9rem;
                background-color: var(--highlight-color);
                border-color: var(--highlight-color);
            }

            #post-comment-btn.btn.btn-primary:hover {
                background-color: var(--text-color-light);
                color: var(--text-color-dark);
                border-color: var(--text-color-dark);
            }

            .comment {
                margin-bottom: 20px;
            }

            .comment .comment-author {
                font-weight: bold;
                margin-bottom: 5px;
            }

            .comment .comment-text {
                word-wrap: break-word;
            }

            #comment-input {
                background-color: var(--text-color-light);
                --bs-focus-ring-color: var(--highlight-color);
            }

            #comment-input:focus {
                border-color: var(--highlight-color);
                box-shadow: 0 0 0 0.2rem rgba(252, 163, 17, 0.25);
            }

            .delete-comment-btn {
                cursor: pointer;
                border: none;
                background: none;
            }

            .delete-comment-btn:hover {
                text-decoration: underline;
            }

        </style>

    </head>
    
    <body>
        <?php if(isset($_SESSION['user_id']) && isset($_GET['id'])) { ?>       
            <!-- Display messages on homepage for various actions -->
            <?php
                if(isset($_GET['success'])) {
                    if($_GET['success'] == 1) {
                        $alert = 'Movie added to FlickPick';
                    }
                    if($_GET['success'] == 2) {
                        $alert = 'Movie already added to FlickPick';
                    }
                    if($_GET['success'] == 3) {
                        $alert = 'Deleted movie from FlickPick';
                    }
                    if($_GET['success'] == 4) {
                        $alert = 'Movie does not exist in FlickPick';
                    }
                }
            ?>

            <?php
                if (!empty($alert)) {
            ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> <?= $alert?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>  
            <?php } ?>
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
                            <!-- Show the user a complete navbar if they are logged in, else display login/signup buttons -->
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

                <!-- Blog header -->
                <div class="sticky-top my-5 find-films-header" style="top: 0; padding-bottom: 20px;">
                
                    <div class="d-md-flex justify-content-between align-items-center">
                        <h1 class="display-2" id="filmSearchHeading">Blog</h1>
                    </div>
                    
                </div>

                <!-- Display the blog post -->
                <div class="container mb-5" id="blog-container">
                    <?php 
                        echo "<h2 class=\"display-2\">{$res[0]['title']}</h2>"; 
                        echo "<h4 style=\"font-weight: 100;\">by {$res[0]['author']}</h4>";
                        echo "<p class=\"mt-5 mb-5\" style=\"font-style: italic;\">Description: {$res[0]['description']}</p>";
                        echo "<p class=\"mt-5 mb-5\" style=\"\">{$res[0]['content']}</p>";
                    ?>
                </div>

                <!-- Display the comments -->
                <div class="container mt-5 mb-5" id="blog-container">
                    <h5 class="display-5">Comments</h5>
                    <div id="comments">
                        <?php 
                            foreach($post_comments as $comment) {

                                echo "<div class=\"comment\">";
                                    echo "<div class=\"comment-author d-flex justify-content-between\">";
                                        echo "{$comment["comment_author"]}";
                                        if($_SESSION['user_id'] == $comment['user_id']) {
                                            echo "<button class=\"text-danger delete-comment-btn\" href=\"#\" data-comment-id=\"{$comment["id"]}\">Delete comment</button>";
                                        }
                                    echo "</div>";
                                    
                                    echo "<div class=\"comment-text\">{$comment["comment_text"]}</div>";
                                echo "</div>";
                            }
                        ?>
                    </div>
                    
                    <input type="text" class="form-control mt-5 mb-5" id="comment-input" placeholder="Type your comment...">
                    <input type="hidden" name="post_id" id="post_id" value="<?php echo $id;?>">
                    <button class="btn btn-primary" id="post-comment-btn">Post Comment</button>
                </div>
            </div>
        <?php } ?>
        
        <?php if(!isset($_SESSION['user_id'])) {
            echo "<h2>Invalid request</h2>";
        } ?>
            
        <!-- Bootstrap CDN JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        
        <script>
            // Event handler for posting comment button
            $("#post-comment-btn").on("click", function() {
                var post_id = $("#post_id").val();
                var comment_text = $("#comment-input").val();
                if (comment_text.trim() === "") {
                    console.log("Comment text is empty. Please enter a comment.");
                    return;
                }
                $.ajax({
                    url: 'php/post-comment.php',
                    method: 'POST',
                    data: { post_id: post_id, comment_text: comment_text },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        location.reload();
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

            // event handler for deleting comment if logged in as comment poster
            $(".delete-comment-btn").on("click", function() {
                var comment_id = $(this).data("comment-id");
                console.log("delete comment with id " + comment_id);
                $.ajax({
                    url: 'php/delete-comment.php',
                    method: 'POST',
                    data: { comment_id: comment_id },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        location.reload();
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

        </script>
    </body>
</html>
