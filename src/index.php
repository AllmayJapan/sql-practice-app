<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';
require_once 'db_info.php';

// 初期表示用のDB構造を取得
$initial_json = getDatabaseStructureJSON($pdo, 'sql-practice');
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>SQL Practice App</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <span>SQL Practice Tool</span>
        <a href="logout.php">ログアウト</a>
    </header>

    <div class="container">
        <div class="top-row">
            <div class="pane sql-input">
                <h3>SQL Editor</h3>
                <div id="table-shortcuts">
                    <?php
                    $db_data = json_decode($initial_json, true);
                    if ($db_data && !isset($db_data['error'])) {
                        foreach (array_keys($db_data) as $tableName) {
                            $safeTable = htmlspecialchars($tableName);
                            echo "<button class='table-btn' onclick=\"insertSQL('{$safeTable}')\">{$safeTable}</button> ";
                        }
                    }
                    ?>
                </div>
                <div id="sql-editor-container"></div>
                <script src="node_modules/monaco-editor/min/vs/loader.js"></script>
                <button id="run-btn">実行 (Ctrl+Enter)</button>
            </div>
            
            <div class="pane db-info">
                <h3>Database Structure (JSON)</h3>
                <pre id="db-structure-json"><?php echo htmlspecialchars($initial_json); ?></pre>
            </div>
        </div>

        <div class="pane sql-result">
            <h3>Execution Result</h3>
            <div id="result-display">
                <p>ここに結果が表示されるよ</p>
            </div>
        </div>
    </div>

    <script src="app.js"></script>
</body>
</html>