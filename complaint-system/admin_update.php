<?php
require '../config/database.php';
if($_POST['id'] && $_POST['status']){
 $stmt=$pdo->prepare("UPDATE complaints SET status=?, updated_at=NOW() WHERE id=?");
 $stmt->execute([$_POST['status'], $_POST['id']]);
 echo "Updated!"; header("Location: ../admin/dashboard.php");
}
?>
