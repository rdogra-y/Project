<?php
session_start();

// Database connection
$host = "localhost";
$db = "learning";
$user = "root"; 
$pass = ""; 

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

// Sanitize GET input
if (isset($_GET['course_id'])) {
    $course_id = filter_input(INPUT_GET, 'course_id', FILTER_SANITIZE_NUMBER_INT);

    if ($course_id) {
        $query = $conn->prepare("SELECT * FROM courses WHERE course_id = ?");
        $query->execute([$course_id]);
        $course = $query->fetch(PDO::FETCH_ASSOC);

        if (!$course) {
            echo "Course not found.";
            exit();
        }
    } else {
        echo "Invalid course ID.";
        exit();
    }
} else {
    echo "Course ID not provided.";
    exit();
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
    $user_id = $_SESSION['user_id'] ?? null; // Allow anonymous comments if no user is logged in

    if (!empty($comment)) {
        $insert_query = $conn->prepare("INSERT INTO feedback (user_id, course_id, comments) VALUES (?, ?, ?)");
        $insert_query->execute([$user_id, $course_id, $comment]);
        header("Location: Course Pages/course_details.php?course_id=$course_id");
        exit();
    } else {
        $error = "Comment cannot be empty.";
    }
}

// Fetch feedback for the course
$feedback_query = $conn->prepare("SELECT f.comments, u.username FROM feedback f LEFT JOIN users u ON f.user_id = u.user_id WHERE f.course_id = ?");
$feedback_query->execute([$course_id]);
$feedbacks = $feedback_query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8') ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .course-details {
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .course-title {
            font-size: 24px;
            font-weight: bold;
        }
        .course-description {
            margin-top: 10px;
            font-size: 18px;
        }
        .feedback-section {
            margin-top: 30px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }
        .feedback {
            margin-bottom: 15px;
        }
        .feedback-author {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .error {
            color: red;
        }
        textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="course-details">
        <div class="course-title"><?= htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8') ?></div>
        <div class="course-description"><?= htmlspecialchars($course['description'], ENT_QUOTES, 'UTF-8') ?></div>

        <!-- YouTube Video -->
        <div class="video-container">
            <h3>Watch Course Video:</h3>
            <iframe width="560" height="315" 
                src="https://www.youtube.com/embed/<?= htmlspecialchars($course['video_id'], ENT_QUOTES, 'UTF-8') ?>" 
                frameborder="0" allowfullscreen>
            </iframe>
        </div>
        
        <!-- Feedback Section -->
        <div class="feedback-section">
            <h3>Feedback</h3>

            <!-- Comment Form -->
            <form method="POST">
                <textarea name="comment" placeholder="Write your comment..." rows="4" required></textarea>
                <button type="submit">Submit Comment</button>
            </form>

            <?php if (isset($error)): ?>
                <p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>

            <!-- Display Comments -->
            <?php foreach ($feedbacks as $feedback): ?>
                <div class="feedback">
                    <div><?= htmlspecialchars($feedback['comments'], ENT_QUOTES, 'UTF-8') ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
