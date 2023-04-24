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
    <title>Административная панель</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="page-header">
    <h1>Административная панель</h1>
</div>
<div class="page-content">
    <p>Добро пожаловать, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</p>
    <p>Здесь вы можете управлять сайтом.</p>
    <ul>
        <li><a href="add_user.php">Добавить пользователя</a></li>
        <li><a href="delete_user.php">Удалить пользователя</a></li>
        <li><a href="view_logs.php">Просмотреть логи</a></li>
    </ul>
    <p><a href="logout.php" class="btn btn-danger">Выход из аккаунта</a></p>
</div>
</body>
</html>
