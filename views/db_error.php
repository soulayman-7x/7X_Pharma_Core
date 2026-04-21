<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Error | 7X Pharma Core</title>
    <style>
        /* CSS Variables for easy maintenance */
        :root {
            --bg-dark: #050505;
            --accent-green: #00ff88;
            --muted-green: #004d2c;
            --text-main: #ffffff;
            --text-dim: #888888;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--bg-dark);
            color: var(--text-main);
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        /* The Glass Card */
        .error-card {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(0, 255, 136, 0.1);
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
            background: rgba(0, 255, 136, 0.05);
            border: 2px solid var(--accent-green);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 25px;
            color: var(--accent-green);
            font-size: 32px;
            font-weight: bold;
            box-shadow: 0 0 20px rgba(0, 255, 136, 0.2);
        }

        h1 {
            font-size: 28px;
            margin: 0 0 15px;
            letter-spacing: -0.5px;
            color: var(--accent-green);
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            color: var(--text-dim);
            margin-bottom: 35px;
        }

        /* Cyber Button */
        .btn-retry {
            background: transparent;
            color: var(--accent-green);
            border: 1px solid var(--accent-green);
            padding: 14px 35px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-retry:hover {
            background: var(--accent-green);
            color: var(--bg-dark);
            box-shadow: 0 0 30px rgba(0, 255, 136, 0.4);
            transform: translateY(-2px);
        }

        .brand-footer {
            margin-top: 50px;
            font-size: 11px;
            color: var(--muted-green);
            text-transform: uppercase;
            letter-spacing: 4px;
        }
    </style>
</head>
<body>

    <div class="error-card">
        <div class="icon-box">!</div>
        <h1>Connection Lost</h1>
        <p>The 7X Pharma core system is currently unable to establish a secure handshake with the database.</p>
        
        <a href="javascript:location.reload()" class="btn-retry">Retry Link</a>

        <div class="brand-footer">7X Pharma Core v1.0</div>
    </div>

</body>
</html>