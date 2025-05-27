<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = $_POST["name"];
    $email = $_POST["email"];
    $pass  = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $photo = $_FILES["photo"]["name"];
    $tmp   = $_FILES["photo"]["tmp_name"];
    move_uploaded_file($tmp, "uploads/" . $photo);

    $sql = "INSERT INTO users (name, email, password, photo) VALUES ('$name', '$email', '$pass', '$photo')";
    $conn->query($sql);
    header("Location: users.php");
}
?>

<form method="post" enctype="multipart/form-data">
  <input type="text" name="name" placeholder="Nama" required><br>
  <input type="email" name="email" placeholder="Email" required><br>
  <input type="password" name="password" placeholder="Password" required><br>
  <input type="file" name="photo" required><br>
  <button type="submit">Simpan</button>
</form>
