<?php include 'header.php'; ?>

<?php
session_start();
include 'db.php'; // your DB connection file

$loginError = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize input (basic)
    $email = mysqli_real_escape_string($conn, $email);

    // Query to check credentials by email
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // Check password (use password_verify if hashed)
        if (password_verify($password, $row['password'])) {
            // ✅ Correct credentials — now start session
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_name'] = $row['username']; // or $row['email'] if username isn't stored

            // Redirect to home page
            header("Location: index.php");
            exit();
        } else {
            $loginError = "❌ Invalid password.";
        }
    } else {
        $loginError = "❌ User not found.";
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Spookies - Sign In</title>
    <link href="https://fonts.googleapis.com/css2?family=Creepster&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="MainPage.css" />
</head>
<body>
 
    <main style="max-width: 450px; margin: 60px auto; background-color: rgba(0,0,0,0.7); padding: 30px; border-radius: 8px; box-shadow: 0 0 10px #e60000; color: #f5f5f5; font-family: Arial, sans-serif;">
        <h2 style="font-family: 'Creepster', cursive; color: #e60000; text-align: center; margin-bottom: 20px;">Sign In</h2>

        <form method="POST" action="signin.php" style="display: block;">
            <label for="email">Email:</label>
            <input
                type="email"
                id="email"
                name="email"
                required
                style="width: 100%; padding: 14px 18px; font-size: 1.1em; border-radius: 5px; border: none; margin-bottom: 18px; box-sizing: border-box;"
            />

            <label for="password">Password:</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                style="width: 100%; padding: 14px 18px; font-size: 1.1em; border-radius: 5px; border: none; margin-bottom: 18px; box-sizing: border-box;"
            />

            <input
                type="submit"
                value="Sign In"
                style="width: 100%; background-color: #e60000; color: white; border: none; padding: 14px; font-size: 1.1em; border-radius: 5px; cursor: pointer; transition: background-color 0.3s; margin-top: 10px;"
                onmouseover="this.style.backgroundColor='#cc0000'"
                onmouseout="this.style.backgroundColor='#e60000'"
            />
        </form>


        <?php if (!empty($loginError)): ?>
    <div style="color: #ff4d4d; text-align: center; margin-bottom: 15px;">
        <?= htmlspecialchars($loginError) ?>
    </div>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 15px; font-size: 0.95em;">
            Don't have an account? <a href="register.php" style="color:#e60000;">Register here</a>
        </div>
    </main>

<?php include 'footer.php'; ?>

    <script>
        // Random logo logic (same as your main page)
        const logos = [
            "images/logos/logo5.png",
            "images/logos/logo6.png",
            "images/logos/logo7.png"
        ];

        const randomLogo = logos[Math.floor(Math.random() * logos.length)];
        document.querySelector(".logo").src = randomLogo;
    </script>
</body>
</html>
