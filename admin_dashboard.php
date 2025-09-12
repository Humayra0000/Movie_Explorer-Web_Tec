<?php
session_start();
require 'config.php';
if(!isset($_SESSION['user']) || $_SESSION['role']!=='admin'){ header("Location:index.php"); exit; }

$search_context = 'movies'; // for header hidden field

function esc($conn,$v){ return mysqli_real_escape_string($conn,$v); }

$sql = "SELECT * FROM movie";
if(isset($_GET['keyword']) && $_GET['context']==='movies'){
    $keyword = esc($conn,$_GET['keyword']);
    $sql .= " WHERE title LIKE '%$keyword%' OR genre LIKE '%$keyword%'";
}
$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Home</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/ad_header.css">
<style>
.container{ padding:12rem 9%; display:flex; flex-wrap:wrap; gap:2rem; min-height:100vh; }
.movie-card{ background:#fff; padding:1.5rem; border-radius:.5rem; box-shadow:0 .3rem .8rem rgba(0,0,0,.2); width:23rem; }
.movie-card img{ width:100%; border-radius:.5rem; margin-bottom:1rem; }
.movie-card h4{ margin-bottom:.5rem; font-size:2rem; }
.movie-card p{ font-size:1.5rem; color:#555; margin-bottom:.5rem; }
</style>
</head>
<body>
<?php include 'ad_header.php'; ?>

<div class="container">
<h2 style="width:100%; margin-bottom:2rem;">All Movies</h2>

<?php if(mysqli_num_rows($result)>0): ?>
    <?php while($row=mysqli_fetch_assoc($result)): ?>
        <div class="movie-card">
            <img src="<?php echo $row['image'] ? 'uploads/'.$row['image'] : 'uploads/no-image.png'; ?>" alt="poster">
            <h4><?php echo htmlspecialchars($row['title']); ?></h4>
            <p><b>Genre:</b> <?php echo htmlspecialchars($row['genre']); ?></p>
            <p><?php echo htmlspecialchars($row['description']); ?></p>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p style="font-size:1.8rem;">No movies found.</p>
<?php endif; ?>
</div>

<script src="js/ad_header.js"></script>
</body>
</html>
