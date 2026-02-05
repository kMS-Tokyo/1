<?php
declare(strict_types=1);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $email === '' || $password === '') {
        $error = '全ての項目を入力してください。';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'メールアドレスの形式が正しくありません。';
    } else {
        try {
            $pdo = new PDO(
                'mysql:host=localhost;dbname=test_db;charset=utf8mb4',
                'db_user',
                'db_pass',
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );

            $stmt = $pdo->prepare(
                'INSERT INTO users (username, email, password)
                 VALUES (:username, :email, :password)'
            );

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $hashedPassword,
            ]);

            $success = '登録が完了しました。';

        } catch (PDOException $e) {
            $error = '登録に失敗しました。（既に登録されている可能性があります）';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザー登録</title>
    <style>
        body { font-family: sans-serif; }
        .container { width: 400px; margin: 40px auto; }
        .error { color: red; }
        .success { color: green; }
        label { display: block; margin-top: 10px; }
        input { width: 100%; padding: 6px; }
        button { margin-top: 15px; padding: 8px; width: 100%; }
    </style>
</head>
<body>
<div class="container">
    <h2>ユーザー登録</h2>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="post">
        <label>
            ユーザー名
            <input type="text" name="username" required>
        </label>

        <label>
            メールアドレス
            <input type="email" name="email" required>
        </label>

        <label>
            パスワード
            <input type="password" name="password" required>
        </label>

        <button type="submit">登録</button>
    </form>
</div>
</body>
</html>
