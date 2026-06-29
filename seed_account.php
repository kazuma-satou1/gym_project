<?php
$host = "localhost"; $db_name = "gym_db"; $username = "root"; $password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Fixed Encryption: Shifted to PASSWORD_DEFAULT to align with login.php checks
    $admin_password   = password_hash('SuperAdmin2026', PASSWORD_DEFAULT);
    $manager_password = password_hash('GymManager2026', PASSWORD_DEFAULT);

    // 2. Clean Slate optimization to avoid duplicate execution keys
    $conn->exec("DELETE FROM users WHERE email IN ('admin@ironpulse.com', 'manager@ironpulse.com')");

    // 3. Fixed Parameters: Altered 'name' column query variables to target 'full_name'
    $sql1 = "INSERT INTO users (full_name, email, password, role) VALUES ('Super Admin', 'admin@ironpulse.com', :pass, 'admin')";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->execute([':pass' => $admin_password]);

    $sql2 = "INSERT INTO users (full_name, email, password, role) VALUES ('Gym Manager', 'manager@ironpulse.com', :pass, 'manager')";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->execute([':pass' => $manager_password]);

    echo "<div style='font-family:sans-serif; background:#121212; color:#fff; padding:40px; min-height:100vh; text-align:center;'>";
    echo "<h2 style='color:green;'>⚡ Master Permissions Initialized Successfully!</h2>";
    echo "<div style='background:#1a1a1a; display:inline-block; padding:20px; border:1px solid #333; text-align:left; border-radius:8px;'>";
    echo "<p>🔑 <strong>Admin Log:</strong> admin@ironpulse.com | Password: SuperAdmin2026</p>";
    echo "<p>💼 <strong>Manager Log:</strong> manager@ironpulse.com | Password: GymManager2026</p>";
    echo "</div>";
    echo "<p style='color:red; margin-top:20px;'>Close this tab, open an Incognito window, and log into your dashboard portal.</p>";
    echo "</div>";

} catch (PDOException $e) {
    echo "<h2 style='color:red;'>SQL Injection Execution Halted</h2>";
    echo "Reason: " . $e->getMessage();
}
?>

