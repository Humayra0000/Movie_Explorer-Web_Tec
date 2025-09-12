<?php
require 'config.php';
include 'customer_header.php';

$customer = $_SESSION['user'];

// Handle Add to Watchlist
if(isset($_POST['add_watchlist'])){
    $movie_id = intval($_POST['movie_id']);
    
    // Get movie details
    $movie_res = mysqli_query($conn,"SELECT * FROM movie WHERE id=$movie_id");
    $movie = mysqli_fetch_assoc($movie_res);

    if($movie){
        // Insert into favorite with full details
        mysqli_query($conn,"
            INSERT IGNORE INTO favorite(customer_email,movie_id,title,genre,description,image) 
            VALUES(
                '$customer',
                {$movie['id']},
                '".mysqli_real_escape_string($conn,$movie['title'])."',
                '".mysqli_real_escape_string($conn,$movie['genre'])."',
                '".mysqli_real_escape_string($conn,$movie['description'])."',
                '".mysqli_real_escape_string($conn,$movie['image'])."'
            )
        ");
    }

    // Redirect to watchlist
    header("Location: customer_watchlist.php");
    exit;
}

// Fetch distinct genres
$genres_res = mysqli_query($conn,"SELECT DISTINCT genre FROM movie");
?>

<?php while($g=mysqli_fetch_assoc($genres_res)):
    $genre = $g['genre'];
    $movies = mysqli_query($conn,"SELECT * FROM movie WHERE genre='".mysqli_real_escape_string($conn,$genre)."'");
?>
<h3><?php echo htmlspecialchars($genre); ?></h3>
<div style="display:flex;flex-wrap:wrap;">
<?php while($m=mysqli_fetch_assoc($movies)):
    // check if favorite
    $fav_check = mysqli_query($conn,"SELECT * FROM favorite WHERE customer_email='$customer' AND movie_id=".$m['id']);
    $is_fav = mysqli_num_rows($fav_check) > 0;
?>
<div style="border:1px solid #ccc;padding:10px;margin:10px;width:200px;text-align:center;">
    <?php if($m['image']): ?>
        <img src="uploads/<?php echo $m['image']; ?>" style="width:100%;height:250px;"><br>
    <?php endif; ?>
    <strong><?php echo htmlspecialchars($m['title']); ?></strong><br>
    <em><?php echo htmlspecialchars($m['genre']); ?></em><br>
    <a href="customer_movie_details.php?id=<?php echo $m['id']; ?>">View Details</a><br>

    <?php if(!$is_fav): ?>
        <form method="POST" style="margin-top:5px;">
            <input type="hidden" name="movie_id" value="<?php echo $m['id']; ?>">
            <button type="submit" name="add_watchlist">Add to Watchlist</button>
        </form>
    <?php else: ?>
        <span style="color:green;font-weight:bold;">Favorited</span>
    <?php endif; ?>
</div>
<?php endwhile; ?>
</div>
<?php endwhile; ?>
