<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
            <a href="../pages/flashsale_home.php" class="flex-shrink-0 flex items-center">
                <img src="../assets/images/tugologo.png" alt="Tugo Logo" class="h-8">
            </a>          
        </div>        
        <!-- <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-4">
          <button class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none"><i class="ri-search-line ri-lg"></i></button>
          <button class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none"><i class="ri-heart-line ri-lg"></i></button>
          <button class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none"><i class="ri-user-line ri-lg"></i></button>
        </div> -->
        <div class="-mr-2 flex items-center sm:hidden">
          <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none"><i class="ri-menu-line ri-lg"></i></button>
        </div>
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
        <button onclick="scrollToDestination('tour_dai_loan')" class="px-4 py-2 rounded-full bg-primary text-white text-xs hover:bg-purple-800 transition">Đài Loan</button>
        <button onclick="scrollToDestination('tour_trung_quoc')" class="px-4 py-2 rounded-full bg-primary text-white text-xs hover:bg-purple-800 transition">Trung Quốc</button>
        <!-- Thêm các điểm đến khác nếu cần -->
      </div>
    </div>
    <?php
    }
    ?>
  </nav>