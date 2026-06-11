<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iron Pulse Gym</title>
    <style>
        /* --- General Styles --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #121212;
            color: #ffffff;
        }
        a {
            text-decoration: none;
            color: inherit;
        }

        /* --- Header & Navigation --- */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 10%;
            background-color: #1a1a1a;
            border-bottom: 2px solid #ff4500;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #ff4500;
            text-transform: uppercase;
        }
        nav ul {
            display: flex;
            list-style: none;
        }
        nav ul li {
            margin-left: 20px;
        }
        nav ul li a:hover {
            color: #ff4500;
        }

        /* --- Hero Section --- */
        .hero {
            height: 60vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://unsplash.com') no-repeat center center/cover;
            padding: 0 20px;
        }
        .hero h1 {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .hero p {
            font-size: 18px;
            margin-bottom: 20px;
            color: #ccc;
        }
        .cta-btn {
            background-color: #ff4500;
            color: white;
            padding: 12px 30px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
            transition: 0.3s;
            display: inline-block;
        }
        .cta-btn:hover {
            background-color: #e03d00;
        }

        /* --- Pricing Section --- */
        .pricing {
            padding: 60px 10%;
            text-align: center;
        }
        .pricing h2 {
            font-size: 36px;
            margin-bottom: 40px;
            color: #ff4500;
        }
        .plans {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }
        .card {
            background-color: #1a1a1a;
            border: 1px solid #333;
            border-radius: 10px;
            padding: 30px;
            width: 280px;
            transition: 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            border-color: #ff4500;
        }
        .card h3 {
            font-size: 22px;
            margin-bottom: 15px;
        }
        .price {
            font-size: 28px;
            font-weight: bold;
            color: #ff4500;
            margin-bottom: 20px;
        }
        .features {
            list-style: none;
            margin-bottom: 30px;
            text-align: left;
            line-height: 2;
            color: #ddd;
        }

        /* --- Form Section --- */
        .join-section {
            background-color: #1a1a1a;
            padding: 60px 10%;
            text-align: center;
        }
        .join-section h2 {
            font-size: 36px;
            margin-bottom: 20px;
            color: #ff4500;
        }
        form {
            max-width: 500px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input, select {
            width: 100%;
            padding: 12px;
            background-color: #2b2b2b;
            border: 1px solid #444;
            color: white;
            border-radius: 5px;
            font-size: 16px;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #ff4500;
        }
        .submit-btn {
            background-color: #ff4500;
            color: white;
            border: none;
            padding: 14px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        .submit-btn:hover {
            background-color: #e03d00;
        }
    </style>
</head>
<body>

    <!-- Header Navigation (Fixed Single Bar) -->
    <header>
        <div class="logo">Iron Pulse</div>
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#plans">Plans</a></li>
                <li><a href="inventory.php">Inventory</a></li>
                <li><a href="#join">Join Now</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Banner -->
    <section class="hero">
        <h1>NO PAIN, NO GAIN</h1>
        <p>No cookies. No tracking. Just pure fitness results.</p>
        <a href="#join" class="cta-btn">Start Your Journey</a>
    </section>

    <!-- Membership Plans Section -->
    <section class="pricing" id="plans">
        <h2>Membership Plans</h2>
        <div class="plans">
            
            <!-- Plan 1 -->
            <div class="card">
                <h3>Basic</h3>
                <div class="price">Ksh 2,500/mo</div>
                <ul class="features">
                    <li>Gym Floor Access</li>
                    <li>Locker Rooms</li>
                    <li>No Trainer</li>
                </ul>
                <a href="#join" class="cta-btn" onclick="selectPlan('Basic')">Choose Plan</a>
            </div>

            <!-- Plan 2 -->
            <div class="card" style="border-color: #ff4500;">
                <h3>Premium</h3>
                <div class="price">Ksh 5,000/mo</div>
                <ul class="features">
                    <li>Gym Floor Access</li>
                    <li>Locker Rooms</li>
                    <li>Group Classes</li>
                    <li>1 Personal Trainer Session</li>
                </ul>
                <a href="#join" class="cta-btn" onclick="selectPlan('Premium')">Choose Plan</a>
            </div>

        </div>
    </section>

    <!-- Signup Form Section -->
    <section class="join-section" id="join">
        <h2>Become a Member</h2>
        <form action="submit.php" method="POST">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Create Password" minlength="6" required>
            
            <select name="plan" id="plan-select" required>
                <option value="" disabled selected>Select Your Plan</option>
                <option value="Basic">Basic - Ksh 2,500/mo</option>
                <option value="Premium">Premium - Ksh 5,000/mo</option>
            </select>

            <button type="submit" class="submit-btn">Register and Pay</button>
        </form>
    </section>

    <!-- Login Section Portal -->
    <section class="join-section" id="login" style="border-top: 1px solid #333; margin-top: 40px; padding-top: 40px;">
        <h2>Member Portal</h2>
        <p style="color: #ccc; margin-bottom: 20px;">Enter your details to view your active gym membership status.</p>
        
        <form action="login.php" method="POST">
            <input type="email" name="login_email" placeholder="Email Address" required>
            <input type="password" name="login_password" placeholder="Password" required>
            <button type="submit" class="submit-btn">View My Plan</button>
        </form>
    </section>

    <script>
        function selectPlan(planName) {
            document.getElementById('plan-select').value = planName;
        }
    </script>

</body>
</html>
