<?php
declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('不正なアクセスです');
}

$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $email === '' || $password === '') {
    exit('未入力の項目があります');
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);



echo '登録が完了しました';
