<?php
$host = "localhost"; $db_name = "gym_db"; $username = "root"; $password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    die("Database connection failed: " . $exception->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['full_name'];
    $user_email = $_POST['email'];
    $user_password = $_POST['password'];
    $user_plan = $_POST['plan'];

    $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

    try {
        // SQL command optimized for single-table structure
        $sql = "INSERT INTO members (name, email, password, plan) VALUES (:name, :email, :password, :plan)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':name', $user_name);
        $stmt->bindParam(':email', $user_email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':plan', $user_plan);

        $stmt->execute();

        echo "<div style='text-align: center; margin-top: 50px; font-family: sans-serif;'>";
        echo "<h2 style='color: #ff4500;'>Registration Successful!</h2>";
        echo "<p>Welcome to Iron Pulse Gym, " . htmlspecialchars($user_name) . ".</p>";
        echo "<br><a href='index.php' style='color: #ff4500; text-decoration: none;'>Go Back Home</a>";
        echo "</div>";

    } catch (PDOException $e) {
        echo "Error saving to database: " . $e->getMessage();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
