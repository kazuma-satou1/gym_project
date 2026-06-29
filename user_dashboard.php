<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'user') { header("Location: index.php"); exit(); }
?>
<!DOCTYPE html>
<html>
<head><title>Member Dashboard</title></head>
<body style="background:#121212; color:#fff; font-family:sans-serif; padding:50px;">
    <h1 style="color:#ff4500;">Member Dashboard</h1>
    <p>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
    <div style="background:#1a1a1a; padding:20px; border:1px solid #333; border-radius:5px; display:inline-block;">
        <h3>Your Active Membership:</h3>
        <p style="color:#ff4500; font-size:24px; font-weight:bold;"><?php echo htmlspecialchars($_SESSION['user_plan'] ?? 'No Active Plan'); ?></p>
    </div>
    <br><br><a href="inventory.php" style="color:#ff4500;">Visit Gym Shop Store</a> | <a href="logout.php" style="color:#aaa;">Log Out</a>
</body>
</html>
