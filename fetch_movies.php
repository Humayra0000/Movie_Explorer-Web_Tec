<?php

require 'config.php';

// Check if user is logged in and is an editor
if(!isset($_SESSION['user']) || $_SESSION['role'] !== 'editor'){
    header("Location: index.php");
    exit;
}

// Fetch all movies safely
$movies = mysqli_query($conn, "SELECT * FROM movie");

if(!$movies){
    die("Database query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>All Approved Movies</title>
    <link rel="stylesheet" href="css/fetch_movies.css"> <!-- your CSS file -->
</head>
<body>

<?php include 'header.php'; ?> <!-- your header -->

<div id="movies-section" class="section">
    <h3>All Approved Movies</h3>

    <input type="text" id="movieSearch" placeholder="Search by title or genre...">

    <div class="movie-container-vertical">
        <?php if($movies && mysqli_num_rows($movies) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($movies)): ?>
                <div class="movie-card-vertical">
                    <?php if(!empty($row['image']) && file_exists('uploads/'.$row['image'])): ?>
                        <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                    <?php else: ?>
                        <div class="no-image">No Image</div>
                    <?php endif; ?>

                    <div class="movie-info">
                        <h4><?php echo htmlspecialchars($row['title']); ?></h4>
                        <p class="genre"><b>Genre:</b> <?php echo htmlspecialchars($row['genre']); ?></p>
                        <p class="description"><?php echo htmlspecialchars($row['description']); ?></p>
                        <a href="update_movie.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No movies found.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
