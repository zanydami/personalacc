<?php
require_once "config.php";

$email = "";
$email_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["email"]))){
        $email_err = "Введите e-mail.";
    } else{
        $email = trim($_POST["email"]);
    }

    if(empty($email_err)){
        $sql = "SELECT id FROM users WHERE email = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            $param_email = $email;

            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $token = bin2hex(random_bytes(32));
                    $sql = "INSERT INTO password_resets (email, token) VALUES (?, ?)";

                    if($stmt = mysqli_prepare($link, $sql)){
                        mysqli_stmt_bind_param($stmt, "ss", $param_email, $param_token);

                        $param_email = $email;
                        $param_token = password_hash($token, PASSWORD_DEFAULT);

                        if(mysqli_stmt_execute($stmt)){
                            $reset_link = "http://example.com/reset_password.php?token=" . $token;
                            $to = $email;
                            $subject = "Password Reset";
                            $message = "To reset your password, please click this link: " . $reset_link;
                            $headers = "From: example@example.com" . "\r\n" .
                                "Reply-To: example@example.com" . "\r\n" .
                                "X-Mailer: PHP/" . phpversion();

                            if(mail($to, $subject, $message, $headers)){
                                header("location: password_reset.php?status=success");
                            } else{
                                header("location: password_reset.php?status=error");
                            }
                        } else{
                            echo "Oops! Something went wrong. Please try again later.";
                        }
                    }
                    mysqli_stmt_close($stmt);
                } else{
                    $email_err = "No account found with that email address.";
                }
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
<html>
<head>
    <meta charset="UTF-8">
    <title>Сброс пароля</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrapper">
    <h2>Сброс пароля</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
            <span class="help-block"><?php echo $email_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Подтвердить">
        </div>
    </form>
    <p>Вспомнили пароль? <a href="index.php">Вход</a></p>
</div>
</body>
</html>