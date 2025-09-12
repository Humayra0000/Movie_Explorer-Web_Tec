<?php
session_start();
require 'config.php';

$msg = "";

// Check if "register" page requested
$show_register = isset($_GET['register']);

// Registration (only for customers)
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $sql = "INSERT INTO users (username, email, password, role) 
            VALUES ('$username', '$email', '$password', 'customer')";
    if (mysqli_query($conn, $sql)) {
        $msg = "✅ Registration successful! You can now login.";
        $show_register = false; // go back to login form
    } else {
        $msg = "❌ Error: " . mysqli_error($conn);
    }
}

// Login (for all roles)
if (isset($_POST['login'])) {
    $username_or_email = $_POST['username_or_email'];
    $password = $_POST['password'];

    $res = mysqli_query($conn, "SELECT * FROM users 
                                WHERE (username='$username_or_email' OR email='$username_or_email') 
                                AND password='$password'");
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);

        $_SESSION['user'] = $row['username'];
        $_SESSION['role'] = $row['role'];

        if ($row['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } elseif ($row['role'] == 'editor') {
            header("Location: editor_dashboard.php");
        } else {
            header("Location: customer_dashboard.php");
        }
        exit;
    } else {
        $msg = "❌ Invalid username/email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User System</title>
	<link rel="stylesheet" href="css/login.css">
</head>
<body>
<?php if (!$show_register): ?>
    <!-- Login Form -->
    <h2>Login</h2>
    <form method="POST">
        Username or Email: <input type="text" name="username_or_email" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <input type="submit" name="login" value="Login"><br>
		<p>Don't have an account? <a href="?register=1">Register here</a></p>
    </form>
    

<?php else: ?>
    <!-- Registration Form -->
    <h2>Customer Registration</h2>
    <form method="POST">
        Username: <input type="text" name="username" required><br><br>
        Email: <input type="email" name="email" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <input type="submit" name="register" value="Register"><br>
		<p>Already have an account? <a href="index.php">Login here</a></p>
    </form>
    
<?php endif; ?>

<p style="color:red;"><?php echo $msg; ?></p>
</body>
</html>
