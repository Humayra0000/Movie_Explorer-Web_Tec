<?php
$msg="";
$editor = $_SESSION['user'];

// Update password
if(isset($_POST['update_password'])){
    $new_pass = $_POST['new_password'];
    mysqli_query($conn,"UPDATE users SET password='$new_pass' WHERE username='$editor'");
    $msg="✅ Password updated!";
}

// Update email
if(isset($_POST['update_email'])){
    $new_email = $_POST['new_email'];
    mysqli_query($conn,"UPDATE users SET email='$new_email' WHERE username='$editor'");
    $msg="✅ Email updated!";
}

// Upload / Change profile picture
if(isset($_POST['update_pic']) && isset($_FILES['profile_pic'])){
    $fileName = $_FILES['profile_pic']['name'];
    $tmpName  = $_FILES['profile_pic']['tmp_name'];
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    $allowed = ['jpg','jpeg','png','gif'];
    if(in_array(strtolower($ext), $allowed)){
        $newName = "profile_".$editor."_".time().".".$ext;
        $uploadPath = "uploads/".$newName;
        if(move_uploaded_file($tmpName, $uploadPath)){
            mysqli_query($conn,"UPDATE users SET profile_pic='$uploadPath' WHERE username='$editor'");
            $msg="✅ Profile picture updated!";
        } else {
            $msg="❌ Error uploading file!";
        }
    } else {
        $msg="❌ Only JPG, PNG, GIF allowed!";
    }
}

// Delete account
if(isset($_POST['delete_account'])){
    mysqli_query($conn,"DELETE FROM users WHERE username='$editor'");
    session_destroy();
    header("Location: index.php");
    exit;
}

// Fetch current user info
$user = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE username='$editor'"));
?>

<h2>Profile Information</h2>
<p>Username: <?php echo htmlspecialchars($user['username']); ?></p>
<p>Email: <?php echo htmlspecialchars($user['email']); ?></p>

<?php if(!empty($user['profile_pic'])): ?>
    <p><img src="<?php echo htmlspecialchars($user['profile_pic']); ?>" width="120" height="120" style="border-radius:50%;object-fit:cover;"></p>
    <h3>Change Profile Picture</h3>
<?php else: ?>
    <p><img src="uploads/default.png" width="120" height="120" style="border-radius:50%;object-fit:cover;"></p>
    <h3>Add Profile Picture</h3>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="profile_pic" accept="image/*" required>
    <input type="submit" name="update_pic" value="Upload">
</form>

<h3>Update Password</h3>
<form method="POST">
    New Password: <input type="password" name="new_password" required>
    <input type="submit" name="update_password" value="Update">
</form>

<h3>Update Email</h3>
<form method="POST">
    New Email: <input type="email" name="new_email" required>
    <input type="submit" name="update_email" value="Update">
</form>

<h3>Delete Account</h3>
<form method="POST" onsubmit="return confirm('Are you sure?');">
    <input type="submit" name="delete_account" value="Delete Account">
</form>

<p class="msg"><?php echo $msg; ?></p>
