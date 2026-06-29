<?php
// Start browser session management at the very top of the file
session_start();

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
    $email = trim($_POST['login_email']);
    $input_password = $_POST['login_password'];

    try {
        // 3. Look up the member by email inside the unified users table
        $sql = "SELECT id, full_name, password, role, plan FROM users WHERE email = :email LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 4. Verify password securely
        if ($user && password_verify($input_password, $user['password'])) {
            
            // 5. Store account permissions safely inside browser session memory
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_plan'] = $user['plan'];

            // 6. Multi-role redirection traffic controller
            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php");
                exit();
            } elseif ($user['role'] === 'manager') {
                header("Location: manager_dashboard.php");
                exit();
            } else {
                header("Location: user_dashboard.php");
                exit();
            }
            
        } else {
            // Login failed
            echo "<div style='text-align: center; margin-top: 50px; font-family: sans-serif; background-color: #121212; color: #fff; padding: 40px; min-height: 100vh;'>";
            echo "<h2 style='color: red;'>Login Failed</h2>";
            echo "<p>Invalid email or password. Please try again.</p>";
            echo "<br><a href='index.php#login' style='color: #ff4500; text-decoration: none; border: 1px solid #ff4500; padding: 10px 20px; border-radius: 5px;'>Go Back</a>";
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
