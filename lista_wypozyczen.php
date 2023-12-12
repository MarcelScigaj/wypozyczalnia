<?php
require_once "admin.php";
require_once "wypozyczenie.php"; 
require_once "user.php"; 
date_default_timezone_set('Europe/Warsaw'); 

session_start();

if (!isset($_SESSION['user']) || $_SESSION['userRole'] != 1) {
    header("Location: login.php");
    exit();
}

$admin = new Admin();
$wypozyczenie = new Wypozyczenie();
$user = new User();

if (isset($_POST['zwrocFilm'])) {
    $id_wypozyczenia = $_POST['id_wypozyczenia'];
    $wynik = $wypozyczenie->zwrocFilm($id_wypozyczenia);
}

if (isset($_POST['usunWypozyczenie'])) {
    $id_wypozyczenia = $_POST['id_wypozyczenia'];
    $wynik = $wypozyczenie->usunWypozyczenie($id_wypozyczenia);
}


$szukaj = isset($_GET['szukaj']) ? $_GET['szukaj'] : '';
$sortuj = isset($_GET['sortuj']) ? $_GET['sortuj'] : '';

$wypozyczenia = $wypozyczenie->wyswietlWszystkieWypozyczenia($szukaj, $sortuj);


?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Lista Wypożyczeń</title>
    <link rel="stylesheet" href="style_lista_wypozyczen.css">
</head>
<body>
<style>
input[name="zwrocFilm"]:hover {
    background-color: #ffcc00; 
}
input[name="usunWypozyczenie"] {
    background-color: #f44336; 
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

input[name="usunWypozyczenie"]:hover {
    background-color: #d32f2f; 
}
</style>

<form action="lista_wypozyczen.php" method="get">
    <input type="text" name="szukaj" placeholder="Szukaj...">
    <select name="sortuj">
        <option value="data_wypozyczenia">Data wypożyczenia</option>
        <option value="nazwisko">Dane klienta</option>
        <option value="tytul_filmu">Tytuł filmu</option>
    </select>
    <input type="submit" value="Szukaj/Sortuj">
</form>


    <h1>Lista Wypożyczeń</h1>
    <a href="index.php" class="strona-glowna-button">Strona Główna</a>
    <table>
        <thead>
            <tr>
                <th>Tytuł Filmu</th>
                <th>Data Wypożyczenia</th>
                <th>Data Planowanego Zwrotu</th>
                <th>Data Zwrotu</th>
                <th>Klient</th>
                <th>Operacje</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($wypozyczenia as $wypozyczenie): ?>
                <?php
                $id_klienta = isset($wypozyczenie['id_klienta']) ? $wypozyczenie['id_klienta'] : null;
                $id_filmu = isset($wypozyczenie['id_filmu']) ? $wypozyczenie['id_filmu'] : null;
                $data_wypozyczenia = isset($wypozyczenie['data_wypozyczenia']) ? $wypozyczenie['data_wypozyczenia']->toDateTime()->format('Y-m-d H:i:s') : null;
                $data_planowanego_zwrotu = isset($wypozyczenie['data_planowanego_zwrotu']) ? $wypozyczenie['data_planowanego_zwrotu']->toDateTime()->format('Y-m-d H:i:s') : null;
                $data_zwrotu = isset($wypozyczenie['data_zwrotu']) ? $wypozyczenie['data_zwrotu']->toDateTime()->format('Y-m-d H:i:s') : 'Nie zwrócono';
                
                if ($id_klienta && $id_filmu && $data_wypozyczenia && $data_planowanego_zwrotu) {
                    $klient = $user->pobierzKlienta($id_klienta);
                    
                    if ($klient) {
                        $tytul_filmu = $wypozyczenie['tytul_filmu'];
                        $imie_nazwisko = htmlspecialchars($klient['imie'] . ' ' . $klient['nazwisko']);
                ?>
                        <tr>
                            <td><?php echo $tytul_filmu; ?></td>
                            <td><?php echo $data_wypozyczenia; ?></td>
                            <td><?php echo $data_planowanego_zwrotu; ?></td>
                            <td><?php echo $data_zwrotu; ?></td>
                            <td><?php echo $imie_nazwisko; ?></td>
                            <td>
                            <form method="post">
                            <input type="hidden" name="id_wypozyczenia" value="<?php echo $wypozyczenie['_id']; ?>">
                                <?php if (empty($wypozyczenie['data_zwrotu'])): ?>
                                    <input type="submit" name="zwrocFilm" value="Zwróć">
                                <?php else: ?>
                                    <input type="submit" name="usunWypozyczenie" value="Usuń">
                                <?php endif; ?>
                         </form>
                            </td>
                        </tr>
                <?php
                    }
                } 
                ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
