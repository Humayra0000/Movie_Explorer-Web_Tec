<?php
session_start();
require 'config.php';
if(!isset($_SESSION['user']) || $_SESSION['role']!=='admin'){ 
    header("Location:index.php"); 
    exit; 
}
function esc($conn,$v){ return mysqli_real_escape_string($conn,$v); }

// --------- APPROVE ----------
if(isset($_GET['approve'])){
    $id = intval($_GET['approve']);
    $res = mysqli_query($conn,"SELECT * FROM pending_movie WHERE id=$id");
    if($res && mysqli_num_rows($res)>0){
        $row = mysqli_fetch_assoc($res);

        $title = esc($conn,$row['title']);
        $genre = esc($conn,$row['genre']);
        $desc  = esc($conn,$row['description']);
        $added = esc($conn,$row['added_by']);
        $img   = !empty($row['image']) ? "'".esc($conn,$row['image'])."'" : "NULL";

        $insert = "INSERT INTO movie(title,genre,description,image,added_by)
                   VALUES('$title','$genre','$desc',$img,'$added')";
        if(!mysqli_query($conn,$insert)){
            die("Insert Error: ".mysqli_error($conn));
        }

        mysqli_query($conn,"DELETE FROM pending_movie WHERE id=$id");
        mysqli_query($conn,"INSERT INTO notification(editor,message) 
                            VALUES('$added','Your movie \"$title\" has been approved by admin.')");
    }
}


// --------- REJECT ----------
if(isset($_GET['reject'])){
    $id = intval($_GET['reject']);
    $res = mysqli_query($conn,"SELECT * FROM pending_movie WHERE id=$id");
    if($res && mysqli_num_rows($res)>0){
        $row = mysqli_fetch_assoc($res);
        if(!empty($row['image']) && file_exists('uploads/'.$row['image'])){
            @unlink('uploads/'.$row['image']);
        }
        mysqli_query($conn,"DELETE FROM pending_movie WHERE id=$id");
        mysqli_query($conn,"INSERT INTO notification(editor,message) 
                            VALUES('{$row['added_by']}','Your movie \"{$row['title']}\" has been rejected by admin.')");
    }
}


// --------- ALL PENDING MOVIES ----------
$pending = mysqli_query($conn,"SELECT * FROM pending_movie");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pending Movies</title>
</head>
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
<h3>Pending Movies</h3>
<table border="1">
<tr><th>ID</th><th>Title</th><th>Genre</th><th>Added By</th><th>Actions</th></tr>
<?php while($p=mysqli_fetch_assoc($pending)){ ?>
<tr>
<td><?php echo $p['id'];?></td>
<td><?php echo htmlspecialchars($p['title']);?></td>
<td><?php echo htmlspecialchars($p['genre']);?></td>
<td><?php echo htmlspecialchars($p['added_by']);?></td>
<td>
    <a href="?approve=<?php echo $p['id']; ?>" onclick="return confirm('Approve this movie?')">Approve</a> |
    <a href="?reject=<?php echo $p['id']; ?>" onclick="return confirm('Reject this movie?')">Reject</a>
</td>
</tr>
<?php } ?>
</table>
</div>
</body>
</html>
