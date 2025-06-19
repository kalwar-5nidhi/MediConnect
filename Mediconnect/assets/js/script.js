// script.js (Consolidated and with lowercase type)
$(document).ready(function() {
    var w = window.innerWidth;

    if (w > 767) {
        $('#menu-jk').scrollToFixed();
    } else {
        $('#menu-jk').scrollToFixed();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('search-form');
    const searchResultsDiv = document.getElementById('search-results');

    searchForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const type = document.getElementById('search-type').value.toLowerCase(); // Convert to lowercase
        const location = document.getElementById('search-location').value;
        const specialty = document.getElementById('search-specialty').value;
        const sort = document.getElementById('sort-by').value;

        const formData = new URLSearchParams();
        formData.append('type', type);
        formData.append('location', location);
        formData.append('specialty', specialty);
        formData.append('sort', sort);

        fetch('search.php?' + formData.toString())
            .then(response => response.json())
            .then(data => {
                searchResultsDiv.innerHTML = ''; // Clear previous results
                if (data.error) {
                    searchResultsDiv.innerHTML = `<p class="error">${data.error}</p>`;
                } else if (data.length > 0) {
                    let resultsHTML = '<h3>Search Results:</h3><ul>';
                    data.forEach(facility => {
                        resultsHTML += `<li>
                            <strong>${facility.name}</strong> (${facility.type})<br>
                            Location: ${facility.location}<br>
                            Specialty: ${facility.specialty || 'N/A'}<br>
                            Status: ${facility.operational_status}<br>
                            Rating: ${facility.rating || 'N/A'}<br>
                            Contact: ${facility.contact || 'N/A'}
                        </li>`;
                    });
                    resultsHTML += '</ul>';
                    searchResultsDiv.innerHTML = resultsHTML;
                } else {
                    searchResultsDiv.innerHTML = '<p>No results found.</p>';
                }
            })
            .catch(error => {
                searchResultsDiv.innerHTML = '<p class="error">An error occurred while fetching results.</p>';
                console.error('Error fetching search results:', error);
            });
    });


    // User's location (default to Kathmandu if geolocation fails)
    let userLocation = { lat: 27.7172, lng: 85.3240 };

    // Get user's location dynamically
    navigator.geolocation.getCurrentPosition(
        (position) => {
            userLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude,
            };
        },
        (error) => {
            console.error("Error getting user location:", error);
        }
    );

    // Handle location input suggestions
    const locationInput = document.getElementById("search-location");
    const suggestionsBox = document.getElementById("suggestions");

    // Sample locations (can be fetched from a database or API)
    const locations = [
        "Kathmandu", "Pokhara", "Lalitpur", "Bhaktapur", "Biratnagar",
        "Dharan", "Butwal", "Janakpur", "Chitwan", "Hetauda",
        "Nepalgunj", "Dhangadhi", "Itahari", "Birgunj", "Bharatpur"
    ];

    locationInput.addEventListener("input", function() {
        const inputValue = this.value.toLowerCase();
        suggestionsBox.innerHTML = ""; // Clear previous suggestions

        if (inputValue) {
            const filteredLocations = locations.filter(loc =>
                loc.toLowerCase().includes(inputValue)
            );

            if (filteredLocations.length) {
                suggestionsBox.style.display = "block";
                filteredLocations.forEach(loc => {
                    const div = document.createElement("div");
                    div.textContent = loc;
                    div.classList.add("suggestion-item");
                    div.addEventListener("click", function() {
                        locationInput.value = loc;
                        suggestionsBox.style.display = "none";
                    });
                    suggestionsBox.appendChild(div);
                });
            } else {
                suggestionsBox.style.display = "none";
            }
        } else {
            suggestionsBox.style.display = "none";
        }
    });

    // Hide suggestions when clicking outside
    document.addEventListener("click", function(event) {
        if (!locationInput.contains(event.target) && !suggestionsBox.contains(event.target)) {
            suggestionsBox.style.display = "none";
        }
    });

    // --- REMAINING EVENT LISTENERS (Review Form, Emergency Services, Filter Buttons) ---
    document.getElementById('review-form').addEventListener('submit', function(e) {
        e.preventDefault();
        let name = document.getElementById('reviewer-name').value;
        let text = document.getElementById('review-text').value;
        let rating = document.getElementById('review-rating').value;

        let reviewItem = document.createElement('div');
        reviewItem.classList.add('review-item');
        reviewItem.innerHTML = `<strong>${name}</strong> (${"★".repeat(rating) + "☆".repeat(5 - rating)}): <p>${text}</p>`;
        document.getElementById('reviews-list').appendChild(reviewItem);
        document.getElementById('review-form').reset();
    });

    let ambulanceStatus = document.getElementById('ambulance-status');
    let emergencySection = document.getElementById('emergency-services');
    let ambulanceDetails = document.getElementById('ambulance-details');

    document.getElementById('details-btn').addEventListener('click', function() {
        ambulanceDetails.classList.toggle('hidden');
    });

    let isAmbulanceAvailable = true; // Example, replace with dynamic data
    let ambulancePhoneNumber = "+9779817768178"; // Replace with your ambulance number

    function updateAmbulanceStatus() {
        let statusText = isAmbulanceAvailable ? "Available" : "Not Available";
        document.getElementById('status').textContent = statusText;
    }

    updateAmbulanceStatus();

    let lastScrollPosition = 0;

    window.addEventListener('scroll', function() {
        let currentScrollPosition = window.pageYOffset || document.documentElement.scrollTop;

        if (currentScrollPosition < lastScrollPosition) {
            emergencySection.classList.add('show-emergency');
        } else {
            emergencySection.classList.remove('show-emergency');
        }

        lastScrollPosition = currentScrollPosition;
    });

    $(".filter-button").click(function() {
        var value = $(this).attr('data-filter');

        if (value == "all") {
            $('.filter').show('1000');
        } else {
            $(".filter").not('.' + value).hide('3000');
            $('.filter').filter('.' + value).show('3000');
        }
    });

    if ($(".filter-button").removeClass("active")) {
        $(this).removeClass("active");
    }
    $(this).addClass("active");
});