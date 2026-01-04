<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'ログインが必要だよ']);
    exit;
}

require_once 'db.php';
require_once 'db_info.php';

$sql = $_POST['sql'] ?? '';

if (empty($sql)) {
    echo json_encode(['error' => 'SQLが空です。']);
    exit;
}

try {
    $stmt = $pdo->query($sql);

    if ($stmt->columnCount() > 0) {
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $count = $stmt->rowCount();
        $result = [["message" => "クエリ成功。影響を受けた行数: $count"]];
    }

    $db_structure_json = getDatabaseStructureJSON($pdo, 'sql_practice');
    $db_structure = json_decode($db_structure_json, true);

    echo json_encode([
        'result' => $result,
        'db_structure' => $db_structure
    ]);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}