
const searchBar = document.getElementById('search-bar');
const searchButton = document.getElementById('search-button');
const nextButton = document.getElementById('next-button');
const previousButton = document.getElementById('previous-button');
const adultCheckbox = document.getElementById('adult-toggle');

let page = 1;
let maxPages = 1;
let searchQuery = '';

searchButton.addEventListener('click', (event) => {
    event.preventDefault();
    searchQuery = searchBar.value;
    page = 1;
    fetch_movies(searchQuery, page);
})

nextButton.addEventListener('click', (event) => {
    page = page + 1;
    fetch_movies(searchQuery, page);
});

previousButton.addEventListener('click', (event) => {
    if (page > 1) {
        page = page - 1;
        fetch_movies(searchQuery, page);
    }
});

function fetch_movies(searchQuery, page) {
    console.log(adultCheckbox.checked);
    const API_URL = `https://api.themoviedb.org/3/search/movie?query=${encodeURIComponent(searchQuery)}&include_adult=${encodeURIComponent(adultCheckbox.checked)}&language=en-US&page=${encodeURIComponent(page)}`;
    console.log(API_URL);
    fetch(
        API_URL,
        {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJlZmQzZWExYzVlOGJiM2FkZWQxYzE4MmFlNGJmZGQ3NCIsInN1YiI6IjY0ZWI2OWMzMDZmOTg0MDEyZDczODJmMSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.H5Lrsay_TU4mRJ_AZM-1YL5H_e_N6-RfmESQldSxF2o',
            },    
        }
    )
    .then(response => {
        return response.json();
    })
    .then(movies => {
        const displayArea = document.getElementById("movie-results");
        displayArea.innerHTML = '';
        maxPages = movies.total_pages;
        movies.results.forEach(movie => {
            
            const movieItem = document.createElement("div");
            movieItem.classList.add('card');
            movieItem.classList.add('mx-auto');
            movieItem.classList.add('my-3');
            movieItem.classList.add('movie-item');
            movieItem.setAttribute("style", "width: 15rem; box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px; background: none;");
            
            const posterImage = document.createElement("img");
            
            if(movie.poster_path === null) {
                posterImage.setAttribute("src", "./resources/default-card-image.png");
            }
            else {
                posterImage.setAttribute("src", "https://image.tmdb.org/t/p/original/" + movie.poster_path);
            }

            posterImage.classList.add("class", "card-img-top");
    
            const title = document.createElement("h2");
            title.textContent = movie.original_title;
    
            const form = document.createElement("form");
            form.setAttribute("action", "form-handler.php");
            form.setAttribute("method", "POST");
    
            const movieTitle = document.createElement("input");
            movieTitle.setAttribute("type", "hidden");
            movieTitle.setAttribute("name", "search");
            movieTitle.setAttribute("value", movie.original_title);

            const movieId = document.createElement("input");
            movieId.setAttribute("type", "hidden");
            movieId.setAttribute("name", "id");
            movieId.setAttribute("value", movie.id);

            const addButton = document.createElement("input");
            addButton.setAttribute("type", "submit");
            addButton.setAttribute("name", "Submit");

            form.appendChild(movieTitle);
            form.append(movieId);
            form.appendChild(addButton);

            movieItem.appendChild(posterImage);
            movieItem.appendChild(title);
            movieItem.appendChild(form);

            displayArea.appendChild(movieItem);
        });

        console.log(page);
        console.log(maxPages);

        if (page == 1) {
            previousButton.hidden = true;
        }
        else {
            previousButton.hidden = false;
        }

        if(page < maxPages) {
            nextButton.hidden = false;
        }

        if(page === maxPages) {
            nextButton.hidden = true;
        }
    
        console.log(movies);
    });
}

