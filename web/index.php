<?php
// Sử dụng biến môi trường để kết nối với database trong Docker
$host =  'db';
$dbname = getenv('DB_NAME') ?: 'shop';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: 'password';

// Kết nối tới cơ sở dữ liệu
try {
    echo 1;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    echo 2;
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo 3;
    // Lấy danh sách sản phẩm từ database
    $stmt = $pdo->query("SELECT id, name FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Không thể kết nối đến cơ sở dữ liệu: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách sản phẩm</title>
</head>
<body>
    <h1>Danh sách sản phẩm</h1>
    <ul>
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <li><a href="product.php?id=<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></a></li>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Không có sản phẩm nào trong cơ sở dữ liệu.</p>
        <?php endif; ?>
    </ul>
</body>
</html>
