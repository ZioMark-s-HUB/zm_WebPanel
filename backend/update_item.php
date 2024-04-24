<?php
require_once '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable('C:/Users/Administrator/Desktop/'); //directory where your .env is!
$dotenv->load();;

$dsn = 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'];
try {
    $pdo = new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $itemId = $_POST['itemId'];
        $label = $_POST['label'];
        $limit = $_POST['limit'];
        $usable = $_POST['usable'];
        $groupId = $_POST['groupId'];
        $desc = $_POST['desc'];
        $weight = $_POST['weight'];
        $can_remove = isset($_POST['can_remove']) ? 1 : 0;

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM items WHERE item = :itemId");
        $stmt->bindParam(':itemId', $itemId);
        $stmt->execute();
        $itemExists = $stmt->fetchColumn();

        if (!$itemExists) {
            http_response_code(400);
            echo json_encode(['message' => 'Item does not exist']);
            error_log('Item does not exist: ' . $itemId);
            exit;
        }

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM item_group WHERE id = :groupId");
        $stmt->bindParam(':groupId', $groupId);
        $stmt->execute();
        $groupExists = $stmt->fetchColumn();

        if (!$groupExists) {
            http_response_code(400);
            echo json_encode(['message' => 'Group does not exist']);
            error_log('Group does not exist: ' . $groupId);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE items SET label = :label, `limit` = :limit, usable = :usable, groupId = :groupId, `desc` = :desc, weight = :weight, can_remove = :can_remove WHERE item = :itemId");
        $stmt->bindParam(':itemId', $itemId);
        $stmt->bindParam(':label', $label);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':usable', $usable, PDO::PARAM_INT);
        $stmt->bindParam(':groupId', $groupId, PDO::PARAM_INT);
        $stmt->bindParam(':desc', $desc);
        $stmt->bindParam(':weight', $weight);
        $stmt->bindParam(':can_remove', $can_remove, PDO::PARAM_INT);


        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo json_encode(['message' => 'Item updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Failed to update item: No rows affected']);
            error_log('Failed to update item: No rows affected');
        }
    } else {
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed: Only POST requests are allowed']);
        error_log('Method Not Allowed: Only POST requests are allowed');
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
    error_log('Database error: ' . $e->getMessage());
}
?>
