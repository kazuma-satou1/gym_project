<?php
// 1. Database Connection Configuration (Your Connection String Setup)
$host = "localhost";
$db_name = "gym_db";
$username = "root"; // Default XAMPP username
$password = "";     // Default XAMPP password is empty

try {
    // Create a direct connection to the database
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    // Tell PHP to show clear errors if something goes wrong
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    die("Database connection failed: " . $exception->getMessage());
}

// 2. Check if the form was actually submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Grab the data sent from the frontend form
    $user_name = $_POST['full_name'];
    $user_email = $_POST['email'];
    $user_password = $_POST['password'];
    $user_plan = $_POST['plan'];

    // 3. Security Check: Hash the password so it isn't stored in plain text
    // This turns "myPassword123" into a long, unreadable string of random characters
    $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

    try {
        // 4. Prepare the SQL command matching your database columns exactly
        $sql = "INSERT INTO members (full_name, email, password, plan) VALUES (:name, :email, :password, :plan)";
        $stmt = $conn->prepare($sql);

        // Link our form variables to the SQL command
        $stmt->bindParam(':name', $user_name);
        $stmt->bindParam(':email', $user_email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':plan', $user_plan);

        // Execute the command to save the member into the database
        $stmt->execute();

        // 5. Success Message (No cookies or sessions created!)
        echo "<div style='text-align: center; margin-top: 50px; font-family: sans-serif;'>";
        echo "<h2 style='color: #ff4500;'>Registration Successful!</h2>";
        echo "<p>Welcome to Iron Pulse Gym, " . htmlspecialchars($user_name) . ".</p>";
        echo "<p>Your plan: <strong>" . htmlspecialchars($user_plan) . "</strong></p>";
        echo "<br><a href='index.php' style='color: #ff4500; text-decoration: none;'>Go Back Home</a>";
        echo "</div>";

    } catch (PDOException $e) {
        // Handle issues like duplicate emails or system errors gracefully
        echo "Error saving to database: " . $e->getMessage();
    }
} else {
    // If someone tries to access submit.php directly without submitting the form
    header("Location: index.php");
    exit();
}
?>
