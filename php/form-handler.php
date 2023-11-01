<?php
    session_start();
    include('../Database.php');
    require_once("../Config.php");
    $db = new DatabaseConnection();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Read request parameters
        $search = $_POST['search'];
        $movie_id = $_POST['id'];
        $API_URL = "https://api.themoviedb.org/3/movie/$movie_id";
        $api_key = Config::$tmdb_api["apikey"];
        
        // Make API call to TBDM API
        $curl = curl_init($API_URL);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $api_key",
            'accept: application/json'
        ]);
        $response = curl_exec($curl);
        $responseArray = json_decode($response, true);
        curl_close($curl);

        // Check if backdrop image exists
        if (isset($responseArray['backdrop_path'])) {
            $backdropImagePath = $responseArray['backdrop_path'];
            $backgroundImageStyle = "background-image: url('https://image.tmdb.org/t/p/original/$backdropImagePath'); background-size: cover;";
        }
        else {
            $backgroundImageStyle = "";
        }

        // Check is poster image exists
        if (isset($responseArray['poster_path'])) {
            $poster = $responseArray['poster_path'];
            $posterImagePath = "https://image.tmdb.org/t/p/original$poster";
        }
        else {
            $posterImagePath = "../resources/default-card-image.png";
        }

        $tagline = $responseArray['tagline'];
        $genres = $responseArray['genres'];

        echo "<!DOCTYPE html>";
        echo "<html lang=\"en-us\">";

            echo "<head>";
                echo "<meta charset=\"utf-8\">";
                echo "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">";
                echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
                echo "<meta name=\"author\" content=\"Saarthak Gupta\">";
                echo "<meta name=\"description\" content=\"A platform to find and share the movies you love with the people you love.\">";
                echo "<meta name=\"keywords\" content=\"Movies, Films, Search, Reviews, Ratings\">";
                echo "<title>$search</title>";

                echo "<link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css\" rel=\"stylesheet\" integrity=\"sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN\" crossorigin=\"anonymous\">";

                echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../styles/main.css\">";
                echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../styles/movieInfo.css\">";
    
                echo "<link rel=\"preconnect\" href=\"https://fonts.googleapis.com\">";
                echo "<link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>";
                echo "<link href=\"https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap\" rel=\"stylesheet\">";
            echo "</head>";

            echo "<body style=\"$backgroundImageStyle\">";
                echo "<div class=\"container py-5 outer-container\">";
                    echo "<div class=\"container\ py-5 px-5 inner-container\">";
                        echo "<div class=\"row\">";
                            echo "<div class=\"col-md-3\">";

                                // Back Button
                                echo "<div id=\"back-button\">";   
                                    echo "<a href=\"../index.php\">";
                                        echo "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"32\" height=\"32\" fill=\"currentColor\" class=\"bi bi-arrow-left-circle-fill\" viewBox=\"0 0 16 16\">";
                                            echo "<path d=\"M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z\"/>";
                                        echo "</svg>";
                                        echo "Back";
                                    echo "</a>";
                                echo "</div>";
                                
                                // Movie Poster
                                echo "<img id=\"poster-img\" src=\"$posterImagePath\" alt=\"$search poster\"/>";

                                // Add Button - display only if user is logged in
                                if(isset($_SESSION['user_id'])) {
                                    echo "<form class=\"text-center mt-1\">";
                                        echo "<button type=\"button\" id=\"add-button\" class=\"btn btn-primary\" data-bs-toggle=\"modal\" data-bs-target=\"#exampleModal\">";
                                            echo "+ Add";
                                        echo "</button>";
                                    echo "</form>";
                                }

                            echo "</div>";

                            echo "<div class=\"col-md-9\">";
                                echo "<div class=\"d-md-flex justify-content-md-between align-items-center\">";

                                    // Title and tagline
                                    echo "<div>";
                                        echo "<h2 class=\"mt-2\" id=\"movie-title\">";
                                            echo "$search";
                                        echo "</h2>";
                                        echo "<p id=\"tagline\" class=\"font-italic\">$tagline</p>";
                                    echo "</div>";

                                    // Movie Rating
                                    if(isset($responseArray['vote_average'])) {
                                        $rating = round($responseArray['vote_average'], 1);
                                        echo "<div class=\"rating-container\">";
                                            echo "<span id=\"starContainer\">";
                                            echo "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"32\" height=\"32\" fill=\"currentColor\" class=\"bi bi-star-fill\" viewBox=\"0 0 16 16\" style=\"margin-right: 10px;\">";
                                                echo "<path d=\"M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z\"/>";
                                            echo "</svg>";
                                            echo "<span id=\"ratingNumber\" class=\"align-middle\" style=\"font-size: 25px;\">$rating</span>";
                                            echo "</span>";
                                        echo "</div>";
                                    }

                                echo "</div>";
                                
                                // Genre pills
                                if (isset($responseArray['genres'])) {
                                    foreach($genres as &$genre) {
                                        $genreName = $genre['name'];
                                        echo "<span class=\"badge badge-pill genre-pill\">$genreName</span>";
                                    }
                                }   

                                // Description
                                if(isset($responseArray['overview'])) {
                                    echo "<p id=\"overview-paragraph\" class=\"pt-1 lead\">";
                                        echo $responseArray['overview'];
                                    echo "</p>";
                                }
                                else {
                                    echo "<p id=\"overview-paragraph\" class=\"pt-1 lead\">";
                                        echo "No description found.";
                                    echo "</p>";
                                }
                                
                                // Links
                                echo "<div class=\"d-md-flex mb-2\">";

                                    // Website Link
                                    if(isset($responseArray['homepage']) && $responseArray['homepage'] != "") {
                                        $homepage = $responseArray['homepage'];
                                        echo "<a class=\"info-link\" href=\"$homepage\" target=\"_blank\">Website</a>";
                                    }

                                    // IMDb Link
                                    if(isset($responseArray['imdb_id'])) {
                                        $imbd_id = $responseArray['imdb_id'];
                                        echo "<a class=\"info-link\" href=\"https://www.imdb.com/title/$imbd_id/\" target=\"_blank\">IMDb</a>";
                                    }
                                echo "</div>";

                                // Additional Info
                                echo "<div class=\"d-md-flex justify-content-md-around\">";

                                    // Runtime
                                    if(isset($responseArray['runtime'])) {
                                        echo "<div class=\"pt-1 info-text\">";
                                            $runtime = $responseArray['runtime'];
                                            echo "$runtime minutes";
                                        echo "</div>";
                                    }

                                    //release_date
                                    if(isset($responseArray['release_date'])) {
                                        $timestamp = strtotime($responseArray['release_date']);
                                        $formattedDate = date("d M, Y", $timestamp);
                                        echo "<div class=\"pt-1 info-text\">";
                                            echo "Released on $formattedDate";
                                        echo "</div>";
                                    }

                                    //spoken_languages
                                    if(isset($responseArray['spoken_languages'])) {
                                        foreach($responseArray['spoken_languages'] as &$language) {
                                            $english_name = $language['english_name'];
                                            echo "<div class=\"pt-1 info-text\">";
                                                echo "$english_name";
                                            echo "</div>";
                                        }
                                    }

                                echo "</div>";
                            echo "</div>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
                // replace with for loop

                echo "<div class=\"modal top fade\" id=\"exampleModal\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\" data-bs-backdrop=\"true\" data-bs-keyboard=\"true\">
                    <div class=\"modal-dialog\">
                        <div class=\"modal-content\">
                            <div class=\"modal-header\">
                                <h5 class=\"modal-title\" id=\"exampleModalLabel\">Your FlickPicks</h5>
                                <button type=\"button\" class=\"btn-close btn-close-white\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                            </div>
                            <form action=\"add-movie.php\" method=\"post\">
                                <div class=\"modal-body\">";

                                    // display all the flickpicks the user has in the Modal
                                    if(isset($_SESSION['user_id'])) {
                                        $result = $db->query(
                                            "select * from flickpicks where user_id = $1;", 
                                            $_SESSION['user_id']
                                        );
                                        if(!empty($result)) {
                                            foreach($result as $flickpick) {
                                                echo "<div class=\"form-check\">";
                                                    echo "<input class=\"form-check-input\" type=\"radio\" name=\"flick_pick_id\" value=\"{$flickpick['id']}\" id=\"flexRadioDefault{$flickpick['id']}\">";
                                                    echo "<label class=\"form-check-label\" for=\"flexRadioDefault{$flickpick['id']}\">";
                                                        echo $flickpick['title'];
                                                    echo "</label>";
                                                echo "</div>";
                                            }
                                        } 
                                        else {
                                            echo "<div class=\"text-center\">";
                                                echo "<h2 style=\"color: #e5e5e5;\">You have no FlickPicks</h2>";
                                                echo "<h6 style=\"color: #e5e5e5;\"><a id=\"createOneNowLink\" href=\"../mypicks.php\">Create one now</a></h6>";
                                            echo "</div>";
                                        }
                                    }

                                    // Hidden form fields are used to send movie id, title, and poster to add to database
                                    echo "<input type=\"hidden\" name=\"movie_id\" value=\"$movie_id\">";
                                    echo "<input type=\"hidden\" name=\"movie_title\" value=\"$search\">";
                                    echo "<input type=\"hidden\" name=\"movie_poster\" value=\"$posterImagePath\">";
                                echo "</div>
                                <div class=\"modal-footer\">
                                    <button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">
                                        Close
                                    </button>";
                                    if(!empty($result)){
                                        echo "<button id=\"modal-save-button\" type=\"submit\" class=\"btn btn-primary\">Save changes</button>";
                                    }
                                echo "</div>
                            </form>
                        </div>
                    </div>
                </div>";

                // Bootstrap JavaScript
                echo "<script src=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js\" integrity=\"sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL\" crossorigin=\"anonymous\"></script>";
            echo "</body>";
        echo "</html>";
    }
?>