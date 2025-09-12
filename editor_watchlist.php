<?php
require 'config.php';

// Fetch watch counts for each customer and movie
$watchlist = mysqli_query($conn, "
    SELECT w.customer_email, m.id AS movie_id, m.title AS movie_title, m.image AS movie_image, COUNT(*) AS watch_count
    FROM watch_history w
    JOIN movie m ON w.movie_id = m.id
    GROUP BY w.customer_email, w.movie_id
    ORDER BY watch_count DESC
");
?>

<h2>Customer Watchlist / Watch Count</h2>

<div style="display:flex; flex-wrap:wrap; gap:15px;">
<?php if(mysqli_num_rows($watchlist) > 0): ?>
    <?php while($row = mysqli_fetch_assoc($watchlist)): ?>
        <div style="border:1px solid #ccc; border-radius:8px; padding:10px; width:200px; text-align:center; box-shadow: 2px 2px 8px rgba(0,0,0,0.1); display:flex; flex-direction:column;">
            <?php if($row['movie_image'] && file_exists('uploads/'.$row['movie_image'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($row['movie_image']); ?>" style="width:100%; height:250px; object-fit:cover; border-radius:4px;">
            <?php else: ?>
                <div style="width:100%; height:250px; background:#eee; display:flex; align-items:center; justify-content:center; border-radius:4px;">No Image</div>
            <?php endif; ?>
            <strong style="display:block; margin-top:8px;"><?php echo htmlspecialchars($row['movie_title']); ?></strong>
            <em style="display:block; margin-top:4px;"><?php echo htmlspecialchars($row['customer_email']); ?></em>
            <span style="display:block; margin-top:6px; font-weight:bold;">Watched: <?php echo $row['watch_count']; ?> times</span>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No watch history available.</p>
<?php endif; ?>
</div>
