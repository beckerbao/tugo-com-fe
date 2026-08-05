<?php
// scripts/test_pass_install.php
// Proxy script to trigger Apple Wallet pass generation and download the file

$response = null;
$error = null;

// Config: Default to localhost:8080 for local testing
// Use host.docker.internal if running inside Docker container to access host
// Or use localhost if running via php -S on host
$baseUrl = getenv('BASE_URL') ?: 'http://host.docker.internal:8080';

// Ensure URL format
if (strpos($baseUrl, 'http') !== 0) {
    $baseUrl = 'http://' . ltrim($baseUrl, '/');
}

$api_url = rtrim($baseUrl, '/') . '/api/v1/wallet/apple/pass';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Generate random balance and code to make each pass unique-ish
    $randomBalance = rand(50000, 500000);
    $randomCode = 'TG' . rand(100000, 999999);
    
    $payload = [
        'user_id' => 87,
        'wallet_balance' => $randomBalance,
        'tier' => 'Gold',
        'member_code' => $randomCode,
        'locale' => 'vi-VN',
    ];

    $json = json_encode($payload);
    
    $ch = curl_init($api_url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            // User-Agent: We use generic one, API now always returns binary regardless
            'User-Agent: AppleWallet/TestScript',
        ],
        CURLOPT_POSTFIELDS => $json,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 30,
        // Disable SSL verification for local dev if needed
        CURLOPT_SSL_VERIFYPEER => false,
    ]);

    $result = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($result === false) {
        $error = 'Lỗi kết nối API: ' . $curlError . " (URL: $api_url)";
    } else {
        // Check successful status
        if ($status >= 200 && $status < 300) {
            // Check magic bytes for ZIP/PKPASS (starts with 'PK')
            if (substr($result, 0, 2) === 'PK') {
                // Success! Stream the file to browser
                header('Content-Description: File Transfer');
                header('Content-Type: application/vnd.apple.pkpass');
                header('Content-Disposition: attachment; filename="tugo-wallet-' . $randomCode . '.pkpass"');
                header('Content-ABos: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . strlen($result));
                echo $result;
                exit; // Stop execution to prevent HTML output
            } else {
                // Server returned success but not a PKPASS file?
                $error = "Server output format invalid (Expected PK header). Output start: " . substr($result, 0, 100);
            }
        } else {
            $error = "API Error (HTTP $status): " . $result;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Apple Wallet Pass Test</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; margin: 0; padding: 40px; background: #f0f2f5; color: #1c1e21; text-align: center; }
        .card { max-width: 400px; margin: 40px auto; padding: 32px; border-radius: 20px; background: #fff; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; margin-bottom: 24px; }
        
        button { 
            display: block; width: 100%; 
            background: #000; color: #fff; 
            border: none; padding: 18px; 
            border-radius: 14px; cursor: pointer; 
            font-size: 16px; font-weight: 600;
            display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        button:hover { opacity: 0.85; transform: scale(0.98); }
        button:active { transform: scale(0.95); }
        
        .icon-apple { font-size: 20px; margin-bottom: 2px; }
        
        .error { margin-top: 20px; padding: 16px; background: #fee2e2; color: #991b1b; border-radius: 12px; font-size: 14px; text-align: left;}
        
        .hint { margin-top: 20px; font-size: 13px; color: #666; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Tugo Wallet Pass</h2>
        
        <form method="post">
            <button type="submit">
                <span class="icon-apple"></span> Add to Apple Wallet
            </button>
        </form>

        <?php if ($error): ?>
            <div class="error">
                <strong>Lỗi:</strong> <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>
        
        <p class="hint">Nhấn nút để tạo và tải file .pkpass mới nhất.</p>
    </div>
</body>
</html>
