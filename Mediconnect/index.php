<?php
include_once('medi/include/config.php');

use PHPMailer\PHPMailer\Exception;

$mail = require __DIR__ . "/medi/include/mailer.php";

if (isset($_POST['submit'])) {
    $name = $_POST['fullname'];
    $email = $_POST['emailid'];
    $mobile = $_POST['mobileno'];
    $message = $_POST['description'];

    // Insert into database
    $query = mysqli_query($con, "INSERT INTO tblcontactus(fullname, email, contactno, message) VALUES('$name','$email','$mobile','$message')");

    // Send Email
    try {
        $mail->setFrom($email, $name);
        $mail->addAddress("n71671064@gmail.com", "MediConnect Admin");

        $mail->Subject = "New Contact Message from $name";
        $mail->Body = "
            <h3>Contact Form Submission</h3>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Mobile:</strong> {$mobile}</p>
            <p><strong>Message:</strong><br>{$message}</p>
        ";

        $mail->send();
        echo "<script>alert('Your information was submitted and email sent successfully!');</script>";
    } catch (Exception $e) {
        echo "<script>alert('Form saved but email could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>";
    }

    echo "<script>window.location.href ='index.php'</script>";
}

// Function to fetch the average rating
function getAverageRating($con) {
    $query = mysqli_query($con, "SELECT AVG(rating) AS average_rating FROM healthcare_facilities");
    $result = mysqli_fetch_assoc($query);
    if ($result && $result['average_rating'] !== null) {
        return round($result['average_rating'], 1); // Round to 1 decimal place
    } else {
        return 'No ratings yet';
    }
}

$averageRating = getAverageRating($con);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MediConnect</title>

    <link rel="shortcut icon" href="assets/images/fav.jpg">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/fontawsom-all.min.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css" />
    <!-- Leaflet.js CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map { height: 400px; width: 100%; margin-top: 20px; }
        body { font-family: Arial, sans-serif; padding: 20px; }
        .form-group { margin-bottom: 10px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .error { color: red; }
    </style>
</head>

<body>
    <!-- ################# Header Starts Here ####################### -->
    <header>
        <div id="nav-head" class="header-nav">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <!-- Logo: Fully Left -->
                    <div class="col-auto pl-3">
                        <div class="logo-text">MediConnect</div>
                    </div>
                    <!-- Nav Items: Centered -->
                    <div class="col text-center d-none d-md-block nav-item">
                        <ul>
                            <li><a href="#">Home</a></li>
                            <li><a href="#services">Services</a></li>
                            <li><a href="#about_us">About Us</a></li>
                            <li><a href="medi/epharmacy.php">Pharmacy</a></li>
                            <li><a href="#contact_us">Contact Us</a></li>
                            <li><a href="medi/login.php">Login</a></li>
                            <li><a href="medi/signup.php">Signup</a></li>
                        </ul>
                    </div>
                    <!-- Mobile Toggle: Right Side -->
                    <div class="col-auto d-block d-md-none">
                        <a data-toggle="collapse" data-target="#menu" href="#menu">
                            <i class="fas small-menu fa-bars"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- ################# Slider Starts Here ####################### -->
    <div class="slider-detail">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item">
                    <img class="d-block w-100" src="assets/images/slider/slider_2.png" alt="Second slide">
                    <div class="carousel-cover"></div>
                    <div class="carousel-caption vdg-cur d-none d-md-block">
                        <h5 class="animated bounceInDown">MediConnect</h5>
                    </div>
                </div>
                <div class="carousel-item active">
                    <img class="d-block w-100" src="assets/images/slider/slider_3.jpg" alt="Third slide">
                    <div class="carousel-cover"></div>
                    <div class="carousel-caption vdg-cur d-none d-md-block">
                        <h5 class="animated bounceInDown">MediConnect</h5>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>

    <!-- ######################## Search Section ######################## -->
    <section id="search">
        <h2>Find Healthcare Facilities</h2>
        <form id="searchForm">
            <div class="form-group">
                <label>Type:</label>
                <select name="type" id="type">
                    <option value="">Any</option>
                    <option value="hospital">Hospital</option>
                    <option value="clinic">Clinic</option>
                    <option value="pharmacy">Pharmacy</option>
                </select>
            </div>
            <div class="form-group">
                <label>Location:</label>
                <input type="text" name="location" id="location" placeholder="e.g., Kathmandu" />
            </div>
            <div class="form-group">
                <label>Specialty:</label>
                <input type="text" name="specialty" id="specialty" placeholder="e.g., cardiology" />
            </div>
            <div class="form-group">
                <label>Sort by:</label>
                <select name="sort" id="sort">
                    <option value="distance">Distance</option>
                    <option value="name">Name</option>
                </select>
            </div>
            <button type="submit">Search</button>
        </form>
        <div id="results" style="margin-top: 20px;"></div>
        <div id="map"></div>
    </section>

    <!-- Include Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
    document.getElementById('searchForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const resultsDiv = document.getElementById('results');
        resultsDiv.innerHTML = '';

        if (!navigator.geolocation) {
            resultsDiv.innerHTML = "<p>Geolocation not supported by your browser.</p>";
            return;
        }

        navigator.geolocation.getCurrentPosition(function(position) {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;

            // Initialize Leaflet map
            const map = L.map('map').setView([userLat, userLng], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Add user location marker
            L.marker([userLat, userLng]).addTo(map).bindPopup("Your location").openPopup();

            // Get form inputs
            const type = document.getElementById('type').value;
            const location = document.getElementById('location').value;
            const specialty = document.getElementById('specialty').value;
            const sort = document.getElementById('sort').value;

            // Map form type to OSM tags
            let osmType = '';
            if (type === 'hospital') osmType = 'hospital';
            else if (type === 'clinic') osmType = 'clinic';
            else if (type === 'pharmacy') osmType = 'pharmacy';
            else osmType = 'hospital|clinic|pharmacy';

            // Build Overpass API query
            let query = `[out:json];node["amenity"~"${osmType}"]`;
            if (specialty) {
                query += `["healthcare:speciality"~"${specialty}",i]`;
            }
            query += `(around:10000,${userLat},${userLng});out body;`;

            const url = `https://overpass-api.de/api/interpreter?data=${encodeURIComponent(query)}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (!data.elements.length) {
                        resultsDiv.innerHTML = "<p>No facilities found matching your criteria.</p>";
                        return;
                    }

                    // Process facilities
                    let facilities = data.elements.map(node => ({
                        name: node.tags.name || 'Unknown',
                        type: node.tags.amenity,
                        lat: node.lat,
                        lon: node.lon,
                        contact: node.tags.phone || node.tags.contact_phone || 'N/A',
                        address: node.tags['addr:full'] || node.tags['addr:street'] || 'N/A',
                        specialty: node.tags['healthcare:speciality'] || 'N/A',
                        distance: L.latLng(userLat, userLng).distanceTo([node.lat, node.lon]) / 1000
                    }));

                    // Filter by location (if provided)
                    if (location) {
                        facilities = facilities.filter(facility =>
                            facility.address.toLowerCase().includes(location.toLowerCase()) ||
                            facility.name.toLowerCase().includes(location.toLowerCase())
                        );
                    }

                    // Sort results
                    if (sort === 'name') {
                        facilities.sort((a, b) => a.name.localeCompare(b.name));
                    } else {
                        facilities.sort((a, b) => a.distance - b.distance);
                    }

                    // Add markers to map only
                    facilities.forEach(facility => {
                        L.marker([facility.lat, facility.lon])
                            .addTo(map)
                            .bindPopup(`
                                <strong>${facility.name}</strong><br>
                                Type: ${facility.type.charAt(0).toUpperCase() + facility.type.slice(1)}<br>
                                Address: ${facility.address}<br>
                                Specialty: ${facility.specialty}<br>
                                Contact: ${facility.contact}<br>
                                Distance: ${facility.distance.toFixed(2)} km
                            `);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    resultsDiv.innerHTML = "<p>Error fetching facilities. Please try again.</p>";
                });
        }, function(error) {
            resultsDiv.innerHTML = "<p>Geolocation error: " + error.message + "</p>";
        });
    });
    </script>

    <!-- ######################## Logins ######################## -->
    <section id="logins" class="our-blog container-fluid">
        <div class="container">
            <div class="inner-title">
                <h2>Logins</h2>
            </div>
            <div class="col-sm-12 blog-cont d-flex justify-content-center">
                <div class="row no-margin justify-content-center">
                    <div class="col-sm-4 blog-smk">
                        <div class="blog-single">
                            <img src="assets/images/patient.jpg" alt="">
                            <div class="blog-single-det">
                                <h6>User Login</h6>
                                <a href="medi/login.php" target="_blank">
                                    <button class="btn btn-success btn-sm">Click Here</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ################# Our Departments Starts Here ####################### -->
    <section id="services" class="key-features department">
        <div class="container">
            <div class="inner-title">
                <h2>Our Key Features</h2>
                <p>Explore the core functionalities that make MediConnect your trusted healthcare companion.</p>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="single-key">
                        <i class="fas fa-hospital-alt"></i>
                        <h5>Find Hospitals</h5>
                        <p>Easily search and locate hospitals based on your specific needs and location.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="single-key">
                        <i class="fas fa-pills"></i>
                        <h5>Medicine Delivery</h5>
                        <p>Order your required medicines online and have them conveniently delivered to your doorstep.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="single-key">
                        <i class="fas fa-ambulance"></i>
                        <h5>Emergency Services</h5>
                        <p>Get quick access to vital emergency contact information and service details.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="single-key">
                        <i class="fas fa-sync-alt"></i>
                        <h5>Real-Time Updates</h5>
                        <p>Stay informed with the latest updates on hospital availability and service schedules.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="single-key">
                        <i class="far fa-clinics-up"></i>
                        <h5>Find Clinics</h5>
                        <p>Discover and connect with nearby clinics offering specialized medical services.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="single-key">
                        <i class="fas fa-map-marker-alt"></i>
                        <h5>Locate Pharmacies</h5>
                        <p>Quickly find pharmacies in your vicinity with details on their operation and services.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ######################## About Us Starts Here ######################## -->
    <section id="about_us" class="about-us">
        <div class="row no-margin">
            <div class="col-sm-6 image-bg no-padding"></div>
            <div class="col-sm-6 abut-yoiu">
                <h3>About Us</h3>
                <?php
                $ret = mysqli_query($con, "select * from tblpage where PageType='aboutus'");
                while ($row = mysqli_fetch_array($ret)) {
                ?>
                    <p><?php echo $row['PageDescription']; ?>.</p>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- ######################## Contact Us Starts Here ######################## -->
    <section id="contact_us" class="contact-us-single">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 cop-ck">
                    <form method="post">
                        <h2>Contact Form</h2>
                        <div class="form-group row cf-ro align-items-center">
                            <label for="fullname" class="col-sm-3 col-form-label">Enter Name:</label>
                            <div class="col-sm-9">
                                <input type="text" id="fullname" name="fullname" class="form-control" placeholder="Enter Name" required>
                            </div>
                        </div>
                        <div class="form-group row cf-ro align-items-center">
                            <label for="emailid" class="col-sm-3 col-form-label">Email Address:</label>
                            <div class="col-sm-9">
                                <input type="email" id="emailid" name="emailid" class="form-control" placeholder="Enter Email Address" required>
                            </div>
                        </div>
                        <div class="form-group row cf-ro align-items-center">
                            <label for="mobileno" class="col-sm-3 col-form-label">Mobile Number:</label>
                            <div class="col-sm-9">
                                <input type="text" id="mobileno" name="mobileno" class="form-control" placeholder="Enter Mobile Number" required>
                            </div>
                        </div>
                        <div class="form-group row cf-ro">
                            <label for="description" class="col-sm-3 col-form-label">Enter Message:</label>
                            <div class="col-sm-9">
                                <textarea id="description" name="description" rows="5" class="form-control" placeholder="Enter Your Message" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row cf-ro">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9">
                                <button type="submit" name="submit" class="btn btn-success">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- ################# Emergency Services Section ####################### -->
    <section id="emergency-services" class="emergency-services">
        <div class="container">
            <div class="emergency-card">
                <h3>Emergency Ambulance Service</h3>
                <p id="ambulance-status">Ambulance Availability: <span id="status">Available</span></p>
                <button id="details-btn" class="btn-details">Details</button>
                <div id="ambulance-cards" class="ambulance-cards hidden"></div>
            </div>
        </div>
    </section>

    <style>
        .hidden { display: none; }
        .ambulance-cards { margin-top: 15px; display: grid; gap: 15px; }
        .emergency-card-item { padding: 15px; background: rgb(97, 153, 141); border: 1px solid #f1faff; border-radius: 8px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); }
        .btn-call-ambulance, .btn-details { display: inline-block; margin: 8px 4px; padding: 8px 16px; background-color: rgb(97, 153, 141); color: white; border: none; border-radius: 6px; text-decoration: none; cursor: pointer; }
        .error { color: red; font-weight: bold; }
    </style>

    <script>
    const detailsBtn = document.getElementById('details-btn');
    const cardsContainer = document.getElementById('ambulance-cards');

    detailsBtn.addEventListener('click', function () {
        if (!cardsContainer.classList.contains('hidden')) {
            cardsContainer.classList.add('hidden');
            cardsContainer.innerHTML = '';
            detailsBtn.textContent = 'Details';
            return;
        }

        fetch('medi/get_ambulance_details.php')
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                let html = '';
                if (Array.isArray(data)) {
                    data.forEach(item => {
                        html += `
                            <div class="emergency-card-item">
                                <h4>${item.hospital_name}</h4>
                                <p><strong>Driver:</strong> ${item.driver_name}</p>
                                <p><strong>Ambulance No:</strong> ${item.ambulance_number}</p>
                                <p><strong>Contact:</strong> <a href="tel:${item.contact}">${item.contact}</a></p>
                            </div>
                        `;
                    });
                } else if (data.message) {
                    html = `<p>${data.message}</p>`;
                } else if (data.error) {
                    html = `<p class="error">${data.error}</p>`;
                }

                cardsContainer.innerHTML = html;
                cardsContainer.classList.remove('hidden');
                detailsBtn.textContent = 'Hide Details';
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                cardsContainer.innerHTML = '<p class="error">Error loading data.</p>';
                cardsContainer.classList.remove('hidden');
                detailsBtn.textContent = 'Hide Details';
            });
    });

    window.addEventListener('scroll', function () {
        if (!cardsContainer.classList.contains('hidden')) {
            cardsContainer.classList.add('hidden');
            cardsContainer.innerHTML = '';
            detailsBtn.textContent = 'Details';
        }
    });
    </script>

    <!-- ################# Review Section ####################### -->
    <section id="reviews" class="reviews container-fluid">
        <div class="container">
            <div class="inner-title">
                <h2>Customer Reviews</h2>
                <p>Share your experience with MediConnect and our services.</p>
            </div>
            <div class="row review-form">
                <div class="col-md-8 offset-md-2">
                    <h3>Leave a Review</h3>
                    <form id="review-submission-form" method="POST" action="medi/submit_review.php">
                        <div class="form-group">
                            <label for="id">Select Facility:</label>
                            <select class="form-control" id="facility_id" name="id" required>
                                <option value="">Select Facility</option>
                                <?php
                                $facilitiesQuery = mysqli_query($con, "SELECT id, name FROM healthcare_facilities");
                                while ($facility = mysqli_fetch_assoc($facilitiesQuery)) {
                                    echo '<option value="' . $facility['id'] . '">' . htmlspecialchars($facility['name']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Your Name:</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="rating">Rating:</label>
                            <select class="form-control" id="rating" name="rating" required>
                                <option value="">Select Rating</option>
                                <option value="5">★★★★★ (Excellent)</option>
                                <option value="4">★★★★☆ (Very Good)</option>
                                <option value="3">★★★☆☆ (Good)</option>
                                <option value="2">★★☆☆☆ (Fair)</option>
                                <option value="1">★☆☆☆☆ (Poor)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="comment">Your Review:</label>
                            <textarea class="form-control" id="comment" name="comment" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </form>
                </div>
            </div>
            <div class="row existing-reviews mt-5">
                <div class="col-md-12">
                    <h3>What Our Users Say</h3>
                    <div id="review-list">
                        <?php
                        $reviewsQuery = mysqli_query($con, "
                            SELECT r.*, h.name AS facility_name
                            FROM reviews r
                            JOIN healthcare_facilities h ON r.facility_id = h.id
                            ORDER BY r.submission_date DESC
                            LIMIT 3
                        ");
                        if ($reviewsQuery && mysqli_num_rows($reviewsQuery) > 0) {
                            while ($review = mysqli_fetch_assoc($reviewsQuery)) {
                                $reviewerName = htmlspecialchars($review['name']);
                                $rating = (int)$review['rating'];
                                $comment = htmlspecialchars($review['comment']);
                                $facilityName = htmlspecialchars($review['facility_name']);
                                $date = date("F j, Y", strtotime($review['submission_date']));
                                echo '<div class="single-review border rounded-lg p-4 mb-4 shadow-sm bg-white">';
                                echo '  <div class="reviewer-info mb-2 text-sm text-gray-700">';
                                echo "    <strong class='text-base text-black'>{$reviewerName}</strong> - ";
                                for ($i = 0; $i < 5; $i++) {
                                    echo $i < $rating ? '<span style="color: gold;">★</span>' : '<span style="color: lightgray;">☆</span>';
                                }
                                echo "    <span class='review-date ml-2 text-xs text-gray-500'>{$date}</span>";
                                echo '  </div>';
                                if (!empty($comment)) {
                                    echo "<p class='text-gray-800 mb-2'>\"{$comment}\"</p>";
                                }
                                echo "<p class='facility-name text-sm text-gray-600 italic'>Facility: {$facilityName}</p>";
                                echo '</div>';
                            }
                        } else {
                            echo '<p class="text-gray-500 italic">No reviews have been submitted yet.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ################# Footer Starts Here ####################### -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <!-- Useful Links -->
            <div class="col-md-6 col-sm-12">
                <h2>Useful Links</h2>
                <ul class="list-unstyled link-list">
                    <li><a href="#about_us">About us</a><i class="fa fa-angle-right"></i></li>
                    <li><a href="#services">Services</a><i class="fa fa-angle-right"></i></li>
                    <li><a href="#logins">Logins</a><i class="fa fa-angle-right"></i></li>
                    <li><a href="#contact_us">Contact us</a><i class="fa fa-angle-right"></i></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-md-6 col-sm-12 map-img">
                <h2>Contact Us</h2>
                <address class="md-margin-bottom-40">
                    <?php
                    $ret = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType='contactus'");
                    while ($row = mysqli_fetch_array($ret)) {
                        $email = $row['Email'];
                        $subject = urlencode("Hello from MediConnect");
                        $body = urlencode("Hi,\n\nI would like to inquire about your services.");
                        ?>
                        <?php echo $row['PageDescription']; ?>
                        <strong>Phone:</strong> <?php echo htmlspecialchars($row['MobileNumber']); ?><br>
                        <strong>Email:</strong>
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=<?php echo $email; ?>&su=<?php echo $subject; ?>&body=<?php echo $body; ?>" target="_blank">
                            <?php echo htmlspecialchars($email); ?>
                        </a><br>
                    <?php } ?>
                </address>
            </div>
        </div>
    </div>
</footer>

<!-- Copy Section -->
<div class="copy">
    <div class="container">
        &copy; <?php echo date("Y"); ?> MediConnect. All rights reserved.
    </div>
</div>


    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/plugins/scroll-nav/js/jquery.easing.min.js"></script>
    <script src="assets/plugins/scroll-nav/js/scrolling-nav.js"></script>
    <script src="assets/plugins/scroll-fixed/jquery-scrolltofixed-min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>