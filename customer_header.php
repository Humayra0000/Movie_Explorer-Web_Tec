<?php
session_start();
require 'config.php';
if(!isset($_SESSION['user']) || $_SESSION['role']!=='customer'){
    header("Location: index.php");
    exit;
}
$customer = $_SESSION['user'];
?>

<div style="background:#333;padding:10px;color:#fff;">
    <a href="customer_dashboard.php" style="color:#fff;margin-right:10px;">Home</a>
    <a href="customer_watchlist.php" style="color:#fff;margin-right:10px;">Watchlist</a>
    <a href="customer_profile.php" style="color:#fff;margin-right:10px;">Profile</a>
    <form method="GET" action="customer_dashboard.php" style="display:inline;">
        <input type="text" name="keyword" placeholder="Search movies" required>
        <input type="submit" name="search" value="Search">
    </form>
    <span style="float:right;">Welcome <?php echo htmlspecialchars($customer); ?> | <a href="customer_logout.php" style="color:#fff;">Logout</a></span>
</div>
<hr>
