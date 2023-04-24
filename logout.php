<?php
// Начало сессии
session_start();

// Удаление всех переменных сессии
session_unset();

// Уничтожение сессии
session_destroy();

// Перенаправление на страницу входа
header("location: index.php");
exit;
?>