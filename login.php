<?php
// 1. Database connection string setup
$host = "localhost";
$db_name = "gym_db";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    die("Database connection failed: " . $exception->getMessage());
}

// 2. Check if login data was sent
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['login_email'];
    $input_password = $_POST['login_password'];

    try {
        // 3. Look up the member by email
        // Note: Change 'name' below to 'full_name' if that is what you used in your database!
        $sql = "SELECT full_name, password, plan FROM members WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 4. Verify password securely
        if ($user && password_verify($input_password, $user['password'])) {
            // Success! Show profile data instantly without generating cookies.
            echo "<div style='text-align: center; margin-top: 50px; font-family: sans-serif; color: #fff; background-color: #121212; padding: 40px; min-height: 100vh;'>";
            echo "<h2 style='color: #ff4500;'>Member Dashboard</h2>";
            echo "<p style='font-size: 20px; margin-top: 20px;'>Welcome back, <strong>" . htmlspecialchars($user['full_name']) . "</strong>!</p>";
            echo "<div style='background-color: #1a1a1a; border: 1px solid #ff4500; display: inline-block; padding: 20px; border-radius: 10px; margin-top: 20px;'>";
            echo "<p style='font-size: 18px;'>Active Membership Plan:</p>";
            echo "<p style='font-size: 24px; color: #ff4500; font-weight: bold; margin-top: 10px;'>" . htmlspecialchars($user['plan']) . "</p>";
            echo "</div>";
            echo "<br><br><br><a href='index.php' style='color: #ff4500; text-decoration: none; border: 1px solid #ff4500; padding: 10px 20px; border-radius: 5px;'>Exit Portal</a>";
            echo "</div>";
        } else {
            // Login failed
            echo "<div style='text-align: center; margin-top: 50px; font-family: sans-serif;'>";
            echo "<h2 style='color: red;'>Login Failed</h2>";
            echo "<p>Invalid email or password. Please try again.</p>";
            echo "<br><a href='index.php#login' style='color: #ff4500;'>Go Back</a>";
            echo "</div>";
        }

    } catch (PDOException $e) {
        echo "System error: " . $e->getMessage();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
