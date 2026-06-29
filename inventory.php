<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Store - Iron Pulse</title>
    <style>
        /* --- Core Theme Styles --- */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #121212; color: #ffffff; padding: 40px; }
        h1 { color: #ff4500; text-align: center; margin-bottom: 40px; text-transform: uppercase; letter-spacing: 1px; }
        
        .layout-container { display: flex; flex-direction: column; align-items: center; gap: 50px; max-width: 1200px; margin: 0 auto; }
        
        /* --- Commercial Product Card Grid --- */
        .product-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); 
            gap: 30px; 
            width: 100%; 
        }
        .product-card { 
            background-color: #1a1a1a; 
            border: 1px solid #333; 
            border-radius: 8px; 
            overflow: hidden; 
            transition: transform 0.3s, border-color 0.3s; 
            display: flex; 
            flex-direction: column; 
        }
        .product-card:hover { transform: translateY(-5px); border-color: #ff4500; }
        
        .product-image { 
            width: 100%; 
            height: 220px; 
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat; 
        }
        .product-info { padding: 20px; display: flex; flex-direction: column; gap: 12px; flex-grow: 1; text-align: left; }
        .product-title { font-size: 18px; font-weight: bold; color: #fff; line-height: 1.4; }
        .product-price { font-size: 22px; font-weight: bold; color: #ff4500; }
        
        /* --- Stock Badges --- */
        .badge { display: inline-block; padding: 4px 8px; font-size: 11px; font-weight: bold; border-radius: 4px; width: fit-content; text-transform: uppercase; }
        .badge-instock { background-color: #2e7d32; color: #fff; }
        .badge-lowstock { background-color: #ef6c00; color: #fff; animation: pulse 2s infinite; }
        .badge-outofstock { background-color: #c62828; color: #fff; }
        
        .select-btn { background-color: #ff4500; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 15px; transition: 0.3s; width: 100%; margin-top: auto; }
        .select-btn:hover { background-color: #e03d00; }
        
        /* --- Cart Dashboard Styles --- */
        .cart-dashboard { width: 100%; background-color: #1a1a1a; border: 2px dashed #ff4500; border-radius: 10px; padding: 25px; text-align: left; }
        .cart-dashboard h2 { color: #ff4500; margin-bottom: 20px; border-bottom: 1px solid #333; padding-bottom: 10px; text-transform: uppercase; font-size: 20px; }
        .cart-item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #222; font-size: 16px; }
        .cart-total { margin-top: 20px; font-size: 22px; font-weight: bold; text-align: right; color: #ff4500; }
        
        .cart-actions { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; }
        .clear-btn { background-color: #333; color: #ccc; border: 1px solid #444; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-weight: bold; transition: 0.3s; }
        .clear-btn:hover { background-color: #444; color: white; }
        .checkout-btn { background-color: #2e7d32; color: white; border: none; padding: 10px 25px; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 16px; transition: 0.3s; }
        .checkout-btn:hover { background-color: #256428; }

        .back-btn { display: inline-block; margin-top: 40px; color: #ff4500; text-decoration: none; border: 1px solid #ff4500; padding: 12px 24px; border-radius: 5px; transition: 0.3s; text-align: center; }
        .back-btn:hover { background-color: #ff4500; color: white; }
        
        @keyframes pulse { 0% { opacity: 0.7; } 50% { opacity: 1; } 100% { opacity: 0.7; } }
    </style>
</head>
<body>

    <h1>Iron Pulse Shop Front</h1>

    <div class="layout-container">
        
        <!-- Commercial Product Card Grid View -->
        <div class="product-grid">
            <?php
            $host = "localhost"; $db_name = "gym_db"; $username = "root"; $password = "";

            try {
                $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Fetch data including your brand new image path column
                $sql = "SELECT item_name, quantity, price, image_path FROM inventory";
                $stmt = $conn->query($sql);

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $name = htmlspecialchars($row['item_name']);
                    $price = htmlspecialchars($row['price']);
                    $qty = (int)$row['quantity'];
                    
                    // Fall back cleanly if an item doesn't have an image path uploaded yet
                    $image_src = !empty($row['image_path']) ? htmlspecialchars($row['image_path']) : 'https://unsplash.com';

                    // Formulate professional commercial stock alerts
                    if ($qty > 5) {
                        $badge = "<span class='badge badge-instock'>In Stock</span>";
                        $action_button = "<button class='select-btn' onclick=\"addToCart('$name', $price)\">Add To Cart</button>";
                    } elseif ($qty > 0) {
                        $badge = "<span class='badge badge-lowstock'>Only $qty Items Left</span>";
                        $action_button = "<button class='select-btn' onclick=\"addToCart('$name', $price)\">Add To Cart</button>";
                    } else {
                        $badge = "<span class='badge badge-outofstock'>Out of Stock</span>";
                        $action_button = "<button class='select-btn' style='background-color:#444; cursor:not-allowed;' disabled>Sold Out</button>";
                    }

                    echo "
                    <div class='product-card'>
                        <div class='product-image' style='background-image: url(\"$image_src\");'></div>
                        <div class='product-info'>
                            $badge
                            <div class='product-title'>$name</div>
                            <div class='product-price'>Ksh " . number_format($price, 2) . "</div>
                            $action_button
                        </div>
                    </div>";
                }
            } catch (PDOException $e) {
                echo "<p style='color: #ff4500; grid-column: 1/-1;'>Storefront connection error: " . $e->getMessage() . "</p>";
            }
            ?>
        </div>

        <!-- Dynamic Cart Display Area -->
        <div class="cart-dashboard">
            <h2>Items in Your Cart</h2>
            <div id="cart-list">
                <p style="color: #888;">Your shopping cart is empty. Click Add To Cart on items above.</p>
            </div>
            <div class="cart-total" id="cart-total">Total: Ksh 0.00</div>
            
            <div class="cart-actions">
                <button class="clear-btn" onclick="clearCart()">Empty Cart</button>
                <button class="checkout-btn" onclick="triggerCheckout()">Proceed to Checkout</button>
            </div>
        </div>
    </div>

    <div style="text-align: center;">
        <a href="index.php" class="back-btn">Return to Home Screen</a>
    </div>
    <!-- Frontend Shopping Logic -->
    <script>
        // Initialize cart array from local memory or set to empty
        let cart = JSON.parse(localStorage.getItem('gym_cart')) || [];

        // Run the layout renderer immediately upon landing
        updateCartDashboard();

        function addToCart(itemName, itemPrice) {
            // Find out if product is already in basket array
            let existingItem = cart.find(item => item.name === itemName);
            
            if (existingItem) {
                existingItem.qty += 1;
            } else {
                cart.push({ name: itemName, price: parseFloat(itemPrice), qty: 1 });
            }
            
            // Save state to local browser memory
            localStorage.setItem('gym_cart', JSON.stringify(cart));
            updateCartDashboard();
        }

        function updateCartDashboard() {
            const listDiv = document.getElementById('cart-list');
            const totalDiv = document.getElementById('cart-total');
            
            if (!listDiv || !totalDiv) return;

            if (cart.length === 0) {
                listDiv.innerHTML = '<p style="color: #888;">Your shopping cart is empty. Click Add To Cart on items above.</p>';
                totalDiv.innerHTML = 'Total: Ksh 0.00';
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
                        <span>Ksh ${itemTotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                    </div>
                `;
            });
            
            listDiv.innerHTML = html;
            totalDiv.innerHTML = `Total: Ksh ${grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        }

        function clearCart() {
            cart = [];
            localStorage.removeItem('gym_cart');
            updateCartDashboard();
        }

        // Triggered directly by your Proceed to Checkout button
        function triggerCheckout() {
            if (cart.length === 0) {
                alert('Your shopping cart is currently empty.');
                return;
            }

            // Prompt user for their phone number cleanly via browser alert prompt input
            let phone = prompt("Please enter your M-Pesa phone number:\nFormat must be: 2547XXXXXXXX or 2541XXXXXXXX", "254");
            
            if (phone === null) return; // User canceled the dialog box prompt row
            phone = phone.trim();

            // Validate Kenyan phone standards string criteria
            if (!phone.startsWith('254') || phone.length !== 12 || isNaN(phone)) {
                alert('Error: Please enter a valid phone number starting strictly with 254 (e.g. 254712345678).');
                return;
            }

            // Compute precise total variable to forward to Daraja backend mapping
            let grandTotal = 0;
            cart.forEach(item => { grandTotal += (item.price * item.qty); });

            // Post properties down to process_payment endpoint via async stream
            fetch('process_payment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `phone=${phone}&amount=${grandTotal}`
            })
            .then(response => response.json())
            .then(data => {
                if(data.ResponseCode === "0" || data.ResponseCode === 0) {
                    alert('STK Push initiated! Type your secret M-Pesa PIN on your smartphone when prompted.');
                    clearCart();
                } else {
                    alert('Safaricom Checkout Error: ' + (data.ResponseDescription || 'Transaction initialization rejected.'));
                }
            })
            .catch(error => {
                console.error('Debug processing stack:', error);
                alert('An infrastructure communications timeout occurred with your backend file.');
            });
        }
    </script>
</body>
</html>
