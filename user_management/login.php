<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $pass  = $_POST["password"];
    $role  = $_POST["role"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && password_verify($pass, $row["password"])) {
        $_SESSION["user"] = $row;
        if ($row["role"] === "admin") {
            header("Location: admin/dashboard_admin.php");
        } else {
            header("Location: dashboard.php");
        }
        exit;
    } else {
        $error = "Login gagal! Periksa email, password, dan role.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #007bff;
            --primary-darkblue: #0056b3;
            --success-green: #28a745;
            --danger-red: #dc3545;
            --light-gray: #f4f7f6;
            --dark-gray: #333;
            --border-color: #ddd;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        body {
            background: url('gudang-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: var(--dark-gray);
        }

        .login-wrapper {
            background-color: rgba(255, 255, 255, 0.95); 
            padding: 40px;
            border-radius: 15px;
            box-shadow: var(--shadow);
            width: 100%;
            max-width: 450px; 
            text-align: center;
            backdrop-filter: blur(5px); 
            -webkit-backdrop-filter: blur(5px); 
            animation: fadeIn 0.8s ease-out;
        }

        h2 {
            color: var(--primary-blue);
            margin-bottom: 25px;
            font-size: 2.2em;
            font-weight: 700;
        }

        h3 {
            color: var(--dark-gray);
            margin-top: 20px;
            margin-bottom: 25px;
            font-size: 1.2em;
            font-weight: 600;
        }

        .toggle-role {
            margin-bottom: 25px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .toggle-role button {
            padding: 12px 25px;
            border: 2px solid var(--primary-blue);
            border-radius: 30px; 
            background-color: transparent;
            color: var(--primary-blue);
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            outline: none;
        }

        .toggle-role button:hover {
            background-color: var(--primary-blue);
            color: white;
        }

        .toggle-role button.active-role {
            background-color: var(--primary-blue);
            color: white;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px; 
        }

        input[type="email"],
        input[type="password"] {
            padding: 15px 20px;
            width: calc(100% - 40px); 
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1em;
            box-sizing: border-box; 
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="email"]::placeholder,
        input[type="password"]::placeholder {
            color: #aaa;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
            outline: none;
        }

        p {
            margin-top: 15px;
            margin-bottom: 25px;
            font-size: 0.95em;
            color: #666;
        }

        p a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        p a:hover {
            color: var(--primary-darkblue);
            text-decoration: underline;
        }

        button[type="submit"] {
            padding: 15px 30px;
            background-color: var(--primary-blue);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 700;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.2);
            width: 100%; 
        }

        button[type="submit"]:hover {
            background-color: var(--primary-darkblue);
            transform: translateY(-2px);
        }

        .error-message {
            color: var(--danger-red);
            margin-top: 20px;
            font-size: 0.9em;
            font-weight: 600;
            background-color: rgba(220, 53, 69, 0.1);
            padding: 10px;
            border-radius: 5px;
            border: 1px solid var(--danger-red);
            animation: shake 0.5s ease-in-out;
        }

        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-10px); }
            40%, 80% { transform: translateX(10px); }
        }

        
        @media (max-width: 500px) {
            .login-wrapper {
                margin: 20px;
                padding: 30px;
                max-width: calc(100% - 40px);
            }
            h2 {
                font-size: 1.8em;
            }
            .toggle-role button {
                padding: 10px 20px;
            }
            input[type="email"],
            input[type="password"] {
                padding: 12px 15px;
            }
            button[type="submit"] {
                padding: 12px 20px;
            }
        }
    </style>
    <script>
        
        document.addEventListener('DOMContentLoaded', function() {
            setRole('user');
        });

        function setRole(role) {
            document.getElementById('role').value = role;
            document.getElementById('btn-user').classList.remove('active-role');
            document.getElementById('btn-admin').classList.remove('active-role');
            document.getElementById('btn-' + role).classList.add('active-role');
        }
    </script>
</head>
<body>
    <div class="login-wrapper">
        <h2>LOGIN</h2>
        <div class="toggle-role">
            <button type="button" id="btn-user" onclick="setRole('user')">Customer</button>
            <button type="button" id="btn-admin" onclick="setRole('admin')">Admin</button>
        </div>

        <form method="post">
            <input type="hidden" name="role" id="role" value="user"> <h3>Selamat datang.</h3>
            <input type="email" name="email" placeholder="Enter your email" required><br>
            <input type="password" name="password" placeholder="Enter password" required><br>
            <p>Belum punya akun? <a href="register.php">Daftar Sekarang</a></p>
            <button type="submit">LOGIN</button>
        </form>
        <?php if (!empty($error)) echo "<p class='error-message'>$error</p>"; ?>
    </div>
</body>
</html>
