<?php
// config.php
define('GOOGLE_MAPS_API_KEY', 'AIzaSyCvwnSaDCN7BNOHnExcMLtDkzGy2AL6Ze4');
?>

<!-- contact.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="styles.css">
    <title>Contact Us - U&Learning</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        h2 {
            margin-bottom: 20px;
            color: #4CAF50;
        }

        .contact-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .contact-form,
        .map-container {
            flex: 1;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-group textarea {
            resize: vertical;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        button:hover {
            background-color: #45a049;
        }

        #map {
            height: 400px;
            width: 100%;
            margin-top: 20px;
            border-radius: 8px;
        }

        footer {
            text-align: center;
            padding: 15px 0;
            background-color: #333;
            color: white;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php require_once 'config.php'; ?>
     <!-- Navbar -->
     <header class="navbar">
        <div class="logo">U&Learning</div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About U</a></li>
            </ul>
        </nav>
    </header>
    <div class="contact-container">
        <!-- Contact Form -->
        <div class="contact-form">
            <h2>Contact Us</h2>
            <form id="contactForm" method="POST" action="process_contact.php">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit">Send Message</button>
            </form>
        </div>

        <!-- Map Section -->
        <div class="map-container">
            <h2>Our Location</h2>
            <div id="map"></div>
        </div>
    </div>

    <!-- Google Maps Script -->
    <script>
        let map;
        let marker;

        function initMap() {
            const businessLocation = {
                lat: 54.930551,
                lng: -97.431014
            };

            map = new google.maps.Map(document.getElementById('map'), {
                center: businessLocation,
                zoom: 15,
                styles: [
                    {
                        featureType: "poi",
                        elementType: "labels",
                        stylers: [{ visibility: "off" }]
                    }
                ]
            });

            marker = new google.maps.Marker({
                position: businessLocation,
                map: map,
                title: 'Our Location'
            });

            const infoWindow = new google.maps.InfoWindow({
                content: '<h3>U&Learning</h3><p>304 Stradbrook Avenue<br>Winnipeg, MB, R3L 987</p>'
            });

            marker.addListener('click', () => {
                infoWindow.open(map, marker);
            });
        }
    </script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_KEY; ?>&callback=initMap">
    </script>

    <footer>
        <p>Designed by Rakshita Dogra</p>
    </footer>
</body>
</html>
