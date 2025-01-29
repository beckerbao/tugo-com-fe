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
    $host = $_SERVER['HTTP_HOST'] . "/tugo-com";
    return $protocol . '://' . $host;
}

//return image domain
function get_image_domain() {
    return "http://localhost:9090";
}