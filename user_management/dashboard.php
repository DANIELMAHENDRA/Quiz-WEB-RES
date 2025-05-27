<?php
session_start();
include 'db.php';
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}
$user = $_SESSION["user"];

// Proses update profil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $user['id'];
    $name = $_POST["name"];
    $email = $_POST["email"];

    // Cek apakah user upload foto baru
    if ($_FILES["photo"]["error"] === 0) {
        $photo = time() . "_" . $_FILES["photo"]["name"];
        $tmp = $_FILES["photo"]["tmp_name"];
        move_uploaded_file($tmp, "uploads/" . $photo);
        $conn->query("UPDATE users SET name='$name', email='$email', photo='$photo' WHERE id=$id");
        $user['photo'] = $photo;
    } else {
        $conn->query("UPDATE users SET name='$name', email='$email' WHERE id=$id");
    }

    $user['name'] = $name;
    $user['email'] = $email;

    // Update session dengan data baru
    $_SESSION["user"] = $user;

    echo "<p style='color:green;'>Profil berhasil diperbarui.</p>";
}
?>

<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
    <img src="uploads/<?= htmlspecialchars($user['photo']) ?>" width="50" height="50" style="border-radius: 50%; object-fit: cover;">
    <h2 style="margin: 0;">Selamat Datang, <?= htmlspecialchars($user['name']) ?></h2>
</div>

<a href="logout.php">Logout</a>

<h3>Edit Profil</h3>
<form method="post" enctype="multipart/form-data">
  <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required><br>
  <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>
  <label>Ganti Foto:</label><br>
  <input type="file" name="photo"><br>
  <button type="submit">Update Profil</button>
</form>
