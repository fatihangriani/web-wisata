<?php
session_start();
include "../koneksi.php";

// Cek apakah user adalah admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Hitung total data
$total_wisata = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM wisata"))['total'];
$total_kategori = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kategori"))['total'];
$total_user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM user"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <style>
        :root {
            --primary: #0057a3;
            --secondary: #ff6b6b;
            --success: #28a745;
            --warning: #ffc107;
            --light: #f8f9fa;
            --dark: #343a40;
            --white: #fff;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--light);
            color: var(--dark);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background: var(--white);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: var(--white);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-card h3 {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .stat-card p {
            color: var(--dark);
            font-weight: 600;
        }
        
        .actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: var(--primary);
            color: var(--white);
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            background: #004080;
            transform: translateY(-2px);
        }
        
        .btn-success {
            background: var(--success);
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        .btn-warning {
            background: var(--warning);
            color: var(--dark);
        }
        
        .btn-warning:hover {
            background: #e0a800;
        }
        
        .btn-danger {
            background: var(--secondary);
        }
        
        .btn-danger:hover {
            background: #e55c5c;
        }
        
        .logout {
            text-align: right;
            margin-bottom: 20px;
        }
        
        .logout a {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logout">
            <a href="../logout.php">Logout</a>
        </div>
        
        <div class="header">
            <h1>Dashboard Admin</h1>
            <p>Selamat datang, <?php echo $_SESSION['username']; ?>!</p>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <h3><?php echo $total_wisata; ?></h3>
                <p>Total Wisata</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $total_kategori; ?></h3>
                <p>Total Kategori</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $total_user; ?></h3>
                <p>Total User</p>
            </div>
        </div>
        
        <div class="actions">
            <a href="index.php" class="btn">Kelola Wisata</a>
            <a href="kategori.php" class="btn btn-success">Kelola Kategori</a>
            <a href="user.php" class="btn btn-warning">Kelola User</a>
            <a href="../user/beranda.php" class="btn btn-danger">Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>