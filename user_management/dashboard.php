<?php
session_start();
include 'db.php'; 

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}
$user = $_SESSION["user"];

$message = ''; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $user['id'];
    $name = $_POST["name"];
    $email = $_POST["email"];

 
    $sql_update = "UPDATE users SET name=?, email=? WHERE id=?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ssi", $name, $email, $id);

   
    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] === 0) {
        $upload_dir = "uploads/"; 
        $photo = time() . "_" . basename($_FILES["photo"]["name"]); 
        $target_file = $upload_dir . $photo;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

       
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            $message = "<p class='message error'>Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.</p>";
        } else if ($_FILES["photo"]["size"] > 5000000) { 
            $message = "<p class='message error'>Maaf, ukuran file terlalu besar.</p>";
        } else {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                
                if (!empty($user['photo']) && file_exists($upload_dir . $user['photo']) && $user['photo'] !== 'default_profile.png') {
                    unlink($upload_dir . $user['photo']);
                }

                $stmt_photo = $conn->prepare("UPDATE users SET photo=? WHERE id=?");
                $stmt_photo->bind_param("si", $photo, $id);
                $stmt_photo->execute();
                $stmt_photo->close();
                $user['photo'] = $photo; 
                $message = "<p class='message success'>Profil dan foto berhasil diperbarui!</p>";
            } else {
                $message = "<p class='message error'>Maaf, terjadi kesalahan saat mengunggah foto Anda.</p>";
            }
        }
    }

    if ($stmt->execute()) {
        $user['name'] = $name;
        $user['email'] = $email;
        $_SESSION["user"] = $user; 

        if (empty($message)) { 
             $message = "<p class='message success'>Profil berhasil diperbarui.</p>";
        }
    } else {
        $message = "<p class='message error'>Terjadi kesalahan saat memperbarui profil: " . $conn->error . "</p>";
    }
    $stmt->close();
}


$display_photo = !empty($user['photo']) ? htmlspecialchars($user['photo']) : 'default_profile.png';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - GudangNET</title>
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
            min-height: 100vh; 
            justify-content: center; 
        }
        .profile-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 500px;
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-header {
            display: flex;
            align-items: center;
            justify-content: center; 
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap; 
        }
        .profile-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #007bff; 
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin: 0;
            color: #007bff;
            font-size: 1.8em;
            font-weight: 600;
        }
        h3 {
            color: #555;
            margin-top: 30px;
            margin-bottom: 20px;
            font-size: 1.4em;
        }
        .logout-link {
            display: inline-block; 
            background-color: #dc3545; 
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1em;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-top: 20px; 
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .logout-link:hover {
            background-color: #c82333;
            transform: translateY(-2px); 
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            text-align: left;
            margin-top: 20px;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            color: #555;
        }
        input[type="text"],
        input[type="email"],
        input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1em;
            box-sizing: border-box; 
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        input[type="text"]:focus,
        input[type="email"]:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
            outline: none;
        }
        input[type="file"] {
            padding: 8px; 
            cursor: pointer;
        }
        button[type="submit"] {
            background-color: #28a745; 
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-top: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        button[type="submit"]:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        
        .message {
            margin-top: 20px;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            opacity: 0; 
            animation: fadeIn 0.5s forwards; 
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        
        @media (max-width: 600px) {
            .profile-container {
                padding: 25px;
                margin: 10px;
                max-width: calc(100% - 20px);
            }
            h2 {
                font-size: 1.5em;
            }
            .profile-header {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <?php if (!empty($message)) echo $message; ?>

        <div class="profile-header">
            <img src="uploads/<?= $display_photo ?>" alt="Foto Profil" class="profile-photo">
            <h2>Selamat Datang, <?= htmlspecialchars($user['name']) ?></h2>
        </div>

        <h3>Edit Profil</h3>
        <form method="post" enctype="multipart/form-data">
            <label for="name">Nama:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <label for="photo">Ganti Foto:</label>
            <input type="file" id="photo" name="photo" accept="image/*"> <button type="submit">Update Profil</button>
        </form>

        <a href="logout.php" class="logout-link">Logout</a>
    </div>
</body>
</html>
