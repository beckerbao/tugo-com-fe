<?php
    // Chuyển đổi string JSON sang mảng và hiển thị booking_id nếu tồn tại
    $decodedContent = json_decode($post['raw_content'], true);

    $rating_display = "";
    $rating_number = 10;
    if ($decodedContent && isset($decodedContent['rating'])) {
        $rating_display = displayRatingStars($decodedContent['rating']);
        $rating_number = $decodedContent['rating'];
    }

    $review = "";
    if ($decodedContent && isset($decodedContent['review'])) {
        $review = $decodedContent['review'];
    }    
?>
<div class="post">
    <div class="user">
        <img src="<?php echo hasHttpOrHttps($post['user']['profile_image'])?$post['user']['profile_image']:(get_image_domain() . $post['user']['profile_image']) ?>" alt="User Profile">
        <div class="name"><?php echo $post['user']['name']; ?></div>        
    </div>
    <div class="meta">
        <i>🆔</i> <span>Mã booking: 
            <?php                 
                if ($decodedContent && isset($decodedContent['booking_id'])) {
                    echo htmlspecialchars($decodedContent['booking_id']);
                }
            ?>
        </span>
    </div>
    <?php
    //if tour_name is not empty
    if($post['tour_name'] != null){
    ?>
    <div class="meta">
        <i>🗺️</i> <span>Tên tour: <?php echo $post['tour_name'];?></span> <span>|</span> <span>Ngày đánh giá: 
            <?php
                //convert from "2025-02-06T00:00:00+07:00" to date format dd/mm/yyyy 
                $date = date('d/m/Y', strtotime($post['created_at']));                
                echo  $date; 
            ?></span>
    </div>
    <?php
    }
    ?>
    <?php
    //if tour_name is not empty
    if($post['guide_name'] != null){
    ?>
    <div class="meta">
        <i>🧑‍🏫</i> <span>Nhân viên Sale: <?php echo $post['guide_name']; ?></span>
    </div>
    <div class="meta">
        <i>🌟</i> <span>Rating: <?php echo $rating_display; ?></span> | (<?php echo $rating_number . "/10"; ?>)
    </div>
    <div class="content-wrapper">                        
        <div class="content" id="content-<?php echo $post['id']; ?>">
           <i>📝</i> Nội dung đánh giá: <?php echo $review; ?>            
        </div>        
        <a href="javascript:void(0);" class="toggle-content" onclick="toggleContent(<?php echo $post['id']; ?>)" id="toggle-<?php echo $post['id']; ?>" style="display: none;">Show More</a>
    </div> 
    <?php 
    }
    ?>
    <?php 
    $post['images'] = isset($post['images']) ? $post['images'] : [];
    foreach ($post['images'] as $image): 
        $image = hasHttpOrHttps($image)?$image:(get_image_domain() . $image);                   
    ?>
    <img class="image" src="<?php echo $image?>" alt="Tour Image">
    <?php endforeach; ?>       
    <?php include '../includes/post-common.php'; ?>
    <?php include '../includes/post-admin-reply.php'; ?>
    <!-- <div class="actions">
        <button>Like</button>
        <button>Comment</button>
        <button>Share</button>
    </div> -->
</div>
