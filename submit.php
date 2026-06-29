<?php
// Start browser session management at the very top of the file
session_start();

$host = "localhost"; $db_name = "gym_db"; $username = "root"; $password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    die("Database connection failed: " . $exception->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = trim($_POST['full_name']);
    $user_email = trim($_POST['email']);
    $user_password = $_POST['password'];
    $user_plan = $_POST['plan'];

    $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

    try {
        // Updated target to 'users' table and explicitly set the role column to 'user'
        $sql = "INSERT INTO users (full_name, email, password, role, plan) VALUES (:name, :email, :password, 'user', :plan)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':name', $user_name);
        $stmt->bindParam(':email', $user_email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':plan', $user_plan);

        $stmt->execute();

        // Fetch the newly inserted user ID to log them in automatically
        $last_id = $conn->lastInsertId();

        // Establish the user session data state instantly
        $_SESSION['user_id']   = $last_id;
        $_SESSION['user_name'] = $user_name;
        $_SESSION['user_role'] = 'user';
        $_SESSION['user_plan'] = $user_plan;

        echo "<div style='text-align: center; margin-top: 50px; font-family: sans-serif; background-color: #121212; color: #fff; padding: 40px; min-height: 100vh;'>";
        echo "<h2 style='color: #ff4500;'>Registration Successful!</h2>";
        echo "<p style='font-size: 18px; margin-top: 20px;'>Welcome to Iron Pulse Gym, <strong>" . htmlspecialchars($user_name) . "</strong>.</p>";
        echo "<div style='background-color: #1a1a1a; border: 1px solid #ff4500; display: inline-block; padding: 20px; border-radius: 10px; margin-top: 20px;'>";
        echo "<p>Your account is active. You can now access your portal panel.</p>";
        echo "</div>";
        echo "<br><br><br><a href='user_dashboard.php' style='color: #ff4500; text-decoration: none; border: 1px solid #ff4500; padding: 10px 20px; border-radius: 5px;'>Enter Dashboard Portal</a>";
        echo "</div>";

    } catch (PDOException $e) {
        // Handle duplicate email addresses gracefully
        if ($e->getCode() == 23000) {
            echo "<div style='text-align: center; margin-top: 50px; font-family: sans-serif; background-color: #121212; color: #fff; padding: 40px; min-height: 100vh;'>";
            echo "<h2 style='color: red;'>Registration Failed</h2>";
            echo "<p>This email address is already registered in our system.</p>";
            echo "<br><a href='index.php#join' style='color: #ff4500;'>Try Again</a>";
            echo "</div>";
        } else {
            echo "Error saving to database: " . $e->getMessage();
        }
    }
} else {
    header("Location: index.php");
    exit();
}
?>
