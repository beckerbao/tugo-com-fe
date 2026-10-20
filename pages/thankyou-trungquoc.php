<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cảm ơn Quý khách đã gửi đánh giá - Tugo</title>
    <style>
        :root {
            --tugo-primary: #007BFF;
            --tugo-primary-hover: #0056b3;
            --tugo-primary-light: #f0f7ff;
            --tugo-success: #10b981;
            --tugo-success-light: #ecfdf5;
            --tugo-text-main: #0f172a;
            --tugo-text-secondary: #475569;
            --tugo-bg: #f8fafc;
            --tugo-card-bg: #ffffff;
            --tugo-border: #e2e8f0;
            --tugo-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: var(--tugo-bg);
            color: var(--tugo-text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px 16px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .thankyou-wrapper {
            max-width: 560px;
            width: 100%;
        }

        .thankyou-card {
            background-color: var(--tugo-card-bg);
            border-radius: 16px;
            padding: 40px 32px;
            box-shadow: var(--tugo-shadow);
            border: 1px solid var(--tugo-border);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .brand-logo-container {
            margin-bottom: 24px;
        }

        .brand-logo {
            max-height: 42px;
            width: auto;
            object-fit: contain;
        }

        .success-icon-wrapper {
            width: 72px;
            height: 72px;
            background-color: var(--tugo-success-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px auto;
            animation: pulseIn 0.5s ease-out;
        }

        .success-icon {
            width: 36px;
            height: 36px;
            color: var(--tugo-success);
        }

        h1 {
            color: var(--tugo-primary);
            font-size: 24px;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 16px;
        }

        .thankyou-desc {
            font-size: 15px;
            line-height: 1.65;
            color: var(--tugo-text-secondary);
            margin-bottom: 32px;
            text-align: justify;
            text-justify: inter-word;
        }

        .action-container {
            display: flex;
            justify-content: center;
        }

        .home-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: var(--tugo-primary);
            color: #ffffff;
            font-size: 16px;
            font-weight: 600;
            padding: 13px 32px;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 123, 255, 0.2);
            min-height: 48px;
        }

        .home-button:hover {
            background-color: var(--tugo-primary-hover);
            box-shadow: 0 6px 12px -2px rgba(0, 123, 255, 0.3);
            transform: translateY(-1px);
        }

        .home-button:active {
            transform: translateY(0);
        }

        .footer-tagline {
            margin-top: 24px;
            font-size: 13px;
            color: #94a3b8;
        }

        @keyframes pulseIn {
            0% {
                transform: scale(0.6);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @media (max-width: 600px) {
            .thankyou-card {
                padding: 28px 20px;
                border-radius: 12px;
            }

            .brand-logo {
                max-height: 36px;
            }

            .success-icon-wrapper {
                width: 60px;
                height: 60px;
                margin-bottom: 20px;
            }

            .success-icon {
                width: 30px;
                height: 30px;
            }

            h1 {
                font-size: 20px;
                margin-bottom: 14px;
            }

            .thankyou-desc {
                font-size: 14px;
                line-height: 1.6;
                text-align: left;
                margin-bottom: 28px;
            }

            .home-button {
                width: 100%;
                font-size: 15px;
                padding: 12px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="thankyou-wrapper">
        <div class="thankyou-card">
            <div class="brand-logo-container">
                <img src="../assets/images/tugologo.png" alt="Tugo Logo" class="brand-logo" onerror="this.style.display='none'">
            </div>
            <div class="success-icon-wrapper">
                <svg class="success-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6L9 17l-5-5"></path>
                </svg>
            </div>
            <h1>Cảm ơn Quý khách đã gửi đánh giá!</h1>
            <p class="thankyou-desc">
                Tugo chân thành cảm ơn những ý kiến đóng góp quý báu của Quý khách về Trưởng đoàn, Hướng dẫn viên và các điểm tham quan trong tour Trung Quốc. Phản hồi của Quý khách là cơ sở quan trọng giúp Tugo không ngừng nâng cao chất lượng dịch vụ và năng lực thuyết minh của đội ngũ hướng dẫn viên.
            </p>
            <div class="action-container">
                <a href="home.php" class="home-button">Về trang chủ Tugo</a>
            </div>
            <div class="footer-tagline">
                Tugo.com.vn &bull; Trải nghiệm chất lượng vượt trội
            </div>
        </div>
    </div>
</body>
</html>
