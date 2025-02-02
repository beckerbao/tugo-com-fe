<?php 
session_start();
$page_title = "Homepage";
include '../includes/header.php'; 
include '../helpers/apiCaller.php';
include '../helpers/common.php';

$type = isset($_GET['type']) ? $_GET['type'] : 'all';

// Gọi API để lấy dữ liệu
APICaller::init();
$statistics = APICaller::get('/statistics');
$postsResponse = APICaller::get('/posts',array("page_size"=>20,"page"=>1,'type'=>$type=='all' ? '' : $type));

$statistics = isset($statistics['data']) ? $statistics['data'] : [];
$posts = isset($postsResponse['data']['posts']) ? $postsResponse['data']['posts'] : [];
$nextCursor = isset($postsResponse['data']['cursor']) ? $postsResponse['data']['cursor'] : null;
?>
<script src="../assets/js/home.js"></script>
<!-- <div class="header">
    <div class="logo">My Community</div>
    <div class="search-bar">
        <input type="text" placeholder="Search...">
    </div>
</div> -->
<!-- hidden div to store base URL -->
<div class="hero">
    <h1>Chào mừng bạn đến với GoReview</h1>
    <p>Kết nối, chia sẻ kinh nghiệm du lịch cùng Tugo</p>
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

<!-- Filter Section -->
<div class="filter-container">
    <a href="?type=all" class="<?php echo $type == 'all' ? 'active' : ''; ?>">Tất cả</a>
    <a href="?type=review" class="<?php echo $type == 'review' ? 'active' : ''; ?>">Đánh giá tour</a>
    <a href="?type=general" class="<?php echo $type == 'general' ? 'active' : ''; ?>">Giới thiệu tour</a>
    <!-- <a href="?type=tour" class="<?php echo $type == 'tour' ? 'active' : ''; ?>">Tour</a>
    <a href="?type=photo" class="<?php echo $type == 'photo' ? 'active' : ''; ?>">Photo</a> -->
</div>

<div class="feed" id="feed">
    <?php 
    foreach ($posts as $post): 
        $post['time_ago'] = get_time_ago($post['created_at']);
        if($post['type'] == 'review'){ 
            include '../includes/post-review.php';
        }else if($post['type'] == 'general'){
            include '../includes/post-general.php';
        }
    endforeach; 
    ?>
</div>

<!-- Load More Button -->
<?php if ($nextCursor): ?>
    <div class="load-more-container" id="load-more-container">
        <button id="load-more" onclick="loadMore('<?php echo $nextCursor; ?>', '<?php echo $type; ?>')">Xem thêm</button>
    </div>
<?php endif; ?>

<div class="spacer"></div>

<!-- Include thêm các phần như Statistics và Feed -->

<?php include '../includes/navbar.php'; ?>
<?php include '../includes/footer.php'; ?>