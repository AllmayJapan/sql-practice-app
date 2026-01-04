<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$user, $pass]);
        echo "<p>登録完了！ <a href='login.php'>ログイン</a></p>";
    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }
}
?>

<form method="POST">
    <h2>ユーザー登録</h2>
    <input type="text" name="username" placeholder="ユーザー名" required><br>
    <input type="password" name="password" placeholder="パスワード" required><br>
    <button type="submit">登録</button>
</form>