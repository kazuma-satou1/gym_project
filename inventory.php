<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Inventory - Iron Pulse</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #121212; color: #ffffff; padding: 40px; text-align: center; }
        h1 { color: #ff4500; margin-bottom: 30px; text-transform: uppercase; }
        
        .container { display: flex; flex-direction: column; align-items: center; gap: 40px; }
        
        table { width: 80%; border-collapse: collapse; background-color: #1a1a1a; border: 1px solid #333; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #333; }
        th { background-color: #ff4500; color: white; text-transform: uppercase; }
        tr:hover { background-color: #252525; }
        
        .select-btn { background-color: #ff4500; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-weight: bold; transition: 0.3s; }
        .select-btn:hover { background-color: #e03d00; }
        
        /* --- Cart Dashboard Styles --- */
        .cart-dashboard { width: 80%; background-color: #1a1a1a; border: 2px dashed #ff4500; border-radius: 10px; padding: 20px; text-align: left; }
        .cart-dashboard h2 { color: #ff4500; margin-bottom: 15px; border-bottom: 1px solid #333; padding-bottom: 10px; }
        .cart-item { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #222; }
        .cart-total { margin-top: 15px; font-size: 20px; font-weight: bold; text-align: right; color: #ff4500; }
        .clear-btn { background-color: #333; color: #ccc; border: 1px solid #444; padding: 5px 10px; border-radius: 4px; cursor: pointer; margin-top: 10px; }
        .clear-btn:hover { background-color: #444; color: white; }
        
        .back-btn { display: inline-block; margin-top: 30px; color: #ff4500; text-decoration: none; border: 1px solid #ff4500; padding: 10px 20px; border-radius: 5px; transition: 0.3s; }
        .back-btn:hover { background-color: #ff4500; color: white; }
    </style>
</head>
<body>

    <h1>Iron Pulse Shop Inventory</h1>

    <div class="container">
        <!-- 1. The Main Inventory Display Table -->
        <table>
            <tr>
                <th>Item Name</th>
                <th>Stock Status</th>
                <th>Price</th>
                <th>Action</th>
            </tr>

            <?php
            $host = "localhost"; $db_name = "gym_db"; $username = "root"; $password = "";

            try {
                $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "SELECT item_name, quantity, price FROM inventory";
                $stmt = $conn->query($sql);

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $name = htmlspecialchars($row['item_name']);
                    $price = htmlspecialchars($row['price']);
                    
                    echo "<tr>";
                    echo "<td>" . $name . "</td>";
                    echo "<td>" . htmlspecialchars($row['quantity']) . " pcs left</td>";
                    echo "<td>Ksh " . $price . "</td>";
                    // The button calls a JavaScript function passing the name and price dynamically
                    echo "<td><button class='select-btn' onclick=\"addToCart('$name', $price)\">Select Item</button></td>";
                    echo "</tr>";
                }
            } catch (PDOException $e) {
                echo "<tr><td colspan='4'>Error loading items: " . $e->getMessage() . "</td></tr>";
            }
            ?>
        </table>

        <!-- 2. The Dynamic Cart Dashboard Area -->
        <div class="cart-dashboard">
            <h2>🛒 Added to Cart Dashboard</h2>
            <div id="cart-list">
                <p style="color: #888;">Your cart is currently empty. Click 'Select Item' above to fill it.</p>
            </div>
            <div class="cart-total" id="cart-total">Total: Ksh 0</div>
            <button class="clear-btn" onclick="clearCart()">Empty Cart</button>
        </div>
    </div>

    <br>
    <a href="index.php" class="back-btn">Go Back Home</a>

    <!-- 3. Stateless Frontend JavaScript Logic -->
    <script>
        // Load whatever items are currently memorized in browser tab memory
        let cart = JSON.parse(localStorage.getItem('gym_cart')) || [];

        // Run the rendering tool immediately when page finishes loading
        updateCartDashboard();

        function addToCart(itemName, itemPrice) {
            // Check if item is already added to cart to add to its quantity count
            let existingItem = cart.find(item => item.name === itemName);
            
            if (existingItem) {
                existingItem.qty += 1;
            } else {
                cart.push({ name: itemName, price: itemPrice, qty: 1 });
            }
            
            // Save state plainly to browser storage without tracking cookies
            localStorage.setItem('gym_cart', JSON.stringify(cart));
            updateCartDashboard();
        }

        function updateCartDashboard() {
            const listDiv = document.getElementById('cart-list');
            const totalDiv = document.getElementById('cart-total');
            
            if (cart.length === 0) {
                listDiv.innerHTML = '<p style="color: #888;">Your cart is currently empty. Click \'Select Item\' above to fill it.</p>';
                totalDiv.innerHTML = 'Total: Ksh 0';
                return;
            }
            
            let html = '';
            let grandTotal = 0;
            
            cart.forEach(item => {
                let itemTotal = item.price * item.qty;
                grandTotal += itemTotal;
                html += `
                    <div class="cart-item">
                        <span><strong>${item.name}</strong> (x${item.qty})</span>
                        <span>Ksh ${itemTotal}</span>
                    </div>
                `;
            });
            
            listDiv.innerHTML = html;
            totalDiv.innerHTML = `Total: Ksh ${grandTotal}`;
        }

        function clearCart() {
            cart = [];
            localStorage.removeItem('gym_cart');
            updateCartDashboard();
        }
    </script>

</body>
</html>
