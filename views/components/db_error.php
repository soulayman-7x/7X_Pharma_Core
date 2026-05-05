<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Error | 7X Pharma Core</title>
    
    <link rel="icon" type="image/png" href="<?= BASE_URL ?? '' ?>/assets/images/logo/7X-PHARMA-ICO.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?? '' ?>/assets/css/global.css">
    
    <style>
        .error-layout {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .error-card {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(220, 38, 38, 0.2);
            border-radius: 24px;
            padding: 50px 40px;
            text-align: center;
            max-width: 450px;
            width: 90%;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        }

        .icon-box {
            width: 80px;
            height: 80px;
            background: rgba(220, 38, 38, 0.1);
            border: 2px solid #dc2626;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 25px;
            color: #dc2626;
            font-size: 32px;
            font-weight: bold;
            box-shadow: 0 0 20px rgba(220, 38, 38, 0.2);
        }

        .error-title {
            font-size: 28px;
            margin: 0 0 15px;
            letter-spacing: -0.5px;
            color: #ffffff;
        }

        .error-msg {
            font-size: 16px;
            line-height: 1.6;
            color: #9ca3af;
            margin-bottom: 35px;
        }

        .brand-footer {
            margin-top: 50px;
            font-size: 11px;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 4px;
        }
    </style>
</head>
<body>

    <div class="error-layout">
        <div class="error-card">
            <div class="icon-box">!</div>
            <h1 class="error-title">Connection Lost</h1>
            <p class="error-msg">The 7X Pharma core system is currently unable to establish a secure handshake with the database.</p>
            
            <a href="javascript:location.reload()" class="btn btn-outline" style="border-color: #dc2626; color: #dc2626;">
                Retry Connection
            </a>

            <div class="brand-footer">7X Pharma Core v1.0</div>
        </div>
    </div>

</body>
</html>