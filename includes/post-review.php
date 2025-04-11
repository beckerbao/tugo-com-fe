<div class="post">
    <div class="user">
        <img src="<?php echo hasHttpOrHttps($post['user']['profile_image'])?$post['user']['profile_image']:(get_image_domain() . $post['user']['profile_image']) ?>" alt="User Profile">
        <div class="name"><?php echo $post['user']['name']; ?></div>
    </div>
    <?php
    //if tour_name is not empty
    if($post['tour_name'] != null){
    ?>
    <div class="meta">
        <i>🗺️</i> <span>Tên tour: <?php echo $post['tour_name'];?></span> <span>|</span> <span>Ngày khởi hành: 
            <?php
                //convert from "2025-02-06T00:00:00+07:00" to date format dd/mm/yyyy 
                $date = date('d/m/Y', strtotime($post['start_date']));                
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
        <i>🧑‍🏫</i> <span>Hướng dẫn: <?php echo $post['guide_name']; ?></span>
    </div>
    <?php 
    }
    ?>
    <?php 
    $post['images'] = isset($post['images']) ? $post['images'] : [];
    foreach ($post['images'] as $image): 
        // $image = get_image_domain() . $image;                
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