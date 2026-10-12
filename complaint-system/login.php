<?php
session_start();
$err='';
if($_SERVER['REQUEST_METHOD']=='POST'){
 $email=trim(strtolower($_POST['email']??''));
 $pass=trim($_POST['password']??'');
 $data=json_decode(file_get_contents(__DIR__.'/fake_db.json'),true);
 $found=null;
 foreach($data['users'] as $u){
   if(strtolower($u['email'])==$email && $u['password']==$pass){ $found=$u; break; }
 }
 if($found){
  $_SESSION['user_id']=$found['id'];
  $_SESSION['user_name']=$found['name'];
  $_SESSION['role']=$found['role'];
  $_SESSION['email']=$found['email'];
  if($found['role']=='admin'){
    header('Location: admin_dashboard.php'); exit;
  } else {
    header('Location: dashboard.php'); exit;
  }
 } else {
  $err='Invalid login. Use student@college.edu / Student@123 for student';
 }
}
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login - ResolveDesk</title><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>*{box-sizing:border-box;margin:0;padding:0}body{font-family:Inter,sans-serif;background:#f1f4f9;display:flex;align-items:center;justify-content:center;min-height:100vh}.card{background:#fff;border-radius:16px;padding:28px;width:100%;max-width:360px;border:1px solid #eef0f4;box-shadow:0 4px 20px rgba(0,0,0,.06)}input{width:100%;padding:11px;border:1px solid #e5e7eb;border-radius:10px;margin-top:6px;font-size:13px}label{font-size:12px;font-weight:600;margin-top:12px;display:block}.btn{width:100%;padding:12px;background:#5a4cf1;color:#fff;border:0;border-radius:10px;font-weight:600;margin-top:16px;cursor:pointer}.demo{margin-top:14px;font-size:11px;color:#6b7280;background:#f9fafb;padding:10px;border-radius:8px;line-height:1.5}</style></head><body>
<div class="card">
<div style="text-align:center;margin-bottom:16px"><div style="width:44px;height:44px;background:#5a4cf1;border-radius:12px;margin:0 auto 8px;display:flex;align-items:center;justify-content:center;color:#fff">💬</div><div style="font-weight:800;font-size:18px">ResolveDesk</div><div style="font-size:12px;color:#6b7280">College Complaints</div></div>
<?php if($err) echo '<div style="background:#fef2f2;color:#991b1b;padding:8px;border-radius:8px;font-size:12px;margin-bottom:10px">'.$err.'</div>'; ?>
<form method="POST"><label>Email</label><input type="email" name="email" required value="student@college.edu"><label>Password</label><input type="password" name="password" required value="Student@123"><button class="btn">Login as Student</button></form>
<div class="demo"><b>🎓 Student Login:</b><br>student@college.edu / Student@123<br>gill@college.edu / Student@123<br><br><b>🛡️ Admin Login:</b><br>admin@college.edu / Admin@123</div>
</div></body></html>
