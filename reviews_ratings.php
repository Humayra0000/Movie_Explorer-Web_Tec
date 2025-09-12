<?php
require 'config.php';

// Fetch reviews with comments
$reviews = mysqli_query($conn, "SELECT r.*, m.title as movie_title 
                               FROM review r 
                               JOIN movie m ON r.movie_id = m.id
                               WHERE r.comment IS NOT NULL AND r.comment != ''
                               ORDER BY r.id DESC");

$reviews_rows = [];
while($row=mysqli_fetch_assoc($reviews)){
    $reviews_rows[] = $row;
}

// Fetch ratings excluding the ones already in reviews with comments
$reviewed_ids = array_column($reviews_rows, 'id');
if(!empty($reviewed_ids)){
    $ids_str = implode(',', $reviewed_ids);
    $ratings = mysqli_query($conn, "SELECT r.rating, r.customer_email, m.title as movie_title 
                                    FROM review r
                                    JOIN movie m ON r.movie_id = m.id
                                    WHERE r.id NOT IN ($ids_str)
                                    ORDER BY r.id DESC");
}else{
    $ratings = mysqli_query($conn, "SELECT r.rating, r.customer_email, m.title as movie_title 
                                    FROM review r
                                    JOIN movie m ON r.movie_id = m.id
                                    ORDER BY r.id DESC");
}
?>

<h3>Customer Reviews</h3>
<table>
<tr><th>ID</th><th>Movie</th><th>Customer</th><th>Comment</th><th>Action</th></tr>
<?php if(!empty($reviews_rows)): foreach($reviews_rows as $r): ?>
<tr>
<td><?php echo $r['id'];?></td>
<td><?php echo htmlspecialchars($r['movie_title']);?></td>
<td><?php echo htmlspecialchars($r['customer_email']);?></td>
<td><?php echo htmlspecialchars($r['comment']);?></td>
<td><a href="?del_review=<?php echo $r['id'];?>" onclick="return confirm('Delete this review?')">Delete</a></td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="5">No reviews with comments.</td></tr>
<?php endif; ?>
</table>

<h3>Customer Ratings</h3>
<table>
<tr><th>Movie</th><th>Customer</th><th>Rating</th></tr>
<?php while($rate=mysqli_fetch_assoc($ratings)): ?>
<tr>
<td><?php echo htmlspecialchars($rate['movie_title']);?></td>
<td><?php echo htmlspecialchars($rate['customer_email']);?></td>
<td><?php echo $rate['rating'];?></td>
</tr>
<?php endwhile; ?>
</table>
