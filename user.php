<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Приветственное окно</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="page-header">
    <h1>Привет, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Добро пожаловать!.</h1>
</div>
<div class="page-content">
    <p>
        <a href="password_reset.php" class="btn btn-warning">Забыли пароль?</a>
        <a href="logout.php" class="btn btn-danger">Выход из аккаунта</a>
    </p>
</div>
</body>
</html>

