<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
if(!isset($_SESSION['user']) || $_SESSION['role'] !== 'editor'){
    header("Location: index.php");
    exit;
}
$editor = $_SESSION['user'];
?>
<header>
    
    <nav>
        <span>Welcome, <?php echo htmlspecialchars($editor); ?></span>
        <a data-target="movies-section">Movies</a>
        <a data-target="add-pending-section">Add & Pending Movies</a>
        <a data-target="reviews-ratings-section">Reviews & Ratings</a>
        <a data-target="watchlist-section">Watchlist</a>
        <a data-target="notifications-section">Notifications</a>
        <a href="editor_profile.php">Profile</a>
        <a href="elogout.php">Logout</a>
    </nav>
</header>