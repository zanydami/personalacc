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
    <title>Редакторская страница</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="page-header">
    <h1>Редакторская страница</h1>
</div>
<div class="page-content">
    <p>
        Добро пожаловать, <?php echo htmlspecialchars($_SESSION["username"]); ?>. Вы вошли как редактор.
    </p>
    <p>
        <a href="logout.php" class="btn btn-danger">Выход из аккаунта</a>
    </p>
</div>
</body>
</html>


