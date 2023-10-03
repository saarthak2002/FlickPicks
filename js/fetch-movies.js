
const searchBar = document.getElementById('search-bar');
const searchButton = document.getElementById('search-button');
const nextButton = document.getElementById('next-button');
const previousButton = document.getElementById('previous-button');
const topNextButton = document.getElementById('top-next-button');
const topPreviousButton = document.getElementById('top-previous-button');
const adultCheckbox = document.getElementById('adult-toggle');
const pageIndicator = document.getElementById('page-indicator');

let page = 1;
let maxPages = 1;
let searchQuery = '';

// On clicking search button
searchButton.addEventListener('click', (event) => {
    event.preventDefault();
    searchQuery = searchBar.value;
    page = 1;
    fetch_movies(searchQuery, page);
});

// Page buttons - top and bottom
nextButton.addEventListener('click', (event) => {
    page = page + 1;
    pageIndicator.textContent = `${page}/${maxPages}`;
    fetch_movies(searchQuery, page);
});

topNextButton.addEventListener('click', (event) => {
    page = page + 1;
    pageIndicator.textContent = `${page}/${maxPages}`;
    fetch_movies(searchQuery, page);
});


previousButton.addEventListener('click', (event) => {
    if (page > 1) {
        page = page - 1;
        pageIndicator.textContent = `${page}/${maxPages}`;
        fetch_movies(searchQuery, page);
    }
});

topPreviousButton.addEventListener('click', (event) => {
    if (page > 1) {
        page = page - 1;
        pageIndicator.textContent = `${page}/${maxPages}`;
        fetch_movies(searchQuery, page);
    }
});

function truncateTitle(title) {
    if (title.length > 20) {
        return title.substring(0, 20) + '...';
    } else {
        return title;
    }
}

function fetch_movies(searchQuery, page) {
    // API call to TBDM API
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
        pageIndicator.textContent = `${page}/${maxPages}`;
        if (movies.results.length === 0) {
            displayArea.innerHTML = `
                <div id="noResultsFound" class="mx-auto text-center">
                    <h2>
                        Nothing to see here... :(
                    </h2>
                </div>
            `;
            pageIndicator.textContent = '';
        }
        movies.results.forEach(movie => { // Create a card for each search result
            
            // Result card
            const movieItem = document.createElement("div");
            movieItem.classList.add('card');
            movieItem.classList.add('mx-auto');
            movieItem.classList.add('my-3');
            movieItem.classList.add('movie-item');
            movieItem.setAttribute("style", "width: 15rem; box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px; background: none;");
            
            // Card poster image
            const posterImage = document.createElement("img");
            
            if(movie.poster_path === null) {
                posterImage.setAttribute("src", "./resources/default-card-image.png");
                posterImage.setAttribute("alt", ".default flick picks logo");
            }
            else {
                posterImage.setAttribute("src", "https://image.tmdb.org/t/p/original/" + movie.poster_path);
                posterImage.setAttribute("alt", `${movie.original_title} poster`);
            }

            posterImage.classList.add("class", "card-img-top");
    
            // Card Title
            const title = document.createElement("h5");
            title.classList.add('card-title');
            title.classList.add('text-center');
            title.textContent = truncateTitle(movie.original_title);
    
            // Card form - submit on click on card body
            const form = document.createElement("form");
            form.setAttribute("action", "php/form-handler.php");
            form.setAttribute("method", "POST");
    
            const movieTitle = document.createElement("input");
            movieTitle.setAttribute("type", "hidden");
            movieTitle.setAttribute("name", "search");
            movieTitle.setAttribute("value", movie.original_title);

            const movieId = document.createElement("input");
            movieId.setAttribute("type", "hidden");
            movieId.setAttribute("name", "id");
            movieId.setAttribute("value", movie.id);


            form.appendChild(movieTitle);
            form.append(movieId);

            movieItem.appendChild(posterImage);
            movieItem.appendChild(title);
            movieItem.appendChild(form);

            movieItem.addEventListener('click', () => {
                console.log('card clicked!');
                form.submit();
            });

            displayArea.appendChild(movieItem);
        });

        console.log(page);
        console.log(maxPages);

        // Logic to show/hide page buttons
        if (page == 1) {
            previousButton.hidden = true;
            topPreviousButton.hidden = true;
        }
        else {
            previousButton.hidden = false;
            topPreviousButton.hidden = false;
        }

        if(page < maxPages) {
            nextButton.hidden = false;
            topNextButton.hidden = false;
        }

        if(page === maxPages) {
            nextButton.hidden = true;
            topNextButton.hidden = true;
        }
    
        console.log(movies);
    });
}

