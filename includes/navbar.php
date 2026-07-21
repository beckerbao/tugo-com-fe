<div class="bottom-nav">
    <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
    <a href="home.php" class="<?php echo $current_page === 'home.php' ? 'active' : ''; ?>">
        <i>🏠</i>
        Trang chủ
    </a>
    <a href="profile.php" class="<?php echo $current_page === 'profile.php' ? 'active' : ''; ?>">
        <i>📄</i>
        Tài khoản
    </a>
    <a href="flashsale_home.php" class="<?php echo in_array($current_page, ['flashsale_home.php', 'flashsale_detail.php'], true) ? 'active' : ''; ?>">
        <i>🔥</i>
        Tour khuyến mãi
    </a>
    <a href="review_statistics.php" class="<?php echo $current_page === 'review_statistics.php' ? 'active' : ''; ?>">
        <i>📊</i>
        Thống kê
    </a>
</div>
