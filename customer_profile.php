<?php
require 'config.php';
include 'customer_header.php';

$msg="";

// Update password
if(isset($_POST['update_password'])){
    $new_pass = $_POST['new_password'];
    mysqli_query($conn,"UPDATE users SET password='$new_pass' WHERE username='$customer'");
    $msg="✅ Password updated!";
}

// Update email
if(isset($_POST['update_email'])){
    $new_email = $_POST['new_email'];
    mysqli_query($conn,"UPDATE users SET email='$new_email' WHERE username='$customer'");
    $msg="✅ Email updated!";
}

// Delete account
if(isset($_POST['delete_account'])){
    mysqli_query($conn,"DELETE FROM users WHERE username='$customer'");
    session_destroy();
    header("Location: index.php");
    exit;
}

// Fetch current user info
$user = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE username='$customer'"));
?>

<h2>Profile Information</h2>
<p>Username: <?php echo htmlspecialchars($user['username']); ?></p>
<p>Email: <?php echo htmlspecialchars($user['email']); ?></p>

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

<p style="color:green;"><?php echo $msg; ?></p>
