<?php
session_start();

if (!isset($_SESSION['student'])) {
    header('Location:student_login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>U&Learning</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>
    <!-- Navbar -->
    <header class="navbar">
        <div class="logo"><a href="index_student.php">U&Learning<a></div>
        <nav>
            <ul class="nav-links">
                <li><a href="home_student.php">Home</a></li>
                <li><a href="about.php">About U</a></li>
                <li>
                <h1 class="mt-5">Welcome,to <?= htmlspecialchars($_SESSION['student']); ?> page!</h1>
                </li>
                <li>
                <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
                </li>
            </ul>
        </nav>
        
    

</li>

    </header>

    <section>
        <h2>Here is a quick<a href="Quiz/index.html"> Quiz </a>for your quick analysis and help you question you knowledge.Below we can see few of the images of projects which you can make after mastering the skills. Explore more in home page.</h2>
    </section>

    <div id="carouselExample" class="carousel slide">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="images/one.png" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="images/four.png" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="images/seven.png" class="d-block w-100" alt="...">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

   

    <!-- Footer -->
    <footer>
        <p>Designed by Rakshita Dogra</p>
    </footer>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
