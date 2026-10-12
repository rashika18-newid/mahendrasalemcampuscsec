<?php
session_start();
require 'config/database.php';
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; }
$stmt=$pdo->prepare("SELECT * FROM complaints WHERE user_id=? ORDER BY id DESC");
$stmt->execute([$_SESSION['user_id']]);
$rows=$stmt->fetchAll();
echo "<h2>My Complaints - MySQL Live (".count($rows).")</h2>";
echo "<table border=1 cellpadding=10><tr><th>ID</th><th>Subject</th><th>Status</th><th>Admin Response</th></tr>";
foreach($rows as $r){
  echo "<tr><td>{$r['id']}</td><td>{$r['subject']}</td><td><b>{$r['status']}</b></td><td>{$r['admin_response']}</td></tr>";
}
echo "</table><br><a href='dashboard.php'>Back</a>";
?>
