<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Wypożyczalnia Filmów</title>
    <link rel="stylesheet" href="glowna.css">
</head>
<body>
    <div class="header">
        <h1>Wypożyczalnia Filmów</h1>
        <?php
        session_start();
        if (isset($_SESSION['userName'])) {
            echo '<div class="welcome">Witaj, ' . htmlspecialchars($_SESSION['userName']) . '</div>';
        }
        // Zmiana w kodzie by wypchac zmiany na glowna galaz
        ?>
    </div>
    
    <div class="navbar">
        <?php if (!isset($_SESSION['user'])): ?>
            <a href="index.php">Strona Główna</a>
            <a href="login.php">Logowanie</a>
            <a href="register.php">Rejestracja</a>
        <?php else: ?>
            <a href="index.php">Strona Główna</a>
            <a href="lista_filmow.php">Lista filmów</a>
            <?php if (isset($_SESSION['userRole']) && $_SESSION['userRole'] == 1): ?>
                <a href="lista_klientow.php">Lista klientów</a>
                <a href="lista_wypozyczen.php">Lista wypożyczeń</a>
            <?php else: ?>
                <a href="user_wypozyczenia.php">Moje wypożyczenia</a>
            <?php endif; ?>
            <a href="logout.php" style="float: right;">Wyloguj</a>
        <?php endif; ?>
    </div>
    <?php if (!isset($_SESSION['user'])): ?>
        <div class="main">
            <h2>Witamy w naszej wypożyczalni!</h2>
            <p>Zaloguj się, aby wypożyczyć film lub zarejestruj nowe konto.</p>
        </div>
    <?php endif; ?>
</body>
</html>
