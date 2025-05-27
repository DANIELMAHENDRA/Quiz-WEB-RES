<?php
include 'db.php';
$users = $conn->query("SELECT * FROM users");
?>

<a href="add_user.php">Tambah User</a>
<table border="1">
  <tr><th>Nama</th><th>Email</th><th>Foto</th><th>Aksi</th></tr>
  <?php while($u = $users->fetch_assoc()): ?>
    <tr>
      <td><?= $u['name'] ?></td>
      <td><?= $u['email'] ?></td>
      <td><img src="uploads/<?= $u['photo'] ?>" width="50"></td>
      <td>
        <a href="edit_user.php?id=<?= $u['id'] ?>">Edit</a> | 
        <a href="delete_user.php?id=<?= $u['id'] ?>" onclick="return confirm('Yakin?')">Hapus</a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>
