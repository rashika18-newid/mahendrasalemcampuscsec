<?php
session_start();
if(!isset($_SESSION['user_id'])){ header('Location: login.php'); exit; }
$id=intval($_GET['id']??0);
$path=__DIR__.'/fake_db.json';
$data=json_decode(file_get_contents($path),true);
if(!$data){ die("DB missing"); }

# Save admin update
if($_SERVER['REQUEST_METHOD']=='POST' && ($_SESSION['role']??'')=='admin'){
  $newStatus=trim($_POST['new_status']??'Pending');
  $comment=trim($_POST['history_comment']??'');
  foreach($data['complaints'] as &$c){
    if($c['id']==$id){
      $c['status']=$newStatus;
      if($comment!=='') $c['admin_response']=$comment;
      if(!isset($c['history'])) $c['history']=[];
      $c['history'][]=['status'=>$newStatus,'comment'=>$comment,'at'=>date('Y-m-d H:i:s'),'by'=>'Administrator'];
      break;
    }
  }
  file_put_contents($path,json_encode($data,JSON_PRETTY_PRINT));
  header('Location: complaint_details.php?id='.$id.'&saved=1'); exit;
}

$c=null; foreach($data['complaints'] as $x){ if($x['id']==$id){ $c=$x; break; } }
if(!$c){ header('Location: admin_complaints.php'); exit; }
$isAdmin = ($_SESSION['role']??'')=='admin';
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>CMP-<?php echo str_pad($c['id'],6,'0',STR_PAD_LEFT); ?></title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}body{font-family:Inter,sans-serif;background:#f1f4f9;display:flex;min-height:100vh}
.sidebar{width:236px;background:#0e0f2a;color:#9ca3af;padding:12px 10px;display:flex;flex-direction:column;flex-shrink:0;height:100vh;position:sticky;top:0}
.logo{padding:6px 8px;display:flex;gap:10px;align-items:center}.logo-i{width:32px;height:32px;background:#6366f1;border-radius:9px;display:flex;align-items:center;justify-content:center;color:#fff}
.admin-badge{margin:12px 6px;background:rgba(251,191,36,.12);border:1px solid rgba(251,191,36,.15);border-radius:10px;padding:10px 12px;display:flex;gap:8px;align-items:center;color:#fbbf24;font-size:12px;font-weight:600}
.menu-t{font-size:9.5px;opacity:.35;margin:16px 10px 6px;font-weight:700;letter-spacing:.9px}.m-item{display:flex;gap:10px;padding:9px 11px;border-radius:10px;color:rgba(255,255,255,.5);text-decoration:none;font-size:12.5px;margin-bottom:2px}.m-item.active{background:#5a4cf1;color:#fff}
.main{flex:1;min-width:0}.topbar{padding:12px 18px;background:#fff;border-bottom:1px solid #e9ecf2;display:flex;justify-content:space-between;align-items:center}
.content{padding:16px 18px}.grid{display:grid;grid-template-columns:1.6fr .85fr;gap:12px}
.card{background:#fff;border-radius:14px;border:1px solid #eef0f4;padding:14px 16px;box-shadow:0 1px 2px rgba(0,0,0,.02)}
.badge{padding:3px 9px;border-radius:10px;font-size:10.5px;font-weight:600;display:inline-block}.b-pending{background:#fef3c7;color:#92400e}.b-host{background:#eef2ff;color:#4338ca}
select,textarea{width:100%;padding:9px 10px;border:1px solid #e5e7eb;border-radius:9px;font-size:12px;font-family:Inter}
textarea{resize:vertical;min-height:70px}
.btn-save{background:#4f46e5;color:#fff;border:0;padding:8px 14px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;display:flex;gap:6px;align-items:center}
.toast{position:fixed;bottom:16px;right:16px;background:#111827;color:#fff;padding:10px 14px;border-radius:12px;font-size:11.5px;display:flex;gap:8px;box-shadow:0 12px 28px rgba(0,0,0,.28);border-left:3px solid #22c55e;z-index:99}
</style></head><body>
<div class="sidebar">
<div class="logo"><div class="logo-i">💬</div><div><div style="font-weight:700;color:#fff;font-size:13px">ResolveDesk</div><div style="font-size:10px;opacity:.6">College Complaints</div></div></div>
<div class="admin-badge">🛡️ Administrator</div>
<div class="menu-t">OVERVIEW</div>
<a class="m-item" href="admin_dashboard.php">◎ Dashboard</a>
<a class="m-item active" href="admin_complaints.php">📁 Manage Complaints</a>
<a class="m-item" href="admin_users.php">👥 Manage Users</a>
<div class="menu-t">ACCOUNT</div>
<a class="m-item" href="index.php">🌐 View Site</a>
<a class="m-item" href="logout.php">↪ Logout</a>
<div style="margin-top:auto;background:#171935;border-radius:12px;padding:11px"><div style="font-size:11px;font-weight:700;color:#fff">❓ Need help?</div><div style="font-size:10.5px;opacity:.5;margin-top:5px;line-height:1.35">Track status anytime. Admins reply within 2 working days.</div></div>
</div>
<div class="main">
<div class="topbar"><div><div style="font-size:10.5px;color:#6b7280">ResolveDesk / CMP-<?php echo str_pad($c['id'],6,'0',STR_PAD_LEFT); ?></div><div style="font-weight:800;font-size:16px;margin-top:2px">CMP-<?php echo str_pad($c['id'],6,'0',STR_PAD_LEFT); ?></div><div style="font-size:11px;color:#6b7280;margin-top:2px"><?php echo htmlspecialchars($c['subject']); ?></div></div><div style="display:flex;gap:8px;align-items:center"><div style="position:relative"><div style="width:30px;height:30px;background:#fff;border:1px solid #e6e8ef;border-radius:9px;display:flex;align-items:center;justify-content:center">🔔</div><span style="position:absolute;top:-4px;right:-4px;background:#ef4444;color:#fff;width:15px;height:15px;border-radius:50%;font-size:9px;display:flex;align-items:center;justify-content:center;font-weight:700">2</span></div><div style="display:flex;gap:8px;align-items:center;background:#fff;border:1px solid #e6e8ef;border-radius:11px;padding:3px 9px 3px 3px"><div style="width:26px;height:26px;background:#6366f1;color:#fff;border-radius:7px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:11px">A</div><div style="font-size:10.5px"><div style="font-weight:700;font-size:11px">Administrator</div><div style="font-size:9.5px;color:#6b7280">Admin</div></div></div></div></div>
<div class="content">
<div style="display:flex;gap:12px;font-size:11px;color:#6b7280;margin-bottom:12px;flex-wrap:wrap"><span>📅 <?php echo date('M j, Y - h:i A',strtotime($c['created_at'])); ?></span><span>🔄 Updated just now</span><span>📍 <?php echo htmlspecialchars($c['location']??'jkdfijsd/f/sdlf/'); ?></span></div>
<div class="grid">
<div>
<div class="card">
<div style="display:flex;gap:6px;margin-bottom:10px"><span class="badge b-pending"><?php echo htmlspecialchars($c['status']); ?></span><span class="badge b-host"><?php echo htmlspecialchars($c['category']); ?></span></div>
<div style="font-size:12px;font-weight:700;margin-bottom:8px">Description</div>
<div style="background:#f9fafb;border:1px solid #f1f3f7;border-radius:9px;padding:10px;font-size:12px;color:#111827"><?php echo nl2br(htmlspecialchars($c['description'])); ?></div>
</div>

<?php if($isAdmin){ ?>
<div class="card" style="margin-top:12px">
<div style="font-weight:700;font-size:13px;margin-bottom:10px;display:flex;gap:6px">🔧 Update status & response</div>
<form method="POST">
<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
<div><label style="font-size:11px;font-weight:600">New status</label>
<select name="new_status"><option <?php if($c['status']=='Pending') echo 'selected'; ?>>Pending</option><option <?php if($c['status']=='In Progress') echo 'selected'; ?>>In Progress</option><option <?php if($c['status']=='Resolved') echo 'selected'; ?>>Resolved</option><option <?php if($c['status']=='Closed') echo 'selected'; ?>>Closed</option></select>
</div>
<div><label style="font-size:11px;font-weight:600">History comment (optional, shown in timeline)</label><input type="text" name="history_comment" placeholder="e.g. Technician assigned" style="width:100%;padding:9px 10px;border:1px solid #e5e7eb;border-radius:9px;font-size:12px"></div>
</div>
<div style="margin-top:10px"><textarea name="admin_response_text" placeholder="Write admin response here..."><?php echo htmlspecialchars($c['admin_response']??''); ?></textarea></div>
<div style="display:flex;justify-content:space-between;align-items:center;margin-top:10px"><div style="font-size:10px;color:#9ca3af">📝 Saving writes to complaints + appends a row to complaint_history in one transaction.</div><button class="btn-save" type="submit">💾 Save changes</button></div>
</form>
</div>
<?php } else { ?>
<div class="card" style="margin-top:12px"><div style="font-size:12px;font-weight:700;margin-bottom:8px">📋 Administrative response</div><div style="background:#f9fafb;border:1px solid #f1f3f7;border-radius:9px;padding:10px;font-size:11.5px;color:#6b7280"><?php echo htmlspecialchars($c['admin_response']??'No response yet — the admin team will reply here once reviewed.'); ?></div></div>
<?php } ?>

</div>
<div>
<div class="card"><div style="font-size:11px;color:#6b7280">Student</div><div style="font-weight:700;font-size:13px;margin-top:2px"><?php echo htmlspecialchars($c['user_name']); ?></div><div style="font-size:11px;color:#6b7280;margin-top:6px">Member since Oct 2026</div></div>
<div class="card" style="margin-top:10px"><div style="font-weight:700;font-size:12.5px;display:flex;gap:6px">📜 History (<?php echo count($c['history']??[]); ?>)</div><div style="font-size:11px;color:#6b7280;margin-top:8px"><?php if(empty($c['history'])){ echo 'No status changes yet.'; } else { foreach($c['history'] as $h){ echo '<div style="padding:6px 0;border-bottom:1px solid #f3f4f6"><b>'.$h['status'].'</b> - '.$h['comment'].'<br><span style="font-size:10px;color:#9ca3af">'.$h['at'].'</span></div>'; } } ?></div></div>
</div>
</div>
</div>
</div>
<?php if(isset($_GET['saved'])){ ?><div class="toast">✅ Complaint <?php echo str_pad($c['id'],6,'0',STR_PAD_LEFT); ?> updated to <?php echo htmlspecialchars($c['status']); ?>!</div><?php } ?>
</body></html>
