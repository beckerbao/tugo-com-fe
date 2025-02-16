<div class="post">
    <div class="user">
        <img src="<?php echo hasHttpOrHttps($post['user']['profile_image'])?$post['user']['profile_image']:(get_image_domain() . $post['user']['profile_image']) ?>" alt="User Profile">
        <div class="name"><?php echo $post['user']['name']; ?></div>
    </div>
    <div class="meta">
        <i>🗺️</i> <span>Tiêu đề: <?php echo $post['tour_name'];?></span>
    </div>
    <?php 
    $post['images'] = isset($post['images']) ? $post['images'] : [];
    foreach ($post['images'] as $image): 
        //check if image doesn't content http or https then get image domain + image
        if(!hasHttpOrHttps($image)){
            $image = get_image_domain() . $image;
        }        
    ?>
    <img class="image" src="<?php echo $image?>" alt="Tour Image">
    <?php endforeach; ?>
    <div class="content-wrapper"> 
        <div class="content" id="content-<?php echo $post['id']; ?>">
            <?php echo $post['content']; ?>
        </div>
        <a href="javascript:void(0);" class="toggle-content" onclick="toggleContent(<?php echo $post['id']; ?>)" id="toggle-<?php echo $post['id']; ?>" style="display: none;">Show More</a>
    </div>
                            
    
    <?php include '../includes/post-common.php'; ?>    
    <!-- <div class="actions">
        <button>Like</button>
        <button>Comment</button>
        <button>Share</button>
    </div> -->
</div>