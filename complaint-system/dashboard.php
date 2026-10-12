<?php
session_start();
require 'config/database.php';
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; }
if($_SERVER['REQUEST_METHOD']=='POST'){
  $stmt=$pdo->prepare("INSERT INTO complaints (user_id, category, subject, description, location, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
  $stmt->execute([$_SESSION['user_id'], $_POST['category'], $_POST['subject'], $_POST['description'], $_POST['location']]);
  header("Location: my_complaints.php"); exit;
}
?>
<form method="POST">
Category: <input name="category" value="Hostel"><br>
Subject: <input name="subject" value="Food Issue"><br>
Description: <textarea name="description">Mess food sari illa</textarea><br>
Location: <input name="location" value="Hostel"><br>
<button type="submit">Submit Complaint</button>
</form>
<a href="my_complaints.php">My Complaints</a>
