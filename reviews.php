<?php
session_start();
require 'config.php';
if(!isset($_SESSION['user']) || $_SESSION['role']!=='admin'){ header("Location:index.php"); exit; }
if(isset($_GET['del_review'])){
    $id=intval($_GET['del_review']);
    mysqli_query($conn,"DELETE FROM review WHERE id=$id");
}
$reviews=mysqli_query($conn,"SELECT * FROM review");
?>
<!DOCTYPE html>
<html>
<head><title>Reviews</title></head>
<body>
<div class="navbar">
    <a href="admin_dashboard.php">Home</a>
    <a href="manage_users.php">Manage Users</a>
    <a href="movie_details.php">Movie Details</a>
    <a href="pending_movies.php">Pending Approvals</a>
    <a href="reviews.php">Reviews</a>
    <a href="alogout.php" style="float:right;">Logout</a>
</div>
<div class="container">
<h3>Reviews</h3>
<table border="1">
<tr><th>ID</th><th>Movie ID</th><th>Customer</th><th>Rating</th><th>Comment</th><th>Action</th></tr>
<?php while($r=mysqli_fetch_assoc($reviews)){ ?>
<tr>
<td><?php echo $r['id'];?></td>
<td><?php echo $r['movie_id'];?></td>
<td><?php echo htmlspecialchars($r['customer_email']);?></td>
<td><?php echo $r['rating'];?></td>
<td><?php echo htmlspecialchars($r['comment']);?></td>
<td><a href="?del_review=<?php echo $r['id'];?>">Delete</a></td>
</tr>
<?php } ?>
</table>
</div>
</body>
</html>
