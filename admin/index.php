<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/main.css">

    <title>AdminHub</title>
</head>
<body>
    <?php 
    // Include header
    if (file_exists('includes/header/header.php')) {
        include 'includes/header/header.php';
    } else {
        echo '<div class="error">Header file not found</div>';
    }
    
    // Include sidebar
    if (file_exists('includes/sidebar/sidebar.php')) {
        include 'includes/sidebar/sidebar.php';
    } else {
        echo '<div class="error">Sidebar file not found</div>';
    }
    ?>
    
    <!-- Konten utama -->
    <div id="main-content">
        <?php 
        // Default page
        $page = $_GET['page'] ?? 'dashboard';
        $pageFile = "pages/{$page}/{$page}.php";
        
        if (file_exists($pageFile)) {
            include $pageFile;
        } else {
            // Fallback to dashboard if page not found
            $fallbackFile = "pages/dashboard/dashboard.php";
            if (file_exists($fallbackFile)) {
                include $fallbackFile;
            } else {
                echo '<div class="error-page">';
                echo '<h1>Page Not Found</h1>';
                echo '<p>The requested page could not be found.</p>';
                echo '<a href="index.php" class="btn">Return to Dashboard</a>';
                echo '</div>';
            }
        }
        ?>
    </div>

    <?php 
    // Include footer
    if (file_exists('includes/footer/footer.php')) {
        include 'includes/footer/footer.php';
    } else {
        echo '<div class="error">Footer file not found</div>';
    }
    ?>

    <!-- JavaScript -->
    <script src="assets/js/header.js"></script>
    <script src="assets/js/sidebar.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>

<style>
.error, .error-page {
    padding: 20px;
    background: #ffebee;
    color: #c62828;
    border-radius: 8px;
    margin: 10px;
    text-align: center;
}

.error-page {
    max-width: 500px;
    margin: 50px auto;
}

.error-page h1 {
    color: #c62828;
    margin-bottom: 15px;
}

.error-page .btn {
    display: inline-block;
    padding: 10px 20px;
    background: var(--blue);
    color: white;
    border-radius: 5px;
    margin-top: 15px;
}
</style>