<?php
require_once "config.php";
session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
	header("location: user.php");
	exit;
}

require_once "config.php";

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

	if(empty(trim($_POST["username"]))){
		$username_err = "Please enter a username.";
	} else{
		$sql = "SELECT id FROM users WHERE username = ?";

		if($stmt = mysqli_prepare($link, $sql)){
			mysqli_stmt_bind_param($stmt, "s", $param_username);

			$param_username = trim($_POST["username"]);

			if(mysqli_stmt_execute($stmt)){
				mysqli_stmt_store_result($stmt);

				if(mysqli_stmt_num_rows($stmt) == 1){
					$username_err = "Такой пользователь уже существует.";
				} else{
					$username = trim($_POST["username"]);
				}
			} else{
				echo "Oops! Something went wrong. Please try again later.";
			}
		}

		mysqli_stmt_close($stmt);
	}

	if(empty(trim($_POST["password"]))){
		$password_err = "Введите пароль.";
	} elseif(strlen(trim($_POST["password"])) < 6){
		$password_err = "Пароль должен содержать не менее 6 символов.";
	} else{
		$password = trim($_POST["password"]);
	}

	if(empty(trim($_POST["confirm_password"]))){
		$confirm_password_err = "Пожалуйста повторите пароль.";
	} else{
		$confirm_password = trim($_POST["confirm_password"]);
		if(empty($password_err) && ($password != $confirm_password)){
			$confirm_password_err = "Пароли не совпадают.";
		}
	}

	if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

		$sql = "INSERT INTO users (username, password) VALUES (?, ?)";

		if($stmt = mysqli_prepare($link, $sql)){
			mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

			$param_username = $username;
			$param_password = password_hash($password, PASSWORD_DEFAULT);

			if(mysqli_stmt_execute($stmt)){
				header("location: index.php");
			} else{
				echo "Oops! Something went wrong. Please try again later.";
			}
		}

		mysqli_stmt_close($stmt);
	}

	mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Регистрация</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrapper">
	<h2>Регистрация</h2>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		<div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
			<label>Имя пользователя</label>
			<input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
			<span class="help-block"><?php echo $username_err; ?></span>
		</div>
		<div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
			<label>Пароль</label>
			<input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
			<span class="help-block"><?php echo $password_err; ?></span>
		</div>
		<div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
			<label>Подтверждение пароля</label>
			<input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
			<span class="help-block"><?php echo $confirm_password_err; ?></span>
		</div>
		<div class="form-group">
			<input type="submit" class="btn btn-primary" value="Подтвердить">
			<input type="reset" class="btn btn-primary" value="Сбросить данные">
		</div>
	</form>
	<p>Уже имеете аккаунт? <a href="index.php">Вход</a>.</p>
</div>
</body>
</html>
