<?php
session_start();
require_once 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

    try {
        $stmt = $pdo->query($sql);
        $user = $stmt->fetch();

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit;
        } else {
            $error = "ユーザー名かパスワードが違います";
        }
    } catch (PDOException $e) {
        $error = "SQLエラー: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン - SQL練習アプリ</title>
</head>
<body>
    <h2>ログイン画面</h2>
    <?php if ($error): ?> <p style="color:red;"><?php echo $error; ?></p> <?php endif; ?>
    
    <form method="POST">
        <input type="text" name="username" placeholder="ユーザー名"><br>
        <input type="password" name="password" placeholder="パスワード"><br>
        <button type="submit">ログイン</button>
    </form>

    <div style="margin-top:20px; padding:10px; border:1px solid #ccc; background:#eee;">
        <strong>💡 ヒント（攻撃デモ用）:</strong><br>
        ユーザー名に <code>' OR '1'='1' -- </code> と入れてみてね。
    </div>
</body>
</html>