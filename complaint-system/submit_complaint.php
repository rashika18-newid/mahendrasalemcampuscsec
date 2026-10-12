<?php
session_start();
require 'config/database.php';
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; }
if($_SERVER['REQUEST_METHOD']=='POST'){
    $user_id = $_SESSION['user_id'];
    $category = $_POST['category'];
    $subject = $_POST['subject'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $stmt = $pdo->prepare("INSERT INTO complaints (user_id, category, subject, description, location, status, created_at) VALUES (?, ?, ?, ?, ?, 'Pending', NOW())");
    if($stmt->execute([$user_id, $category, $subject, $description, $location])){
        header("Location: dashboard.php?success=1");
        exit;
    } else {
        echo "Failed to insert";
    }
}
?>
