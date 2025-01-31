<?php 
session_start();
$page_title = "Homepage";
include '../includes/header.php'; 
include '../helpers/apiCaller.php';
include '../helpers/common.php';

// Gọi API để lấy dữ liệu
APICaller::init();
$statistics = APICaller::get('/statistics');
$posts = APICaller::get('/posts');

$statistics = isset($statistics['data']) ? $statistics['data'] : [];
$posts = isset($posts['data']['posts']) ? $posts['data']['posts'] : [];
?>
<script src="../assets/js/home.js"></script>
<!-- <div class="header">
    <div class="logo">My Community</div>
    <div class="search-bar">
        <input type="text" placeholder="Search...">
    </div>
</div> -->

<div class="hero">
    <h1>Chào mừng bạn đến với GoReview</h1>
    <p>Kết nối, chia sẻ kinh nghiệm du lịch</p>
    <?php
    // var_dump(get_access_token());
    //not login yet
    if(get_access_token()==null){
        
    ?>
        <button onclick="location.href='register.php'">Join Now</button>
    <?php
    }
    ?>
</div>

<!-- Statistics Section -->
<div class="statistics">
    <div class="stat">
        <div class="value"><?php echo $statistics['users'] ?? '0'; ?>+</div>
        <div class="label">Thành viên</div>
    </div>
    <div class="stat">
        <div class="value"><?php echo $statistics['posts'] ?? '0'; ?>+</div>
        <div class="label">Bài viết</div>
    </div>
    <!-- <div class="stat">
        <div class="value"><?php echo $statistics['comments'] ?? '0'; ?>+</div>
        <div class="label">Comments</div>
    </div> -->
</div>

<div class="feed">
    <?php 
    foreach ($posts as $post): 
        $post['time_ago'] = get_time_ago($post['created_at']);
        if($post['type'] == 'tour'){ 
            include '../includes/post-tour.php';
        }else if($post['type'] == 'general'){
            include '../includes/post-general.php';
        }
    endforeach; 
    ?>
    <div class="spacer"></div>
</div>

<!-- Include thêm các phần như Statistics và Feed -->

<?php include '../includes/navbar.php'; ?>
<?php include '../includes/footer.php'; ?>