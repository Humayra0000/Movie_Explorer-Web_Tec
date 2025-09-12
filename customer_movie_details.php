<?php
require 'config.php';
include 'customer_header.php';

$customer = $_SESSION['user'];
$movie_id = intval($_GET['id']);
$movie = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM movie WHERE id=$movie_id"));

// Add Rating
if(isset($_POST['rating_submit'])){
    $rating = intval($_POST['rating']);
    mysqli_query($conn,"INSERT INTO review(movie_id,customer_email,rating,comment) VALUES($movie_id,'$customer',$rating,'')");
    header("Location: customer_movie_details.php?id=$movie_id");
    exit;
}

// Add Comment/Review
if(isset($_POST['review_submit'])){
    $comment = mysqli_real_escape_string($conn,$_POST['comment']);
    mysqli_query($conn,"INSERT INTO review(movie_id,customer_email,rating,comment) VALUES($movie_id,'$customer',0,'$comment')");
    header("Location: customer_movie_details.php?id=$movie_id");
    exit;
}

// Fetch reviews
$reviews = mysqli_query($conn,"SELECT * FROM review WHERE movie_id=$movie_id ORDER BY id DESC");

// Check if already in Watchlist
$fav_check = mysqli_query($conn,"SELECT * FROM favorite WHERE customer_email='$customer' AND movie_id=$movie_id");
$is_fav = mysqli_num_rows($fav_check) > 0;
?>

<div style="display:flex; gap:20px; margin-top:20px;">
    <div style="max-width:250px;">
        <?php if($movie['image']): ?>
            <img src="uploads/<?php echo $movie['image']; ?>" style="width:100%;">
        <?php else: ?>
            <div style="width:100%;height:300px;background:#ccc;display:flex;align-items:center;justify-content:center;">No Image</div>
        <?php endif; ?>
    </div>

    <div style="max-width:700px;">
        <h2><?php echo htmlspecialchars($movie['title']); ?></h2>
        <p><strong>Genre:</strong> <?php echo htmlspecialchars($movie['genre']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($movie['description']); ?></p>

        <h3>Give Rating</h3>
        <form method="POST">
            Rating (1-5):
            <select name="rating" required>
                <option value="">Select</option>
                <?php for($i=1;$i<=5;$i++): ?>
                    <option value="<?php echo $i; ?>"><?php echo str_repeat("⭐",$i); ?></option>
                <?php endfor; ?>
            </select>
            <button type="submit" name="rating_submit">Submit Rating</button>
        </form>

        <h3>Add Comment / Review</h3>
        <form method="POST">
            <textarea name="comment" required style="width:100%;height:80px;"></textarea><br>
            <button type="submit" name="review_submit">Submit Review</button>
        </form>

        <h3>All Reviews</h3>
        <?php if(mysqli_num_rows($reviews)==0): ?>
            <p>No reviews yet.</p>
        <?php else: ?>
            <?php while($r=mysqli_fetch_assoc($reviews)): ?>
                <div style="border-bottom:1px solid #ccc; padding:10px 0;">
                    <strong><?php echo htmlspecialchars($r['customer_email']); ?></strong>
                    <?php if($r['rating']>0): ?>
                        <span style="color:orange;"><?php echo str_repeat("⭐",$r['rating']); ?></span>
                    <?php endif; ?>
                    <?php if($r['comment']): ?>
                        <p><?php echo htmlspecialchars($r['comment']); ?></p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>
