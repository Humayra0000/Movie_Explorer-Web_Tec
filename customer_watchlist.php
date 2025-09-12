<?php
require 'config.php';
include 'customer_header.php';

$customer = $_SESSION['user'];

// Fetch all movies in favorite table for this customer
$fav_res = mysqli_query($conn,"SELECT * FROM favorite WHERE customer_email='$customer'");
?>

<h2>My Watchlist</h2>
<div style="display:flex;flex-wrap:wrap;">
<?php while($m=mysqli_fetch_assoc($fav_res)): ?>
<div style="border:1px solid #ccc;padding:10px;margin:10px;width:200px;text-align:center; display:flex; flex-direction:column;">

    <?php if($m['image']): ?>
        <img src="uploads/<?php echo $m['image']; ?>" style="width:100%;height:250px; object-fit:cover;"><br>
    <?php endif; ?>

    <strong><?php echo htmlspecialchars($m['title']); ?></strong><br>
    <em><?php echo htmlspecialchars($m['genre']); ?></em><br>

    <!-- No description here -->
    <a href="customer_movie_details.php?id=<?php echo $m['movie_id']; ?>" style="margin-top:auto;">View Details</a>
</div>
<?php endwhile; ?>
</div>