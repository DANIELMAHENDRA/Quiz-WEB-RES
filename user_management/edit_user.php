<?php
include 'db.php';

$id = $_GET['id'];
$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = $_POST["name"];
    $email = $_POST["email"];
    
    
    if ($_FILES["photo"]["name"]) {
        $photo = $_FILES["photo"]["name"];
        $tmp   = $_FILES["photo"]["tmp_name"];
        move_uploaded_file($tmp, "uploads/" . $photo);
        $conn->query("UPDATE users SET name='$name', email='$email', photo='$photo' WHERE id=$id");
    } else {
        
        $conn->query("UPDATE users SET name='$name', email='$email' WHERE id=$id");
    }

    header("Location: users.php");
}
?>

<form method="post" enctype="multipart/form-data">
  <input type="text" name="name" value="<?= $user['name'] ?>" required><br>
  <input type="email" name="email" value="<?= $user['email'] ?>" required><br>
  <img src="uploads/<?= $user['photo'] ?>" width="100"><br>
  <input type="file" name="photo"><br>
  <button type="submit">Update</button>
</form>
