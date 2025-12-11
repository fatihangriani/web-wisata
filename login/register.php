<?php
include "../koneksi.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm']);

    // Validasi input
    if ($username == "" || $email == "" || $password == "" || $confirm == "") {
        $error = "⚠️ Semua kolom wajib diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "⚠️ Format email tidak valid!";
    } elseif ($password !== $confirm) {
        $error = "⚠️ Konfirmasi password tidak cocok!";
    } else {
        // Cek apakah email sudah digunakan
        $cek = mysqli_query($koneksi, "SELECT * FROM user WHERE email = '$email'");
        if (mysqli_num_rows($cek) > 0) {
            $error = "⚠️ Email sudah terdaftar, gunakan email lain.";
        } else {
            // Enkripsi password (MD5)
            $hashed = md5($password);

            // Simpan data user baru
            $insert = mysqli_query($koneksi, "
                INSERT INTO user (username, email, password, role)
                VALUES ('$username', '$email', '$hashed', 'user')
            ");

            if ($insert) {
                // Ambil data user yang baru
                $result = mysqli_query($koneksi, "SELECT * FROM user WHERE email = '$email'");
                $user = mysqli_fetch_assoc($result);

                // Set session
                $_SESSION['login'] = true;
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Arahkan ke halaman beranda
                header("Location: ../user/beranda.php");
                exit;
            } else {
                $error = "❌ Gagal menyimpan data ke database.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Akun - Pariwisata</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: "Segoe UI", Arial, sans-serif;
      background: linear-gradient(135deg, #0077b6, #48cae4);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .register-container {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      width: 370px;
    }

    h2 {
      text-align: center;
      color: #0077b6;
      margin-bottom: 20px;
    }

    label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
      color: #333;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
    }

    input:focus {
      outline: none;
      border-color: #0077b6;
      box-shadow: 0 0 5px rgba(0,119,182,0.3);
    }

    button {
      width: 100%;
      background: #0077b6;
      color: #fff;
      border: none;
      padding: 12px;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background: #005f91;
    }

    .error {
      background: #ffe5e5;
      color: #d00000;
      padding: 10px;
      border-radius: 6px;
      text-align: center;
      margin-bottom: 15px;
      font-size: 14px;
    }

    .login-link {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
    }

    .login-link a {
      color: #0077b6;
      text-decoration: none;
      font-weight: bold;
    }

    .login-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="register-container">
  <h2>Daftar Akun</h2>

  <?php if (!empty($error)): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post" action="">
    <label for="username">Username</label>
    <input type="text" name="username" id="username" placeholder="Masukkan username" required>

    <label for="email">Email</label>
    <input type="email" name="email" id="email" placeholder="Masukkan email" required>

    <label for="password">Password</label>
    <input type="password" name="password" id="password" placeholder="Masukkan password" required>

    <label for="confirm">Konfirmasi Password</label>
    <input type="password" name="confirm" id="confirm" placeholder="Ulangi password" required>

    <button type="submit" name="register">Daftar Sekarang</button>
  </form>

  <div class="login-link">
    Sudah punya akun? <a href="login.php">Login</a>
  </div>
</div>

</body>
</html>
