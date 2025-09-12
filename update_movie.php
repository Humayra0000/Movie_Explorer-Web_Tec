<?php
require 'config.php';

// Get movie by ID
if (!isset($_GET['id'])) {
    die("No movie ID provided.");
}
$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM movie WHERE id=$id");
$movie = mysqli_fetch_assoc($result);
if (!$movie) {
    die("Movie not found.");
}

// Update movie
if (isset($_POST['update_movie'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);

    $imageName = $movie['image']; // default old image

    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        $newImage = basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $newImage;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            if (!empty($movie['image']) && file_exists("uploads/" . $movie['image'])) {
                unlink("uploads/" . $movie['image']);
            }
            $imageName = $newImage;
        }
    }

    mysqli_query($conn, "UPDATE movie 
        SET title='$title', genre='$genre', description='$desc', image='$imageName' 
        WHERE id=$id");

    // ✅ Update successful → redirect to home page
    header("Location: editor_dashboard.php");  // ← তোমার home/dashboard ফাইলের নাম বসাও
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Movie</title>
    <style>
        body{
            font-family: Arial, sans-serif;
            background:#f5f5f5;
            display:flex;
            justify-content:center;
            align-items:flex-start;
            padding:30px;
        }
        .form-container{
            background:#fff;
            padding:25px 30px;
            border-radius:10px;
            box-shadow:0 2px 8px rgba(0,0,0,0.15);
            width:400px;
        }
        .form-container h2{
            text-align:center;
            margin-bottom:20px;
        }
        .form-container label{
            display:block;
            margin:10px 0 5px;
            font-weight:bold;
        }
        .form-container input[type=text],
        .form-container textarea,
        .form-container select{
            width:100%;
            padding:8px;
            border:1px solid #ccc;
            border-radius:5px;
            margin-bottom:10px;
        }
        .form-container input[type=file]{
            margin-top:5px;
        }
        .current-image{
            text-align:center;
            margin-bottom:15px;
        }
        .current-image img{
            max-width:120px;
            border-radius:5px;
            border:1px solid #ddd;
        }
        .form-container button{
            width:100%;
            padding:10px;
            background:#28a745;
            border:none;
            color:#fff;
            font-size:16px;
            border-radius:5px;
            cursor:pointer;
            transition:0.3s;
        }
        .form-container button:hover{
            background:#218838;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Movie</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($movie['title']); ?>" required>

        <label>Genre:</label>
        <select name="genre" required>
            <?php
            $genres = ['Adventure','Thriller','Anime','Cartoon','Romantic','Horror'];
            foreach($genres as $g){
                $selected = ($movie['genre'] == $g) ? 'selected' : '';
                echo "<option value='$g' $selected>$g</option>";
            }
            ?>
        </select>

        <label>Description:</label>
        <textarea name="description" rows="4" required><?php echo htmlspecialchars($movie['description']); ?></textarea>

        <div class="current-image">
            <p>Current Image:</p>
            <?php if (!empty($movie['image']) && file_exists("uploads/".$movie['image'])): ?>
                <img src="uploads/<?php echo $movie['image']; ?>">
            <?php else: ?>
                <p>No Image</p>
            <?php endif; ?>
        </div>

        <label>Upload New Image:</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit" name="update_movie">Update Movie</button>
    </form>
</div>

</body>
</html>
