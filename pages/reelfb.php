<?php
$video_ids = [
    "2031352557369378",
    "1637515117123705",
    "870858001785570",
    "460136580461100",
    "1618129922913236",
    "918880443292673",
    "3988009111437157",
    "1181981580079583",
    "1333740407628915",
    "995282602656195",
    "941570277952412",
    "643411598111793",
    "595626653403411",
    "373878089143492",
    "1251478492572719",
    "972378311614140"
];

function check_facebook_video_embed($video_url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $video_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64)");
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
// return $response;
    if ($http_code != 200) {
        // return "❌ Video bị lỗi HTTP: $http_code";
        return false;
    }

    // Kiểm tra nếu response chứa URL của Facebook Help (chỉ xuất hiện khi video bị chặn)
    if (strpos($response, "Không khả dụng") !== false) {
        // return "❌ Video bị chặn nhúng!";
        return false;
    }

    // return "✅ Video có thể nhúng!";
    return true;
}

$video_id = "2031352557369378"; // ID video cần kiểm tra
$iframe_url = "https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2Freel%2F$video_id%2F&show_text=true&width=267&t=0";

echo check_facebook_video_embed($iframe_url);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhúng 10 Video Reels Facebook bằng PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .video-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .video-box {
            width: 267px;
        }
    </style>
</head>
<body>

    <h2>10 Video Reels Nhúng từ Facebook</h2>

    <div class="video-container">
        <?php 
            foreach ($video_ids as $video_id) { 
                $iframe_url = "https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2Freel%2F$video_id%2F&show_text=true&width=267&t=0";

                //hàm này chỉ nên chạy khi add video mới vào, chạy lúc load sẽ rất lâu
                // if (check_facebook_video_embed($iframe_url)==false) {
                //     echo "Video $video_id bi loi";
                //     continue;
                // }
        ?>
            <div class="video-box">
                <iframe src="https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2Freel%2F<?php echo $video_id; ?>%2F&show_text=true&width=267&t=0" 
                    width="267" height="591" 
                    style="border:none;overflow:hidden" 
                    scrolling="no" frameborder="0" 
                    allowfullscreen="true" 
                    allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share">
                </iframe>
            </div>
        <?php } ?>
    </div>
</body>
</html>
