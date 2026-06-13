<?php
require_once '../app/config/constants.php';

?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — <?= APP_NAME ?></title>
    <meta name="description" content="Secure login for 7X Pharma Nexus pharmacy management system.">

    <!-- FONTAWESOM CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/login.css">
    <link rel="shortcut icon" href="<?= BASE_URL ?>/assets/images/logo/7X-PHARMA-ICO.png" type="image/x-icon">
</head>

<body>

    <!-- Theme Toggle -->
    <div class="theme-toggle-wrapper">
        <button id="theme-toggle" class="btn-icon" aria-label="Toggle Theme">
            <span id="theme-icon"></span>
        </button>
    </div>

    <main class="login-container">
        <div class="login-card">

            <div class="login-header">
                <div class="logo-wrapper">
                    <img src="<?= BASE_URL ?>/assets/images/logo/7X-PHARMA-ICO.png" alt="7x pharma logo">
                </div>
                <h1 class="login-title">7X Pharma Nexus</h1>
                <p class="login-subtitle">Sign in to your pharmacy dashboard</p>
            </div>



            <form class="login-form" action="<?= BASE_URL ?>/auth/login" method="POST" id="login-form">


                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-control"
                        placeholder="Enter your username"
                        required
                        autofocus
                        autocomplete="username">
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password">
                </div>


                <button type="submit" class="btn btn-primary btn-block" id="btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    Sign In
                </button>
            </form>

            <div class="login-footer">
                <p>&copy; 2026 7X Pharma Nexus. All rights reserved.</p>
            </div>

        </div>
    </main>

    <script src="<?= BASE_URL ?>/assets/js/theme.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/toast.js"></script>

    <?php if (isset($toast) && $toast !== null): ?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showToast("<?= htmlspecialchars($toast['message']) ?>", "<?= htmlspecialchars($toast['type']) ?>");
            });
        </script>
    <?php endif; ?>
</body>

</html>