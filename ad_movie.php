<?php
session_start();
require 'config.php';

if(!isset($_SESSION['user']) || $_SESSION['role'] !== 'editor'){
    header("Location: index.php");
    exit;
}

$editor = $_SESSION['user'];
$msg = "";

if(isset($_POST['add_movie'])){
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $desc  = mysqli_real_escape_string($conn, $_POST['description']);

    $image = "";
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $target_dir = "uploads/";
        if(!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $file_name = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($_FILES["image"]["name"]));
        $target_file = $target_dir . $file_name;

        if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
            $image = $file_name;
        }
    }

    $insert = "INSERT INTO pending_movie(title, genre, description, added_by, image) 
               VALUES('$title', '$genre', '$desc', '$editor', '$image')";
    if(mysqli_query($conn, $insert)){
        $msg = "✅ Movie submitted! Waiting for admin approval.";
    } else {
        $msg = "❌ Error: " . mysqli_error($conn);
    }
}
?>
