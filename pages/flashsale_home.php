<?php
include '../helpers/common.php';
include '../helpers/apiCaller.php';
function fetch_api($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    curl_close($ch);
    return json_decode($res, true);
}
APICaller::init();

$campaign_id   = $_GET['campaign_id'] ?? '1';
$force_refresh = isset($_GET['force_refresh']) && $_GET['force_refresh'] === 'true' ? 'true' : 'false';
$response = APICaller::get(
    '/flashsale/homepage',
    ['campaign_id' => $campaign_id, 'force_refresh' => $force_refresh]
);

$data = $response['data'] ?? [];
$collections = $data['collections'] ?? [];
// var_dump($data);

function getCountryFlag($name) {
    $map = [
        'Tour Đài Loan'   => '🇹🇼',
        'Tour Hàn Quốc'   => '🇰🇷',
        'Tour Nhật Bản'   => '🇯🇵',
        'Tour Châu Âu'    => '🇪🇺',
        'Tour Trung Quốc' => '🇨🇳',
    ];
    return $map[$name] ?? '';
}
?>
<!DOCTYPE html>
<!-- saved from url=(0046)http://localhost:8081/pages/flashsale_home.php -->
<html lang="vi">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Flash Sale Tour Giá Sốc | Tugo</title>
  <link rel="preconnect" href="https://fonts.googleapis.com/">
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
  <link href="../assets/css/flashsale-home.css?test" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com?version=3.4.16"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { primary: '#660066', secondary: '#2196F3' },
          borderRadius: { button: '8px' }
        }
      }
    };
  </script>
  <style type="text/css">@import url('https://fonts.googleapis.com/css2?family=Work+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap');</style>
  <?php include '../includes/tracking.php'; ?>
</head>
<body class="bg-gray-50">
  <!-- Navbar -->
  <?php include '../includes/flashsale-header.php'; ?>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
      <div class="w-full max-w-xl bg-white/10 backdrop-blur-sm p-6 rounded-lg border border-white/20">
        <h1 class="text-3xl md:text-4xl font-bold text-white">FLASH SALE TOUR GIÁ SỐC</h1>
        <p class="mt-2 text-lg text-white/90">Ưu đãi số lượng có hạn – chỉ áp dụng cho các ngày khởi hành sắp tới</p>
        <div class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-full countdown">
          <i class="ri-time-line ri-lg mr-2"></i><span class="font-semibold">Còn 2 ngày</span>
        </div>
      </div>
    </div>
  </section>

  <!-- Collection Sections -->
  <?php foreach ($collections as $collection): ?>
    
  <section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="collection-card overflow-hidden rounded-lg border border-gray-200 shadow-sm mb-12">
        <div class="relative h-48 md:h-64">
          <img src="<?= htmlspecialchars($collection['collection_image']) ?>" alt="" class="w-full h-full object-cover object-top">
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
          <div class="absolute bottom-0 left-0 p-6">
            <h2 class="text-2xl font-bold text-white flex items-center">
              <span class="mr-2"><?= getCountryFlag($collection['collection_name']) ?></span>
              <?= htmlspecialchars($collection['collection_name']) ?>
            </h2>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
          <?php foreach ($collection['tours'] as $tour): ?>
          <?php
            $tourName   = $tour['tour_name'];
            $departures = array_slice($tour['departures'], 0, 4);
          ?>
          <div class="tour-card bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
            <div class="relative h-48">
              <img src="<?php echo htmlspecialchars($tour['tour_image']) ?>" alt="" class="w-full h-full object-cover object-top">
            </div>
            <div class="p-4">
              <h3 class="font-bold text-lg text-gray-900"><?= htmlspecialchars($tourName) ?></h3>
              <div class="mt-3 flex flex-wrap gap-2">
                <?php foreach ($departures as $dep): ?>
                  <?php
                    $dt    = new DateTime($dep['date']);
                    $day   = $dt->format('d');
                    $month = $dt->format('m');
                    $price = number_format($dep['price']/1000000,1,',','.') . 'tr';
                  ?>
                  <div class="date-pill flex items-center bg-gray-100 hover:bg-gray-200 rounded-full px-3 py-1 text-sm">
                    <span class="font-medium"><?= $day ?>/<?= $month ?></span>
                    <span class="mx-1">-</span>
                    <span class="text-primary font-semibold"><?= $price ?></span>
                  </div>
                <?php endforeach; ?>
              </div>
              <a href="flashsale_detail.php?tour_id=<?= $tour['tour_id'] ?>&campaign_id=<?= urlencode($campaign_id) ?>" class="mt-4 w-full bg-primary text-white py-2 px-4 rounded-button flex items-center justify-center no-underline">
                <span>Xem chi tiết</span><i class="ri-arrow-right-line ri-lg ml-1"></i>
              </a>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
  <?php endforeach; ?> 

  <!-- Footer -->
  <?php
    include_once('../includes/flashsale-footer.php');
    ?>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const tourCards = document.querySelectorAll(".tour-card");
      tourCards.forEach(card => {
        card.addEventListener("mouseenter", () => card.classList.add("shadow-md"));
        card.addEventListener("mouseleave", () => card.classList.remove("shadow-md"));
      });
    });
  </script>
</body>
</html>
