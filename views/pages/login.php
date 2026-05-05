<?php 
require_once '../app/config/constants.php';
// echo password_hash('123456', PASSWORD_DEFAULT);
// exit();
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — 7X Pharma Nexus</title>
    <meta name="description" content="Secure login for 7X Pharma Nexus pharmacy management system.">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL?>/assets/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL?>/assets/css/login.css">
    <link rel="shortcut icon" href="<?= BASE_URL?>/assets/images/logo/7X-PHARMA-ICO.png" type="image/x-icon">
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
                    <img src="<?= BASE_URL?>/assets/images/logo/7X-PHARMA-ICO.png" alt="7x pharma logo">
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
                        autocomplete="username"
                    >
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
                        autocomplete="current-password"
                    >
                    <a href="#" class="forgot-password">Forgot password?</a>
                </div>


                <button type="submit" class="btn btn-primary btn-block" id="btn-login">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
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
