<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'manager') {
    header("Location: index.php");
    exit();
}

$host = "localhost"; $db_name = "gym_db"; $username = "root"; $password = "";
$message = "";

// Handle Quick Updates (Manager Privilege Field Routing)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_stock'])) {
    $item_id  = (int)$_POST['item_id'];
    $new_qty  = (int)$_POST['quantity'];
    $new_prc  = (float)$_POST['price'];

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "UPDATE inventory SET quantity = :qty, price = :price WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':qty' => $new_qty, ':price' => $new_prc, ':id' => $item_id]);
        $message = "⚡ Inventory configurations modified locally!";
    } catch (PDOException $e) {
        $message = "❌ Manager update rejected: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manager Operations Panel</title>
    <style>
        body { background-color: #121212; color: #fff; font-family: 'Segoe UI', sans-serif; padding: 40px; }
        h1, h2 { color: #ff4500; text-transform: uppercase; }
        .table-container { background-color: #1a1a1a; border: 1px solid #333; padding: 25px; border-radius: 8px; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #333; }
        th { background-color: #ff4500; }
        input[type="number"] { width: 80px; padding: 6px; background-color: #2b2b2b; border: 1px solid #444; color: white; border-radius: 4px; }
        .update-btn { background-color: #2e7d32; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .update-btn:hover { background-color: #1b5e20; }
    </style>
</head>
<body>

    <h1>Managerial Stock Control</h1>
    <p>Logged in operator: <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong> | <a href="logout.php" style="color:#ff4500;">Log Out</a></p>

    <?php if(!empty($message)) echo "<p style='margin-top:15px; color:#2e7d32; font-weight:bold;'>$message</p>"; ?>

    <div class="table-container">
        <h2>Live Floor Stock Pricing Control Matrix</h2>
        <p style="color:#aaa; font-size:14px; margin-bottom:15px;">Modify quantities or edit pricing tags below and click update to refresh the live storefront.</p>
        
        <table>
            <tr>
                <th>Item Name</th>
                <th>Current Stock</th>
                <th>Store Price</th>
                <th>Action</th>
            </tr>
            <?php
            try {
                $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
                $stmt = $conn->query("SELECT id, item_name, quantity, price FROM inventory");
                
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['item_name']) . "</td>";
                    echo "<form action='manager_dashboard.php' method='POST'>";
                    echo "<input type='hidden' name='update_stock' value='1'>";
                    echo "<input type='hidden' name='item_id' value='".$row['id']."'>";
                    // Editable parameters inside fields
                    echo "<td><input type='number' name='quantity' value='".$row['quantity']."' min='0'> pcs</td>";
                    echo "<td>Ksh <input type='number' name='price' value='".$row['price']."' step='0.01' min='0'></td>";
                    echo "<td><button type='submit' class='update-btn'>Update Item</button></td>";
                    echo "</form>";
                    echo "</tr>";
                }
            } catch(PDOException $e) {
                echo "<tr><td colspan='4'>Failed to fetch records: " . $e->getMessage() . "</td></tr>";
            }
            ?>
        </table>
    </div>

</body>
</html>
