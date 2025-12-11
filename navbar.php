
<header>
  <nav>
    <h1>Pariwisata</h1>
    <ul>
      <li><a href="../user/beranda.php">Beranda</a></li>
      <li><a href="../user/menu.php">Menu</a></li>
      <li><a href="../user/favorite.php">Favorite</a></li>
      
      <!-- Login/User Info Section -->
      <?php if (!isset($_SESSION['login'])): ?>
        <li id="loginSection" class="auth-buttons">
          <a href="../login/register.php" class="register-btn">Daftar</a>
          <a href="../login/login.php" class="login-btn">Login</a>
        </li>
      <?php else: ?>
        <li id="userSection">
          <div class="user-info">
            <div class="user-avatar">
              <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
            </div>
            <span class="user-name">Halo, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <div class="dropdown">
              <a href="#" class="dropdown-toggle">▼</a>
              <ul class="dropdown-menu">
                <li><a href="../login/logout.php">Logout</a></li>
              </ul>
            </div>
          </div>
        </li>
      <?php endif; ?>
    </ul>
  </nav>
</header>

<style>
  /* NAVBAR STYLE YANG AMAN - pakai selector spesifik */
  header {
    background: #0077b6 !important;
    color: #fff !important;
    font-family: Arial, sans-serif !important;
    position: sticky !important;
    top: 0 !important;
    z-index: 1000 !important;
    width: 100% !important;
    box-sizing: border-box !important;
  }

  header nav {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    padding: 15px 30px !important;
    max-width: 1200px !important;
    margin: 0 auto !important;
    box-sizing: border-box !important;
  }

  header nav h1 {
    font-size: 22px !important;
    margin: 0 !important;
    font-weight: bold !important;
    color: #fff !important;
  }

  header nav ul {
    list-style: none !important;
    display: flex !important;
    gap: 25px !important;
    margin: 0 !important;
    padding: 0 !important;
    align-items: center !important;
  }

  header nav a {
    color: #fff !important;
    text-decoration: none !important;
    padding: 8px 12px !important;
    border-radius: 4px !important;
    transition: background 0.3s ease !important;
    font-size: 14px !important;
    display: block !important;
  }

  header nav a:hover {
    background: rgba(255, 255, 255, 0.1) !important;
  }

  /* Auth Buttons */
  header .auth-buttons {
    display: flex !important;
    gap: 10px !important;
    align-items: center !important;
  }

  header .register-btn {
    background: rgba(255, 255, 255, 0.2) !important;
    border: 1px solid rgba(255, 255, 255, 0.3) !important;
  }

  header .register-btn:hover {
    background: rgba(255, 255, 255, 0.3) !important;
  }

  header .login-btn {
    background: #fff !important;
    color: #0077b6 !important;
    font-weight: bold !important;
  }

  header .login-btn:hover {
    background: #f0f0f0 !important;
  }

  /* User dropdown */
  header .dropdown {
    position: relative !important;
  }

  header .dropdown-menu {
    position: absolute !important;
    top: 100% !important;
    left: 0 !important;
    background: #fff !important;
    color: #333 !important;
    list-style: none !important;
    padding: 8px 0 !important;
    margin: 0 !important;
    border-radius: 8px !important;
    display: none !important;
    min-width: 120px !important;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15) !important;
    border: 1px solid #e0e0e0 !important;
    z-index: 1001 !important;
  }

  header .dropdown-menu li {
    margin: 0 !important;
  }

  header .dropdown-menu a {
    color: #333 !important;
    display: block !important;
    padding: 8px 16px !important;
    transition: background 0.2s ease !important;
    text-align: center !important;
    font-size: 14px !important;
  }

  header .dropdown-menu a:hover {
    background: #f5f5f5 !important;
    color: #0077b6 !important;
  }

  header .dropdown:hover .dropdown-menu {
    display: block !important;
    animation: fadeIn 0.2s ease !important;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(-5px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  header .user-info {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
  }

  header .user-avatar {
    width: 36px !important;
    height: 36px !important;
    border-radius: 50% !important;
    background-color: #fff !important;
    color: #0077b6 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-weight: bold !important;
    font-size: 14px !important;
  }

  header .user-name {
    font-weight: bold !important;
    font-size: 14px !important;
    color: #fff !important;
  }

  header .dropdown-toggle {
    font-size: 12px !important;
    padding: 4px 8px !important;
    color: #fff !important;
  }

  /* Responsive */
  @media (max-width: 768px) {
    header nav {
      padding: 10px 15px !important;
    }
    
    header nav ul {
      gap: 15px !important;
    }
    
    header nav a {
      padding: 6px 10px !important;
      font-size: 13px !important;
    }
    
    header .auth-buttons {
      gap: 8px !important;
    }
    
    header .dropdown-menu {
      min-width: 100px !important;
    }
    
    header .user-name {
      display: none !important;
    }
  }

  @media (max-width: 480px) {
    header .auth-buttons {
      flex-direction: column !important;
      gap: 5px !important;
    }
    
    header .auth-buttons a {
      padding: 6px 8px !important;
      font-size: 12px !important;
      text-align: center !important;
      min-width: 60px !important;
    }
    
    header nav h1 {
      font-size: 18px !important;
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.querySelector('.dropdown');
    if (dropdown) {
      let hideTimeout;

      dropdown.addEventListener('mouseenter', function() {
        clearTimeout(hideTimeout);
        this.querySelector('.dropdown-menu').style.display = 'block';
      });

      dropdown.addEventListener('mouseleave', function() {
        hideTimeout = setTimeout(() => {
          this.querySelector('.dropdown-menu').style.display = 'none';
        }, 300);
      });

      const dropdownMenu = document.querySelector('.dropdown-menu');
      
      if (dropdownMenu) {
        dropdownMenu.addEventListener('mouseenter', function() {
          clearTimeout(hideTimeout);
        });

        dropdownMenu.addEventListener('mouseleave', function() {
          this.style.display = 'none';
        });
      }
    }
  });
</script>