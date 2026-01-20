<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($title) ? $title : 'Login Kasir' ?></title>

  <link rel="shortcut icon" href="<?= base_url('assets/assets/compiled/svg/favicon.svg') ?>" type="image/x-icon">

  <!-- CSS Mazer -->
  <link rel="stylesheet" href="<?= base_url('assets/assets/compiled/css/app.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/assets/compiled/css/app-dark.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/assets/compiled/css/auth.css') ?>">

  <style>
    body{
      background: radial-gradient(circle at top, rgba(13,110,253,0.18), transparent 55%),
                  radial-gradient(circle at bottom, rgba(25,135,84,0.18), transparent 55%);
      min-height: 100vh;
      overflow-x: hidden;
    }

    .login-wrap{
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }

    .login-card{
      width: 100%;
      max-width: 440px;
      border-radius: 20px;
      background: #ffffff;
      padding: 28px;
      box-shadow: 0 16px 40px rgba(0,0,0,0.08);
      animation: fadeUp 0.55s ease;
    }

    @keyframes fadeUp{
      from{ opacity: 0; transform: translateY(14px); }
      to{ opacity: 1; transform: translateY(0); }
    }

    .title{
      font-weight: 800;
      font-size: 28px;
      margin-bottom: 6px;
      text-align: center;
      letter-spacing: 0.2px;
    }

    .subtitle{
      font-size: 14px;
      color: #6c757d;
      text-align: center;
      margin-bottom: 22px;
      line-height: 1.5;
    }

    .divider{
      height: 1px;
      background: rgba(0,0,0,0.08);
      margin: 18px 0;
    }

    .btn-login{
      padding: 12px 16px;
      font-weight: 700;
      border-radius: 14px;
    }

    .hint{
      color: #6c757d;
      font-size: 12.5px;
      text-align: center;
      margin: 14px 0 0 0;
    }

    .pw-wrap{
      position: relative;
    }

    .pw-toggle{
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      border: none;
      background: transparent;
      cursor: pointer;
      padding: 6px;
      border-radius: 10px;
      color: #6c757d;
      transition: 0.2s ease;
    }

    .pw-toggle:hover{
      background: rgba(0,0,0,0.05);
      color: #000;
    }

    .pw-input{
      padding-right: 52px !important;
    }

    .mini-badge{
      display: inline-block;
      font-size: 12px;
      padding: 6px 12px;
      border-radius: 999px;
      background: rgba(13,110,253,0.12);
      color: rgba(13,110,253,1);
      font-weight: 600;
      margin: 0 auto 10px auto;
    }

    .mini-badge-wrap{
      display: flex;
      justify-content: center;
    }
  </style>
</head>

<body>
  <script src="<?= base_url('assets/assets/static/js/initTheme.js') ?>"></script>

  <div class="login-wrap">
    <div class="login-card">

      <div class="mini-badge-wrap">
        <span class="mini-badge">Sistem Kasir</span>
      </div>

      <h1 class="title">Login</h1>
      <p class="subtitle">Silakan masuk untuk mengelola transaksi dan data toko</p>

      <?php if ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger">
          <?= $this->session->flashdata('error') ?>
        </div>
      <?php endif; ?>

      <form action="<?= base_url('auth/login_process') ?>" method="post">

        <div class="form-group position-relative has-icon-left mb-4">
          <input
            type="text"
            class="form-control form-control-xl"
            name="username"
            placeholder="Username"
            required
            autocomplete="username"
          >
          <div class="form-control-icon">
            <i class="bi bi-person"></i>
          </div>
        </div>

        <div class="form-group position-relative has-icon-left mb-3 pw-wrap">
          <input
            id="password"
            type="password"
            class="form-control form-control-xl pw-input"
            name="password"
            placeholder="Password"
            required
            autocomplete="current-password"
          >
          <div class="form-control-icon">
            <i class="bi bi-shield-lock"></i>
          </div>

          <button type="button" class="pw-toggle" id="togglePassword" aria-label="Show Password">
            <i class="bi bi-eye" id="iconEye"></i>
          </button>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="form-check form-check-lg d-flex align-items-end">
            <input class="form-check-input me-2" type="checkbox" name="remember" id="remember">
            <label class="form-check-label text-gray-600" for="remember">
              Ingat saya
            </label>
          </div>

          <a class="font-bold" href="<?= base_url('auth/forgot_password') ?>">
            Lupa password?
          </a>
        </div>

        <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-2 btn-login">
          Masuk
        </button>

        <div class="divider"></div>

        <p class="hint">
          © <?= date('Y') ?> K01 • Sistem Kasir Modern
        </p>

      </form>

    </div>
  </div>

  <script>
    const passInput = document.getElementById("password");
    const toggleBtn = document.getElementById("togglePassword");
    const iconEye = document.getElementById("iconEye");

    toggleBtn.addEventListener("click", function () {
      const isPassword = passInput.getAttribute("type") === "password";
      passInput.setAttribute("type", isPassword ? "text" : "password");

      iconEye.className = isPassword ? "bi bi-eye-slash" : "bi bi-eye";
      toggleBtn.setAttribute("aria-label", isPassword ? "Hide Password" : "Show Password");
    });
  </script>

</body>
</html>
