<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$host = "localhost"; $db_name = "gym_db"; $username = "root"; $password = "";
$message = "";

// Handle Adding New Items (Admin Only Privilege)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_item'])) {
    $item_name = trim($_POST['item_name']);
    $quantity  = (int)$_POST['quantity'];
    $price     = (float)$_POST['price'];
    $target_file = "";

    if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] === UPLOAD_ERR_OK) {
        $file_name     = basename($_FILES["item_image"]["name"]);
        $file_ext      = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_exts  = array("jpg", "jpeg", "png", "webp");

        if (in_array($file_ext, $allowed_exts)) {
            $new_filename = uniqid('img_', true) . "." . $file_ext;
            $target_file  = "uploads/" . $new_filename;
            move_uploaded_file($_FILES["item_image"]["tmp_name"], $target_file);
        }
    }

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql = "INSERT INTO inventory (item_name, quantity, price, image_path) VALUES (:item_name, :quantity, :price, :image_path)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':item_name' => $item_name, ':quantity' => $quantity, ':price' => $price, ':image_path' => $target_file]);
        $message = "✅ New merchandise added successfully!";
    } catch (PDOException $e) {
        $message = "❌ Error adding item: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Privilege Panel</title>
    <style>
        body { background-color: #121212; color: #fff; font-family: 'Segoe UI', sans-serif; padding: 40px; }
        h1, h2 { color: #ff4500; text-transform: uppercase; }
        .grid { display: flex; gap: 40px; margin-top: 30px; flex-wrap: wrap; }
        .card { background-color: #1a1a1a; border: 1px solid #333; padding: 25px; border-radius: 8px; flex: 1; min-width: 300px; }
        input { width: 100%; padding: 12px; margin-bottom: 15px; background-color: #2b2b2b; border: 1px solid #444; color: #fff; border-radius: 5px; }
        .btn { width: 100%; background-color: #ff4500; color: white; border: none; padding: 12px; font-weight: bold; border-radius: 5px; cursor: pointer; }
        .btn:hover { background-color: #e03d00; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #333; }
        th { background-color: #ff4500; }
    </style>
</head>
<body>

    <h1>System Administrator Console</h1>
    <p>Welcome, master user: <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong> | <a href="logout.php" style="color:#ff4500;">Log Out</a></p>

    <?php if(!empty($message)) echo "<p style='margin-top:15px;'>$message</p>"; ?>

    <div class="grid">
        <!-- System Control 1: Add New Products -->
        <div class="card">
            <h2>Add New Store Merchandise</h2>
            <form action="admin_dashboard.php" method="POST" enctype="multipart/form-data" style="margin-top:20px;">
                <input type="hidden" name="add_item" value="1">
                <input type="text" name="item_name" placeholder="Product Title" required>
                <input type="number" name="quantity" placeholder="Starting Stock Volume" required>
                <input type="number" name="price" placeholder="Unit Cost Price (Ksh)" step="0.01" required>
                <input type="file" name="item_image" accept="image/*" required>
                <button type="submit" class="btn">Upload to Database</button>
            </form>
        </div>

        <!-- System Control 2: Master Inventory Overview Logs -->
        <div class="card">
            <h2>Active Catalog Matrix</h2>
            <table>
                <tr>
                    <th>Item</th>
                    <th>Stock Count</th>
                    <th>Valuation</th>
                </tr>
                <?php
                try {
                    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
                    $stmt = $conn->query("SELECT item_name, quantity, price FROM inventory");
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>".htmlspecialchars($row['item_name'])."</td>";
                        echo "<td>".$row['quantity']." pcs</td>";
                        echo "<td>Ksh ".number_format($row['price'])."</td>";
                        echo "</tr>";
                    }
                } catch(PDOException $e) {}
                ?>
            </table>
        </div>
    </div>

</body>
</html>
