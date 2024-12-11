<?php
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'shop';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: 'password';

try {

    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Không thể kết nối đến cơ sở dữ liệu: " . $e->getMessage();
    exit;
}

$product = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT name, description, price FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}


function send_request($url, $headers = []) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    return $error ? "Error: $error" : $response;
}


if (isset($_SERVER['HTTP_REFERER'])) {
    $referer = $_SERVER['HTTP_REFERER'];
    $shellshock_servers = explode(",", $referer);  

    foreach ($shellshock_servers as $server) {
        $server = trim($server);  
        if (filter_var($server, FILTER_VALIDATE_URL)) {
            $userAgentPayload = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
            
            $headers = [
                "User-Agent: $userAgentPayload"
            ];
            $response = send_request($server, $headers);

            file_put_contents(
                "ssrf_log.txt",
                "Requested URL (Referer): $server\nUser-Agent: $userAgentPayload\nResponse: " . ($response ?: "No response or connection failed") . "\n\n",
                FILE_APPEND
            );
        } else {
            file_put_contents(
                "ssrf_log.txt",
                "Invalid Referer URL: $server\n\n",
                FILE_APPEND
            );
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết sản phẩm</title>
</head>
<body>
    <h1>Chi tiết sản phẩm</h1>

    <?php if ($product): ?>
        <p><strong>Tên sản phẩm:</strong> <?= htmlspecialchars($product['name']) ?></p>
        <p><strong>Mô tả:</strong> <?= htmlspecialchars($product['description']) ?></p>
        <p><strong>Giá:</strong> <?= htmlspecialchars($product['price']) ?></p>
    <?php else: ?>
        <p>Không tìm thấy sản phẩm.</p>
    <?php endif; ?>

    <p><em>Kiểm tra file <strong>ssrf_log.txt</strong> để xem log SSRF và User-Agent payload.</em></p>
</body>
</html>
