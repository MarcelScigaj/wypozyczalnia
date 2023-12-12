<?php
session_start();

require "user.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new User();
    $verificationResult = $user->verifyUser($email, $password);

    if ($verificationResult['status']) {
        $userDetails = $user->getUserDetails($email);
        $_SESSION['user'] = $email; 
        $_SESSION['userId'] = (string) $userDetails['_id'];
        $_SESSION['userName'] = $user->getUserDetails($email)['imie']; 
        $_SESSION['userRole'] = $user->getUserRole($email);

        header("Location: index.php");
        exit();
    } else if ($verificationResult['reason'] === "wrong_password") {
        $error = "Niepoprawne hasło";
    } else {
        $error = "Nieprawidłowy email lub hasło";
    }
}
?>



<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
    <link rel="stylesheet" href="glowna.css">
</head>
<body>
    <div class="header">
        <h1>Logowanie</h1>
    </div>
    <div class="main">
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="login">E-mail:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Hasło:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="button">Zaloguj się</button>
        </form>
    </div>
</body>
</html>
