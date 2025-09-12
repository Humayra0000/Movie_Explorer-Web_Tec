<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Movie Info Website</title>

  <!-- Font Awesome Free CDN -->
  <link rel="stylesheet" href="css/ad_header.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<header class="header">
  <a href="#" class="logo">
    <i class="fa-solid fa-tv" style="color: #ffffff;"></i> CineScope
  </a>
  <nav class="navbar">
    <a href="admin_dashboard.php">Home</a>
    <a href="manage_users.php">Manage Users</a>
    <a href="movie_details.php">Movie Details</a>
    <a href="pending_movies.php">Pending Approvals</a>
    <a href="reviews.php">Reviews</a>
  </nav>
  
  <div class="icons">
    <div class="fa fa-search" id="search-btn"></div>
    <div class="fa fa-user" id="login-btn"></div>
  </div>
  
  <!-- Reusable Search Form -->
  <form class="search-form" method="GET" action="">
    <input type="text" id="search-box" name="keyword" placeholder="Search Here...." 
           value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>" required>
    <input type="hidden" name="context" value="<?php echo isset($search_context) ? $search_context : ''; ?>">
    <label for="search-box" class="fa fa-search" onclick="this.closest('form').submit()"></label>
  </form> 
   
  <!-- Profile Form -->
  <form action="#" class="profile">
    <a href="#Profile" class="box">Profile</a><br>
    Welcome, <?php echo isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']) : 'Admin'; ?> | 
    <a href="alogout.php" class="box" style="color:#fff;">Logout</a>
  </form>
</header>
<script src="js/ad_header.js"></script>
