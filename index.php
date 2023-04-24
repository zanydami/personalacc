<?php
require_once "config.php";
session_start();

// проверка уровня доступа
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $role_id = $_SESSION["role_id"];
    switch ($role_id) {
        case "1":
            header("location: admin.php");
            exit;
            break;
        case "2":
            header("location:editor.php");
            break;
        default:
            header("location: user.php");
            exit;
    }
}

$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST"){

    if (empty(trim($_POST["username"]))) {
        $username_err = "Введите имя.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Введите пароль.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT id, username, password, role_id FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = $username;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $role_id);

                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            session_start();

                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["role_id"] = $role_id;

                            switch ($role_id) {
                                case "1":
                                    header("location: admin.php");
                                    break;
                                case "2":
                                    header("location: editor.php");
                                    break;
                                default:
                                    header("location: user.php");
                            }

                        } else {
                            $password_err = "Пароль неверный.";
                        }
                    }
                } else {
                    $username_err = "Такого пользователя не существует.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrapper">
    <h2>Вход</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
            <label>Логин</label>
            <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
            <span class="help-block"><?php echo $username_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Пароль</label>
            <input type="password" name="password" class="form-control">
            <span class="help-block"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Войти">
        </div>
    </form>
    <p>У вас нет аккаунта? <a href="register.php">Зарегистрироваться</a>.</p>
    <p>Забыли пароль? <a href="password_reset.php">Сбросить пароль</a>.</p>
</div>
</body>
</html>