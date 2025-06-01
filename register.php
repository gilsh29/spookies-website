<?php include 'header.php'; ?>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = "sql101.byethost7.com"; 
$dbname = "b7_39023074_Spookies_Users";
$username = "b7_39023074";
$password = "Hy.srT_ZUG\$Ygv6";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';
$message_color = 'green';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uname = $_POST['username'];
    $email = $_POST['email'];
    $pwd = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $bdate = $_POST['birthdate'];

    $sql = "INSERT INTO users (username, email, password, birthdate) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssss", $uname, $email, $pwd, $bdate);
        if ($stmt->execute()) {
            $message = "Registration successful!";
            $message_color = 'lightgreen';
        } else {
            $message = "Error: " . $stmt->error;
            $message_color = 'lightcoral';
        }
        $stmt->close();
    } else {
        $message = "Error preparing statement: " . $conn->error;
        $message_color = 'lightcoral';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Spookies - Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Creepster&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="MainPage.css" />
    
</head>
<body>



<main>
    <h2>Register</h2>

    <?php if ($message): ?>
        <p class="message" style="color: <?= htmlspecialchars($message_color) ?>;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="username">User Name:</label>
        <input type="text" id="username" name="username" required />

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required />

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required />

        <label for="birthdate">Birthdate:</label>
        <input type="date" id="birthdate" name="birthdate" required />

        <input type="submit" value="Register" />
    </form>
    
</main>

<?php include 'footer.php'; ?>

<script src="logo.js"></script>


</body>
</html>

<?php $conn->close(); ?>
