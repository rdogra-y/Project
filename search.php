<?php
// Database connection
$dsn = 'mysql:host=localhost;dbname=learning;charset=utf8';
$username = 'root';
$password = '';
$results_per_page = 5; // Number of results per page

$errors = [];
$results = [];
$total_results = 0;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$keyword = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword'], ENT_QUOTES, 'UTF-8') : '';
$category = isset($_GET['category']) ? htmlspecialchars($_GET['category'], ENT_QUOTES, 'UTF-8') : '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!empty($keyword)) {
        // Get total number of results for pagination
        $count_query = "SELECT COUNT(*) FROM Courses WHERE (title LIKE :keyword OR description LIKE :keyword)";
        if (!empty($category)) {
            $count_query .= " AND category = :category";
        }
        $count_stmt = $pdo->prepare($count_query);
        $count_stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
        if (!empty($category)) {
            $count_stmt->bindValue(':category', $category, PDO::PARAM_STR);
        }
        $count_stmt->execute();
        $total_results = $count_stmt->fetchColumn();

        // Calculate pagination offset
        $offset = ($current_page - 1) * $results_per_page;

        // Fetch paginated results
        $query = "SELECT * FROM Courses WHERE (title LIKE :keyword OR description LIKE :keyword)";
        if (!empty($category)) {
            $query .= " AND category = :category";
        }
        $query .= " LIMIT :offset, :results_per_page";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
        if (!empty($category)) {
            $stmt->bindValue(':category', $category, PDO::PARAM_STR);
        }
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':results_per_page', $results_per_page, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $errors[] = "Database error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Pages with Pagination</title>
    <style>
        /* General Body Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        /* Navbar Styling */
        header.navbar {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .navbar .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        /* Page Content Styling */
        main {
            padding: 20px;
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #4CAF50;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
            margin-bottom: 20px;
        }

        form label {
            font-weight: bold;
        }

        form input, form select, form button {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form input {
            flex: 1 1 60%;
        }

        form select {
            flex: 1 1 30%;
        }

        form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        form button:hover {
            background-color: #45a049;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #4CAF50;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Pagination Styling */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .pagination a {
            text-decoration: none;
            color: #4CAF50;
            padding: 5px 10px;
            border: 1px solid #4CAF50;
            border-radius: 5px;
        }

        .pagination a:hover {
            background-color: #4CAF50;
            color: white;
        }

        .pagination a[style*="font-weight: bold"] {
            background-color: #4CAF50;
            color: white;
        }

        /* Error Messages */
        .error {
            color: red;
            font-size: 1rem;
        }
    </style>
</head>
<body>
      <!-- Navbar -->
   <header class="navbar">
        <div class="logo">U&Learning</div>
        <nav>
            <ul class="nav-links">
                <li><a href="logout.php">Logout</a></li>
                <li><a href="create.php">create page</a></li>
                <li><a href="read.php">view page</a></li>
                <li><a href="update.php">update page</a></li>
                <li><a href="delete.php">delete page</a></li>
            </ul>
        </nav>
</header>
    <h1>Search Pages</h1>

    <!-- Search Form -->
    <form method="GET" action="search.php">
        <label for="keyword">Search by Keyword:</label>
        <input type="text" id="keyword" name="keyword" placeholder="Enter keyword" value="<?php echo htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>" required>
        
        <label for="category">Category:</label>
        <select id="category" name="category">
            <option value="">All Categories</option>
            <option value="Web Development" <?php if ($category === 'Web Development') echo 'selected'; ?>>Web Development</option>
            <option value="Programming" <?php if ($category === 'Programming') echo 'selected'; ?>>Programming</option>
            <option value="Design" <?php if ($category === 'Design') echo 'selected'; ?>>Design</option>
            <option value="Database" <?php if ($category === 'Database') echo 'selected'; ?>>Database</option>
        </select>
        <button type="submit">Search</button>
    </form>

    <hr>

    <!-- Display Errors -->
    <?php if (!empty($errors)): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Display Results -->
    <?php if (!empty($results)): ?>
        <h2>Search Results</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Course ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Category</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $result): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($result['course_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($result['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($result['description'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($result['category'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div>
            <?php
            $total_pages = ceil($total_results / $results_per_page);
            if ($current_page > 1): ?>
                <a href="?keyword=<?php echo urlencode($keyword); ?>&category=<?php echo urlencode($category); ?>&page=<?php echo $current_page - 1; ?>">Previous</a>
            <?php endif; ?>

            <?php for ($page = 1; $page <= $total_pages; $page++): ?>
                <a href="?keyword=<?php echo urlencode($keyword); ?>&category=<?php echo urlencode($category); ?>&page=<?php echo $page; ?>" <?php if ($page == $current_page) echo 'style="font-weight: bold;"'; ?>>
                    <?php echo $page; ?>
                </a>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <a href="?keyword=<?php echo urlencode($keyword); ?>&category=<?php echo urlencode($category); ?>&page=<?php echo $current_page + 1; ?>">Next</a>
            <?php endif; ?>
        </div>
    <?php elseif (!empty($keyword)): ?>
        <p>No results found for "<?php echo htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>"</p>
    <?php endif; ?>
</body>
</html>
