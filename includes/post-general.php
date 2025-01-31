<div class="post">
    <div class="user">
        <img src="<?php echo hasHttpOrHttps($post['user']['profile_image'])?$post['user']['profile_image']:(get_image_domain() . $post['user']['profile_image']) ?>" alt="User Profile">
        <div class="name"><?php echo $post['user']['name']; ?></div>
    </div>
    <div class="meta">
        <i>🗺️</i> <span>Tour: <?php echo $post['tour_name'];?></span> <span>|</span>
    </div>
    <div class="content">
        <?php echo $post['content']; ?>
    </div>
    <?php 
    $post['images'] = isset($post['images']) ? $post['images'] : [];
    foreach ($post['images'] as $image): 
        $image = get_image_domain() . $image;                
    ?>
    <img class="image" src="<?php echo $image?>" alt="Tour Image">
    <?php endforeach; ?>                        
    
    <?php include '../includes/post-common.php'; ?>    
    <!-- <div class="actions">
        <button>Like</button>
        <button>Comment</button>
        <button>Share</button>
    </div> -->
</div>