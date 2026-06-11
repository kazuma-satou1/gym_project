# Iron Pulse Gym System

This is my web project for a Gym Membership and Shop Inventory system. I built it using a completely cookie-free, stateless setup. Instead of tracking users with browser cookies or sessions, the site opens a direct database connection string whenever a user registers, logs in, or views data.

## What the Website Does
*   **No Cookie Tracking:** Everything runs without sessions or cookies, keeping the project strictly stateless.
*   **Member Registration:** Users can sign up for a gym plan. Passwords are safe because the code encrypts them before saving them to the database.
*   **Member Portal (Dashboard):** Members can type in their email and password to pull up a real-time view of their gym status straight from the database.
*   **Shop Inventory:** Displays available gym gear and supplements with prices listed in Kenyan Shillings (Ksh).
*   **Stateless Shopping Cart:** Users can click "Select Item" to add gear to their cart. It uses browser tab memory (Local Storage) to calculate totals automatically without tracking files.

## Project Files
*   `index.php` - The main homepage with the pricing cards, registration form, and login portal.
*   `submit.php` - Connects to the database to process and save new members securely.
*   `login.php` - Checks member logins on-demand and shows their dashboard view.
*   `inventory.php` - The shop page that reads stock items from the database and runs the shopping cart.

## Tools Used
*   **Frontend:** HTML5, CSS (Dark Theme Layout), and JavaScript.
*   **Backend & Server:** PHP (using PDO connection strings) running on XAMPP.
*   **Database:** MySQL (managed through phpMyAdmin).

## Database Configuration (`gym_db`)
I set up two tables inside a local database named `gym_db`:
1.  **`members`** - Columns used: `id`, `name`, `email`, `password`, and `plan`.
2.  **`inventory`** - Columns used: `id`, `item_name`, `quantity`, and `price`.

---
*Created by: Abraham Nathan*
