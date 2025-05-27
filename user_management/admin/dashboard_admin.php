<?php
session_start();
include '../db.php';

// Proteksi role admin
if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$result = $conn->query("SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 20px;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 900px; /* Lebar yang lebih besar untuk tabel */
            margin-bottom: 20px;
        }
        h2 {
            color: #007bff;
            text-align: center;
            margin-bottom: 25px;
        }
        .action-buttons {
            display: flex;
            justify-content: flex-end; /* Pindahkan tombol ke kanan */
            margin-bottom: 20px;
        }
        .action-buttons a {
            background-color: #28a745; /* Hijau untuk tambah user */
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .action-buttons a:hover {
            background-color: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
            color: #555;
            font-weight: bold;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #e9e9e9;
        }
        .table-actions a {
            margin-right: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .table-actions a:hover {
            text-decoration: underline;
        }
        .table-actions a.delete {
            color: #dc3545; /* Merah untuk hapus */
        }
        .logout-link {
            display: block;
            width: fit-content;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #dc3545; /* Warna merah untuk logout */
            color: white;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .logout-link:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Data Users</h2>
        <div class="action-buttons">
            <a href="add_user.php">+ Tambah User</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row["id"]) ?></td>
                    <td><?= htmlspecialchars($row["name"]) ?></td>
                    <td><?= htmlspecialchars($row["email"]) ?></td>
                    <td><?= htmlspecialchars($row["role"]) ?></td>
                    <td class="table-actions">
                        <a href="edit_user.php?id=<?= htmlspecialchars($row["id"]) ?>">Edit</a> |
                        <a href="delete_user.php?id=<?= htmlspecialchars($row["id"]) ?>" class="delete" onclick="return confirm('Hapus user ini?')">Hapus</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <a href="../logout.php" class="logout-link">Logout</a>
</body>
</html>