<?php
require 'config.php';

$editor = $_SESSION['user'];
$msg = "";

// ---------- Add movie ----------
if (isset($_POST['add_movie'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $desc  = mysqli_real_escape_string($conn, $_POST['description']);

    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_name = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($_FILES["image"]["name"]));
        $target_file = $target_dir . $file_name;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $file_name;
        }
    }

    $insert = "INSERT INTO pending_movie(title, genre, description, added_by, image) 
               VALUES('$title','$genre','$desc','$editor','$image')";
    if (mysqli_query($conn, $insert)) {
        $msg = "✅ Movie submitted! Waiting for admin approval.";
    } else {
        $msg = "❌ Error: " . mysqli_error($conn);
    }
}

// ---------- Fetch pending movies ----------
$pending_movies = mysqli_query($conn, "SELECT * FROM pending_movie WHERE added_by='$editor'");
?>

<div id="add-pending-section" class="section">
    <h3>Add & Pending Movies</h3>

    <!-- Add movie form -->
    <?php if ($msg): ?>
        <p class="<?php echo strpos($msg, '✅') !== false ? 'msg' : 'error'; ?>">
            <?php echo $msg; ?>
        </p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        Title: <input type="text" name="title" required><br><br>
        Genre:
        <select name="genre">
            <option>Adventure</option>
            <option>Thriller</option>
            <option>Horror</option>
            <option>Romantic</option>
        </select><br><br>
        Description: <input type="text" name="description" required><br><br>
        Image: <input type="file" name="image" accept="image/*" required><br><br>
        <input type="submit" name="add_movie" value="Add Movie">
    </form>

    <!-- Pending movies list -->
    <h4 style="margin-top:2rem;">Your Pending Movies (Waiting for Admin Approval)</h4>
    <table>
        <tr><th>ID</th><th>Title</th><th>Genre</th><th>Description</th><th>Image</th></tr>
        <?php if ($pending_movies && mysqli_num_rows($pending_movies) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($pending_movies)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['genre']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td>
                        <?php
                        if (!empty($row['image']) && file_exists('uploads/' . $row['image'])) {
                            echo '<img src="uploads/' . $row['image'] . '" style="max-width:80px;">';
                        } else {
                            echo 'No Image';
                        }
                        ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">No pending movies.</td></tr>
        <?php endif; ?>
    </table>
</div>
