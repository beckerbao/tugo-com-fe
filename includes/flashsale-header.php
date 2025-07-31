<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
            <a href="../pages/flashsale_home.php" class="flex-shrink-0 flex items-center">
                <img src="../assets/images/tugologo.png" alt="Tugo Logo" class="h-8">
            </a>          
            <nav class="desktop-menu">
              <a href="/pages/home.php">Đánh giá từ khách</a>
              <a href="/pages/flashsale_home.php">Tour khuyến mãi</a>
              <a href="/group-tour">Tour nhóm nhỏ</a>
            </nav>
        </div>
        

        <button class="menu-toggle" onclick="toggleMobileMenu()">
          <i class="ri-menu-line"></i>
        </button>                
      </div>
    </div>
    <?php
    //check current page is flashsale_home or not, if yes show the button below
    if (basename($_SERVER['PHP_SELF']) == 'flashsale_home.php') {

    ?>
    <div class="bg-white py-4 shadow-md sticky top-[calc(100vh-60px)] z-30">
      <div class="max-w-7xl mx-auto px-4 flex flex-wrap justify-center gap-4">
        <button onclick="scrollToDestination('tour_han_quoc')" class="px-4 py-2 rounded-full bg-primary text-white text-xs hover:bg-purple-800 transition">Hàn Quốc</button>
        <button onclick="scrollToDestination('tour_nhat_ban')" class="px-4 py-2 rounded-full bg-primary text-white text-xs hover:bg-purple-800 transition">Nhật Bản</button>
        <button onclick="scrollToDestination('tour_chau_au')" class="px-4 py-2 rounded-full bg-primary text-white text-xs hover:bg-purple-800 transition">Châu Âu</button>
        <button onclick="scrollToDestination('tour_uc')" class="px-4 py-2 rounded-full bg-primary text-white text-xs hover:bg-purple-800 transition">Châu Úc</button>
        <button onclick="scrollToDestination('tour_dai_loan')" class="px-4 py-2 rounded-full bg-primary text-white text-xs hover:bg-purple-800 transition">Đài Loan</button>
        <button onclick="scrollToDestination('tour_trung_quoc')" class="px-4 py-2 rounded-full bg-primary text-white text-xs hover:bg-purple-800 transition">Trung Quốc</button>
        <!-- Thêm các điểm đến khác nếu cần -->
      </div>
    </div>
    <?php
    }
    ?>
  </nav>


<!-- Menu toggle button (mobile only) -->
<!-- <button class="menu-toggle" onclick="toggleMobileMenu()">
  <i class="ri-menu-line"></i>
</button> -->

<!-- Slide menu for mobile -->
<div id="mobileMenu" class="mobile-slide-menu">
  <button class="close-btn" onclick="toggleMobileMenu()">
    <i class="ri-close-line"></i>
  </button>
  <nav class="mobile-menu-items">
    <a href="/">Trang chủ</a>
    <a href="/flashsale">Flash Sale</a>
    <a href="/group-tour">Tour nhóm nhỏ</a>
  </nav>
</div>

<script>
  function toggleMobileMenu() {
    document.getElementById('mobileMenu').classList.toggle('active');
  }
</script>
