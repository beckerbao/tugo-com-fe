<?php
include '../helpers/apiCaller.php';
include '../helpers/common.php';

if (!isset($_GET['cursor'])) {
    echo json_encode(['error' => 'Cursor is required.']);
    exit;
}

$cursor = isset($_GET['cursor']) ? $_GET['cursor'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';
//nếu type=all thi $type='';
if ($type === 'all') {
    $type = '';
}

APICaller::init();
$response = APICaller::get("/posts",array("page_size"=>20,"page"=>$cursor,'type'=>$type));

if (isset($response['data'])) {
    $posts = $response['data']['posts'];
    $nextCursor = $response['data']['cursor'];
    
    $html = '';
    foreach ($posts as $post) {
        if ($post['type'] === 'review') {
            $post['time_ago'] = get_time_ago($post['created_at']);
            $html .= renderPostReview($post);
        } elseif ($post['type'] === 'general') {
            $post['time_ago'] = get_time_ago($post['created_at']);
            $html .= renderPostGeneral($post);
        }
    }

    try {
        $response = [
            'html' => $html,
            'nextCursor' => $nextCursor
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
    } catch (Exception $e) {
        header('Content-Type: application/json', true, 500);
        echo json_encode(['error' => $e->getMessage()]);
    }    
} else {
    echo json_encode(['error' => 'Failed to fetch posts.']);
}

/**
 * Render post-review HTML from a template file.
 */
function renderPostReview($post) {
    ob_start();
    include '../includes/post-review.php';
    return ob_get_clean();
}

/**
 * Render general post HTML from a template file.
 */
function renderPostGeneral($post) {
    ob_start();
    include '../includes/post-general.php';
    return ob_get_clean();
}