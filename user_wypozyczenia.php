<?php
require_once "user.php";
require_once "wypozyczenie.php";
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = new User();
$userId = $_SESSION['userId'];
$wypozyczenie = new Wypozyczenie();
$wypozyczone_filmy = $wypozyczenie->wyswietlWypozyczoneFilmy($userId);


$id_klienta = $_SESSION['userId'];


$wypozyczone_filmy = $wypozyczenie->wyswietlWypozyczoneFilmy($id_klienta);

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Twoje Wypożyczenia</title>
    <link rel="stylesheet" href="style_wypozyczenia.css">
    <link rel="stylesheet" href="glowna.css">
</head>
<body>
<div class="header">
        <h1>Wypożyczalnia Filmów</h1>
        <?php
        
        if (isset($_SESSION['userName'])) {
            echo '<div class="welcome">Witaj, ' . htmlspecialchars($_SESSION['userName']) . '</div>';
        }
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
    <table style="width: 50%;">
        <thead>
            <tr>
                <th>Tytuł Filmu</th>
                <th>Data Wypożyczenia</th>
                <th>Data Planowanego Zwrotu</th>
                <th>Oddany</th>
            </tr>
        </thead>
        <tbody class="wypozyczenia-table">
        <?php foreach ($wypozyczone_filmy as $wypozyczenie): ?>
            <tr>
            <td><?php echo htmlspecialchars($wypozyczenie['tytul_filmu']); ?></td>
            <td><?php echo date_format(date_create($wypozyczenie['data_wypozyczenia']->toDateTime()->format('Y-m-d H:i:s')), 'Y-m-d H:i:s'); ?></td>
            <td><?php echo date_format(date_create($wypozyczenie['data_planowanego_zwrotu']->toDateTime()->format('Y-m-d H:i:s')), 'Y-m-d H:i:s'); ?></td>
            <td><?php echo isset($wypozyczenie['data_zwrotu']) ? 'tak' : 'nie'; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
