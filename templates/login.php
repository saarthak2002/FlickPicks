<!DOCTYPE html>
<html lang="en-us">
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <meta name="author" content="Saarthak Gupta">
        <meta name="description" content="A platform to find and share the movies you love with the people you love.">
        <meta name="keywords" content="Movies, Films, Search, Reviews, Ratings">   
        
        <title>FlickPicks Login</title>

        <!-- Bootstrap CSS CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"> 

        <!-- Custom Styles -->
        <link rel="stylesheet" type="text/css" href="./styles/auth.css">

        <!-- Custom Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,700;1,400&display=swap" rel="stylesheet">

    </head>
    
    <body>
        <!-- banner to display errors -->
        <?php
            if(!empty($error_message)) {
                echo "<div style=\"margin: 0;\" class=\"alert alert-danger\" role=\"alert\">";
                    echo $error_message;
                echo "</div>";
            }
        ?>
        <section class="vh-100 gradient-custom">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                        <div class="card text-white" style="border-radius: 1rem;">
                            <div class="card-body p-5 text-center">
                                <div class="mt-md-2">
                                    <!-- Logo and heading -->
                                    <div class="mx-auto mb-md-2">
                                        <img id="form-logo" src="./resources/logo.png" alt="FLickPicks logo" />
                                    </div>
                                    <h2 class="mb-2 text-uppercase">Login</h2>

                                    <!-- Login Form -->
                                    <form method="post" action="?command=login">
                                        <div class="form-outline form-white mb-4">
                                            <input type="email" id="typeEmailX" class="form-control form-control-lg" required placeholder="Email" name="email" />
                                        </div>
                                        <div class="form-outline form-white mb-4">
                                            <input type="password" id="typePasswordX" class="form-control form-control-lg" required placeholder="Password" name="password" />
                                        </div>
                                        <p class="small mb-5 pb-lg-2">Don't have an account? <a href="./signup.php" id="auth-other-link" class="fw-bold">Sign Up</a></p>
                                        <button class="btn btn-outline-light btn-lg px-5" type="submit">Login</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>
