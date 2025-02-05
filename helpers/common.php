<?php
//get access token
function get_access_token() {
    // $access_token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjozLCJleHAiOjE3MzgyMjc3NjUsImlhdCI6MTczODE0MTM2NX0.wm2G2N3rNLH5ghR8tPulWNC-t1tukBKxj-9qMZrJeBk";
    // return $access_token;
    // session_start();

    // Kiểm tra nếu token đã được lưu trữ trong session
    if (isset($_SESSION['jwt_token'])) {
        return $_SESSION['jwt_token'];
    }

    // Trường hợp không tìm thấy token
    return null;
}

//return current domain
function get_current_domain() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $protocol = "https";
    $host = $_SERVER['HTTP_HOST'];
    return $protocol . '://' . $host;
}

//return image domain
function get_image_domain() {
    if (file_exists(__DIR__ . '/../.env')) {
        $env = parse_ini_file(__DIR__ . '/../.env');
        return $env['DOMAIN_URL'] ?? '';
    } else {
        throw new Exception(".env file not found.");
    }
}

function hasHttpOrHttps($url) {
    if (isset($url) && (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0)) {
        return true;
    }
    return false;
}

function get_time_ago($datetime) {
    $timestamp = strtotime($datetime);
    $currentTime = time();
    $timeDifference = $currentTime - $timestamp;

    if ($timeDifference < 1) {
        return 'tức thì';
    }

    $timeUnits = [
        31536000 => 'năm',
        2592000 => 'tháng',
        604800 => 'tuần',
        86400 => 'ngày',
        3600 => 'giờ',
        60 => 'phút',
        1 => 'giây'
    ];

    foreach ($timeUnits as $unitSeconds => $unitName) {
        if ($timeDifference >= $unitSeconds) {
            $value = floor($timeDifference / $unitSeconds);
            return $value . ' ' . $unitName . ($value > 1 ? '' : '') . ' trước';
        }
    }
}
