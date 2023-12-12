<?php
require_once "admin.php";
session_start();

if (!isset($_SESSION['user']) || $_SESSION['userRole'] != 1) {
    header("Location: login.php");
    exit();
}

$admin = new Admin();
$id_klienta = $_GET['id'] ?? null;
$klient = null;

if ($id_klienta) {
    $klient = $admin->pobierzKlienta($id_klienta);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $noweDane = [
        'imie' => $_POST['imie'],
        'nazwisko' => $_POST['nazwisko'],
        'email' => $_POST['email'],
        'telefon' => $_POST['telefon'],
        // Inne pola, jeśli są potrzebne
    ];

    $admin->modyfikujDaneKlienta($id_klienta, $noweDane);
    header("Location: lista_klientow.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Modyfikuj Klienta</title>
    <link rel="stylesheet" href="style_modyfikuj_klienta.css">

</head>
<body>
    <h1>Modyfikuj Klienta</h1>
    <?php if ($klient): ?>
        <form method="post">
            Imię: <input type="text" name="imie" value="<?php echo htmlspecialchars($klient['imie']); ?>"><br>
            Nazwisko: <input type="text" name="nazwisko" value="<?php echo htmlspecialchars($klient['nazwisko']); ?>"><br>
            Email: <input type="email" name="email" value="<?php echo htmlspecialchars($klient['email']); ?>"><br>
            Telefon: <input type="text" name="telefon" value="<?php echo htmlspecialchars($klient['telefon']); ?>"><br>
            <!-- Inne pola, jeśli są potrzebne -->
            <input type="submit" value="Zapisz zmiany">
        </form>
    <?php else: ?>
        <p>Klient nie został znaleziony.</p>
    <?php endif; ?>
</body>
</html>
