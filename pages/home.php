<?php 
session_start();
$page_title = "Homepage";
include '../helpers/apiCaller.php';
include '../helpers/common.php';
include '../includes/header.php'; 

$type = isset($_GET['type']) ? $_GET['type'] : 'all';

// Gọi API để lấy dữ liệu
APICaller::init();
$statistics = APICaller::get('/statistics');
$postsResponse = APICaller::get('/posts',array("page_size"=>20,"page"=>1,'type'=>$type=='all' ? '' : $type));

$statistics = isset($statistics['data']) ? $statistics['data'] : [];
$posts = isset($postsResponse['data']['posts']) ? $postsResponse['data']['posts'] : [];
$nextCursor = isset($postsResponse['data']['cursor']) ? $postsResponse['data']['cursor'] : null;

function displayRatingStars($rating) {
    $maxRating = 10;
    // Dùng sao đầy (★) cho rating, sao rỗng (☆) cho phần còn lại
    $fullStar = '★';
    $emptyStar = '☆';
    $output = '';

    // Lặp từ 1 đến $maxRating, hiển thị sao đầy nếu chỉ số nhỏ hơn hoặc bằng rating
    for ($i = 1; $i <= $maxRating; $i++) {
        if ($i <= $rating) {
            $output .= '<span style="color:gold;">' . $fullStar . '</span>';
        } else {
            $output .= '<span style="color:#ccc;">' . $emptyStar . '</span>';
        }
    }
    return $output;
}
?>
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
        <button onclick="location.href='login2.php'">Join Now</button>
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
    <a href="?type=review_sale" class="<?php echo $type == 'review_sale' ? 'active' : ''; ?>">Đánh giá tư vấn</a>
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
        }else if($post['type'] == 'review_sale'){
            include '../includes/post-sale-review.php';
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
<script src="../assets/js/home.js"></script>
<!--
    cần 1 script check nếu query string biến notice=review_success thì sẽ alert "Đánh giá thành công"
-->
<?php
if (isset($_GET['notice']) && $_GET['notice'] === 'review_success') {
    echo "<script>
        alert('Đánh giá thành công!');
    </script>";
}
?>
<?php include '../includes/footer.php'; ?>