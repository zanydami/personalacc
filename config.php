<?php
/* Подключение к базе данных */
$link = mysqli_connect("PersonalAcc", "NesterovDaniil", "Da789512346Da", "personal");

// Проверяем соединение
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
