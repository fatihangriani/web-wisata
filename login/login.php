<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../koneksi.php";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = md5($_POST['password']); // gunakan md5 sesuai data di DB

    $query = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username' AND password='$password'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        // Simpan data user ke session
        $_SESSION['login'] = true;
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['email'] = $data['email'];
        $_SESSION['role'] = $data['role'];

        // Redirect berdasarkan role
        if ($data['role'] === 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../user/beranda.php");
        }
        exit;
    } else {
        $error = "Username atau password salah!"; 
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #0077b6;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .login-box {
      background: white;
      padding: 30px;
      border-radius: 10px;
      width: 320px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    h2 {
      text-align: center;
      color: #0077b6;
      margin-bottom: 20px;
    }
    .form-group {
      margin-bottom: 15px;
    }
    label {
      display: block;
      margin-bottom: 5px;
      color: #333;
      font-weight: bold;
    }
    input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }
    button {
      width: 100%;
      padding: 10px;
      background: #0077b6;
      color: white;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
      font-size: 16px;
    }
    button:hover {
      background: #005f8d;
    }
    .error {
      color: red;
      text-align: center;
      margin-top: 10px;
      padding: 10px;
      background: #ffe6e6;
      border-radius: 5px;
    }
    .back-link {
      text-align: center;
      margin-top: 15px;
    }
    .back-link a {
      color: #0077b6;
      text-decoration: none;
    }
    .back-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="login-box">
  <h2>Login</h2>
  <form method="POST">
    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" name="username" placeholder="Masukkan username" required>
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" name="password" placeholder="Masukkan password" required>
    </div>
    <button type="submit" name="login">Login</button>
  </form>
  
  <?php if (isset($error)): ?>
    <p class="error"><?php echo $error; ?></p>
  <?php endif; ?>
  
  <div class="back-link">
    <a href="../user/beranda.php">← Kembali ke Beranda</a>
  </div>
</div>

</body>
</html>