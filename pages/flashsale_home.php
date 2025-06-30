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

$campaign_id   = $_GET['campaign_id'] ?? '3';
$force_refresh = isset($_GET['force_refresh']) && $_GET['force_refresh'] === 'true' ? 'true' : 'false';
$response = APICaller::get(
    '/flashsale/homepage',
    ['campaign_id' => $campaign_id, 'force_refresh' => $force_refresh]
);

$data = $response['data'] ?? [];
$campaign_start_date = $data['campaign_info']['start_time'] ?? '';
$campaign_end_date = $data['campaign_info']['end_time'] ?? '';
$collections = $data['collections'] ?? [];

//convert start date to gmt+7
$campaign_start_date = date('Y-m-d H:i:s', strtotime($campaign_start_date) + 7 * 3600);
//convert end date to gmt+7
$campaign_end_date = date('Y-m-d H:i:s', strtotime($campaign_end_date) + 7 * 3600);
//if start date <= now then the campaign has started
$campaign_start = true;
if (strtotime($campaign_start_date) > time() || strtotime($campaign_end_date) < time()) {
  $campaign_start = false;
}

$campaign_end_date = strtotime($campaign_end_date);
//calculate remaining day, hour, minute, second from campaign end date to current date
$remainingDay = floor(($campaign_end_date - time()) / (60 * 60 * 24));
$remainingHour = floor((($campaign_end_date - time()) % (60 * 60 * 24)) / (60 * 60));
$remainingMinute = floor((($campaign_end_date - time()) % (60 * 60)) / 60);
$remainingSecond = floor(($campaign_end_date - time()) % 60);

//remaining text, if remaining day > 0 then remaining day, else remaining hour. if remaining hour > 0 then remaining hour, else remaining minute. if remaining minute > 0 then remaining minute, else remaining second
if ($remainingDay > 0) {
  $remainingText = $remainingDay . ' ngày';
} elseif ($remainingHour > 0) {
  $remainingText = $remainingHour . ' giờ';
} elseif ($remainingMinute > 0) {
  $remainingText = $remainingMinute . ' phút';
} else {
  $remainingText = $remainingSecond . ' giây';
}
$remainingText = sprintf('Chỉ còn %02d ngày %02d:%02d:%02d là kết thúc', $remainingDay, $remainingHour, $remainingMinute, $remainingSecond);

$campaign_start_date = strtotime($campaign_start_date);
//calculate comming day, hour, minute, second from campaign start date to current date
$commingDay = floor(($campaign_start_date - time()) / (60 * 60 * 24));
$commingHour = floor((($campaign_start_date - time()) % (60 * 60 * 24)) / (60 * 60));
$commingMinute = floor((($campaign_start_date - time()) % (60 * 60)) / 60);
$commingSecond = floor(($campaign_start_date - time()) % 60);

// $commingText = sprintf('Chương trình sẽ bắt đầu sau %02d ngày %02d:%02d:%02d', $commingDay, $commingHour, $commingMinute, $commingSecond);
$commingText = "Chương trình sẽ bắt đầu vào ngày " . date('d-m-Y', $campaign_start_date) . ", và kết thúc vào ngày " . date('d-m-Y', $campaign_end_date);

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

//function get id by name
function getIdCodeByName($name) {
    $map = [
        'Tour Đài Loan'   => 'tour_dai_loan',
        'Tour Hàn Quốc'   => 'tour_han_quoc',
        'Tour Nhật Bản'   => 'tour_nhat_ban',
        'Tour Châu Á'    => 'tour_chau_a',
        'Tour Trung Quốc' => 'tour_trung_quoc',
        'Tour Châu Âu' => 'tour_chau_au',
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

  <!-- Open Graph Meta Tags -->
  <meta property="og:title" content="Flash 7.7. Mua Tour Giá Gốc">
  <meta property="og:description" content="Flash 7.7. Đặt tour với giá gốc, số lượng có hạn.">
  <meta property="og:image" content="https://review.tugo.com.vn/assets/images/flash55.png">
  <meta property="og:url" content="https://review.tugo.com.vn/pages/flashsale_home.php">
  <meta property="og:type" content="website">

  <!-- Twitter Card Tags (tùy chọn) -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Mua Tour Giá Gốc">
  <meta name="twitter:description" content="Đặt tour với giá gốc, số lượng có hạn.">
  <meta name="twitter:image" content="https://yourdomain.com/images/flashsale-5-5.jpg">

  <link rel="preconnect" href="https://fonts.googleapis.com/">
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
  <link href="../assets/css/flashsale-home.css?v4" rel="stylesheet">
  <link href="../assets/css/flashsale-menu.css?v1" rel="stylesheet">
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
    function scrollToDestination(id) {
      const el = document.getElementById(id);
      if (el) {
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    }
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
        <h1 class="text-3xl md:text-4xl font-bold text-white">Flash 7.7 Giảm giá sốc</h1>
        <p class="mt-2 text-lg text-white/90">Du lịch Mùa Thu không lo về giá</p>
        <div class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-full countdown">
          <?php
          if ($campaign_start) {
          ?>
            <i class="ri-time-line ri-lg mr-2"></i><span class="font-semibold"><?php echo $remainingText ?></span>
          <?php
          } else {
          ?>
            <!-- <i class="ri-time-line ri-lg mr-2"></i><span class="font-semibold"><?php echo $commingText ?></span> -->
          <?php
          }
          ?>
        </div>
      </div>
    </div>    
  </section>

  <!-- Collection Sections -->
  <?php foreach ($collections as $collection): ?>
    
  <section class="py-12 bg-white" id="<?php echo getIdCodeByName($collection['collection_name']) ?>">
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
