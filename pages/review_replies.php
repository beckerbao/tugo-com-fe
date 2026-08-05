<?php
session_start();
include '../helpers/apiCaller.php';

header('Content-Type: application/json; charset=utf-8');

function get_admin_reply_code_from_request() {
    $headers = function_exists('getallheaders') ? getallheaders() : [];
    foreach ($headers as $name => $value) {
        if (strtolower($name) === 'x-admin-reply-code') {
            return trim((string) $value);
        }
    }

    return trim((string) ($_SERVER['HTTP_X_ADMIN_REPLY_CODE'] ?? ''));
}

function respond_with_api_result($response) {
    if (!is_array($response)) {
        http_response_code(502);
        echo json_encode(['status' => 'error', 'message' => 'Invalid response from review API.']);
        return;
    }

    if (($response['status'] ?? '') === 'error') {
        $message = (string) ($response['message'] ?? 'Review API request failed.');
        $httpCode = 502;
        if (stripos($message, 'forbidden') !== false || stripos($message, 'admin reply code') !== false) {
            $httpCode = 403;
        } elseif (stripos($message, 'not found') !== false) {
            $httpCode = 404;
        } elseif (stripos($message, 'invalid') !== false || stripos($message, 'required') !== false) {
            $httpCode = 400;
        }
        http_response_code($httpCode);
    }

    echo json_encode($response);
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$postId = filter_input(INPUT_GET, 'post_id', FILTER_VALIDATE_INT);
$replyId = filter_input(INPUT_GET, 'reply_id', FILTER_VALIDATE_INT);

if ($method === 'GET') {
    if (!$postId || $postId < 1) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid post ID.']);
        exit;
    }

    APICaller::init();
    respond_with_api_result(APICaller::get('/posts/' . $postId . '/replies'));
    exit;
}

$rawBody = file_get_contents('php://input');
$payload = json_decode($rawBody, true);
$payload = is_array($payload) ? $payload : [];
$adminReplyCode = get_admin_reply_code_from_request();
$headers = ['admin_reply_code: ' . $adminReplyCode];

APICaller::init();

if ($method === 'POST') {
    if (!$postId || $postId < 1) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid post ID.']);
        exit;
    }

    respond_with_api_result(APICaller::post('/posts/' . $postId . '/replies', $payload, $headers));
    exit;
}

if ($method === 'PUT') {
    if (!$replyId || $replyId < 1) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid reply ID.']);
        exit;
    }

    respond_with_api_result(APICaller::put('/review-replies/' . $replyId, $payload, $headers));
    exit;
}

if ($method === 'DELETE') {
    if (!$replyId || $replyId < 1) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid reply ID.']);
        exit;
    }

    respond_with_api_result(APICaller::delete('/review-replies/' . $replyId, [], $headers));
    exit;
}

http_response_code(405);
echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
