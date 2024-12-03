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
    <title>Contact Us</title>
    <style>
        #map {
            height: 400px;
            width: 100%;
            margin-top: 20px;
        }
        .contact-container {
            display: flex;
            gap: 20px;
            padding: 20px;
        }
        .contact-form {
            flex: 1;
        }
        .map-container {
            flex: 1;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 8px;
        }
    </style>
</head>
<body>
    <?php require_once 'config.php'; ?>

    <div class="contact-container">
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

        <div class="map-container">
            <h2>Our Location</h2>
            <div id="map"></div>
        </div>
    </div>

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

            // Optional: Add an info window
            const infoWindow = new google.maps.InfoWindow({
                content: '<h3>U&Learning</h3><p>304 Stradbrook Avenue<br>winnipeg, Mb, R3L987</p>'
            });

            marker.addListener('click', () => {
                infoWindow.open(map, marker);
            });
        }
    </script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_KEY; ?>&callback=initMap">
    </script>
</body>
</html>

<?php
// process_index.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    // Add your email processing logic here
    // Example:
    $to = "your@email.com";
    $subject = "New Contact Form Submission";
    $emailBody = "Name: $name\nEmail: $email\nMessage: $message";
    
    mail($to, $subject, $emailBody);
    
    // Redirect back to contact page with success message
    header("Location: index.php?status=success");
    exit;
}
?>
