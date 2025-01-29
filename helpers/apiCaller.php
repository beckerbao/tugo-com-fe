<?php
class APICaller {
    private static $baseUrl;// = 'http://host.docker.internal:9090/api/v1'; // Thay bằng URL API chính thức của bạn
    // private static $baseUrl = 'https://edd2-14-160-150-11.ngrok-free.app/api/v1';

    // Load base URL from .env file
    public static function init() {
        if (file_exists(__DIR__ . '/../.env')) {
            $env = parse_ini_file(__DIR__ . '/../.env');
            self::$baseUrl = $env['BASE_URL'] ?? '';
        } else {
            throw new Exception(".env file not found.");
        }
    }

    // Hàm GET
    public static function get($endpoint, $params = []) {
        $url = self::$baseUrl . $endpoint . '?' . http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            return ['error' => 'Failed to connect: ' . $error];
        }

        return json_decode($response, true);
    }

    // Hàm GET với headers tùy chỉnh
    public static function getWithHeaders($endpoint, $params = [], $headers = []) {
        $url = self::$baseUrl . $endpoint . '?' . http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        // Thêm headers vào request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            return ['error' => 'Failed to connect: ' . $error];
        }

        return json_decode($response, true);
    }

    // Hàm POST
    public static function post($endpoint, $data, $headers = []) {
        $url = self::$baseUrl . $endpoint;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge(['Content-Type: application/json'], $headers));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);        

        if ($response === false) {
            return ['error' => 'Failed to connect: ' . $error];
        }

        return json_decode($response, true);
    }

    // Hàm POST
    public static function postMultipart($endpoint, $data, $headers = [] ) {
        $url = self::$baseUrl . $endpoint;
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);     
        
        // Xử lý dữ liệu để đảm bảo đúng định dạng
        $processedData = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $index => $item) {
                    $processedData[$key . '['.$index.']'] = $item;                    
                }
            } else {
                $processedData[$key] = $value;
            }
        }

        // Đảm bảo headers bao gồm Content-Type nếu chưa được thêm
        if (!in_array('Content-Type: multipart/form-data', $headers)) {
            $headers[] = 'Content-Type: multipart/form-data';
        }
        // $processedData = $data;
        // print_r($processedData);

        // Đảm bảo Content-Type được thiết lập tự động bởi cURL
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $processedData); // Gửi dữ liệu dạng multipart/form-data
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        if ($response === false) {
            return [
                'error' => 'Failed to connect: ' . $error,
                'http_code' => $httpCode
            ];
        }

        // die;
    
        return [
            'data' => json_decode($response, true),
            'http_code' => $httpCode
        ];
    }
    

    // Hàm PUT (nếu cần)
    public static function put($endpoint, $data, $headers = []) {
        $url = self::$baseUrl . $endpoint;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge(['Content-Type: application/json'], $headers));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            return ['error' => 'Failed to connect: ' . $error];
        }

        return json_decode($response, true);
    }

    // Hàm DELETE (nếu cần)
    public static function delete($endpoint, $params = [], $headers = []) {
        $url = self::$baseUrl . $endpoint . '?' . http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            return ['error' => 'Failed to connect: ' . $error];
        }

        return json_decode($response, true);
    }
}
?>
