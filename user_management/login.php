<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $pass  = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if ($row && password_verify($pass, $row["password"])) {
        $_SESSION["user"] = $row;
        header("Location: dashboard.php");
    } else {
        echo "Login gagal!";
    }
}
?>

<form method="post">
  <input type="email" name="email" placeholder="Email" required><br>
  <input type="password" name="password" placeholder="Password" required><br>
  <button type="submit">Login</button>
</form>
