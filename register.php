<?php
session_start();

require "user.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $telefon = $_POST['telefon'];
    $rola = 0; // Automatycznie ustawiamy rolę na 0 (zwykły użytkownik)
    $ilosc_wypozyczen = 0;

    if (strlen($telefon) != 9) {
        $error = "Numer telefonu musi mieć dokładnie 9 cyfr";
    } else {
        $user = new User();
        if ($user->isLoginEmailUnique($login, $email)) {
            if ($user->registerUser($login, $email, $password, $imie, $nazwisko, $telefon, $rola,$ilosc_wypozyczen)) {
                // Rejestracja udana, przekierowanie na stronę logowania
                header("Location: login.php");
                exit();
            } else {
                $error = "Błąd rejestracji";
            }
        } else {
            $error = "Login lub email już istnieje";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Rejestracja</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="header">
        <h1>Rejestracja</h1>
    </div>
    <div class="main">
        <?php if(isset($error)): ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="register.php" method="post">
            <div class="form-group">
                <label for="login">Login:</label>
                <input type="text" id="login" name="login" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Hasło:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="imie">Imię:</label>
                <input type="text" id="imie" name="imie" required>
            </div>
            <div class="form-group">
                <label for="nazwisko">Nazwisko:</label>
                <input type="text" id="nazwisko" name="nazwisko" required>
            </div>
            <div class="form-group">
                <label for="telefon">Telefon:</label>
                <input type="text" id="telefon" name="telefon" required>
            </div>
            <button type="submit" class="button">Zarejestruj się</button>
        </form>
    </div>
</body>
</html>
