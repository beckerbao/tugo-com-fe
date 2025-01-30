<?php 
$page_title = "Homepage";
include '../includes/header.php'; 
include '../helpers/apiCaller.php';
include '../helpers/common.php';

APICaller::init();

// Gọi API để lấy dữ liệu
$statistics = APICaller::get('/statistics');
$posts = APICaller::get('/posts');

$statistics = isset($statistics['data']) ? $statistics['data'] : [];
$posts = isset($posts['data']['posts']) ? $posts['data']['posts'] : [];
?>
<script src="../assets/js/home.js"></script>
<div class="header">
    <div class="logo">My Community</div>
    <div class="search-bar">
        <input type="text" placeholder="Search...">
    </div>
</div>

<div class="hero">
    <h1>Welcome to Our Community!</h1>
    <p>Connect, Share, and Explore with Fellow Travelers</p>
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
        <div class="label">Members</div>
    </div>
    <div class="stat">
        <div class="value"><?php echo $statistics['posts'] ?? '0'; ?>+</div>
        <div class="label">Posts</div>
    </div>
    <div class="stat">
        <div class="value"><?php echo $statistics['comments'] ?? '0'; ?>+</div>
        <div class="label">Comments</div>
    </div>
</div>

<div class="feed">
    <?php foreach ($posts as $post): ?>  
        <?php if($post['type'] == 'tour'){ ?>    
        <div class="post">
            <div class="user">
            <img src="<?php echo hasHttpOrHttps($post['user']['profile_image'])?$post['user']['profile_image']:(get_image_domain() . $post['user']['profile_image']) ?>" alt="User Profile">
                <div class="name"><?php echo $post['user']['name']; ?></div>
            </div>
            <div class="meta">
                <i>🗺️</i> <span>Tour: <?php echo $post['tour_name'];?></span> <span>|</span> <span>Date: <?php echo $post['start_date']; ?></span>
            </div>
            <div class="meta">
                <i>🧑‍🏫</i> <span>Tour Guide: <?php echo $post['guide_name']; ?></span>
            </div>
            <?php 
            $post['images'] = isset($post['images']) ? $post['images'] : [];
            foreach ($post['images'] as $image): 
                $image = get_image_domain() . $image;                
            ?>
            <img class="image" src="<?php echo $image?>" alt="Tour Image">
            <?php endforeach; ?>                        
            <div class="content">
                <?php echo $post['content']; ?>
            </div>
            <div class="time">Posted 2 hours ago</div>
            <div class="likes"><?php echo $post['likes'];?> likes</div>
            <!-- <div class="actions">
                <button>Like</button>
                <button>Comment</button>
                <button>Share</button>
            </div> -->
        </div>
        <?php }; ?>
    <?php endforeach; ?>
    <div class="spacer"></div>
</div>

<!-- Include thêm các phần như Statistics và Feed -->

<?php include '../includes/navbar.php'; ?>
<?php include '../includes/footer.php'; ?>