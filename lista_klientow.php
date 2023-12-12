<?php
require_once "admin.php";

session_start();

// Sprawdzenie, czy użytkownik jest zalogowany i czy ma uprawnienia administratora
if (!isset($_SESSION['user']) || $_SESSION['userRole'] != 1) {
    header("Location: login.php");
    exit();
}

$admin = new Admin();

// Logika do usuwania klienta
if (isset($_POST['usunKlienta'])) {
    $id_klienta = $_POST['id_klienta'];
    $admin->usunKlienta($id_klienta);

    $response = $admin->usunKlienta($id_klienta);

    if ($response['status']) {
        $_SESSION['message'] = ['status' => true, 'message' => $response['message']];
    } else {
        $_SESSION['message'] = ['status' => false, 'message' => $response['message']];
    }
}

if (isset($_SESSION['message'])) {
    $messageType = $_SESSION['message']['status'] ? 'success' : 'error';
    echo "<div class='message $messageType'>" . $_SESSION['message']['message'] . "</div>";
    unset($_SESSION['message']);
}


$klienci = $admin->pobierzKlientow();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Lista Klientów</title>
    <link rel="stylesheet" href="style_lista_klientow.css">
</head>
<body>

<script>
        
        window.onload = function() {
            setTimeout(function() {
                var messageElement = document.querySelector('.message');
                if (messageElement) {
                    messageElement.style.display = 'none';
                }
            }, 5000); 
        };
    </script>

    <h1>Lista Klientów</h1>
    <a href="index.php">Strona Główna</a>
    <table>
        <thead>
            <tr>
                <th>Imię</th>
                <th>Nazwisko</th>
                <th>Email</th>
                <th>Telefon</th>
                <th>Data rejestracji</th>
                <th>Ilość Wypożyczeń</th>
                <th>Operacje</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($klienci as $klient): ?>
            <tr>
                <td><?php echo htmlspecialchars($klient['imie']); ?></td>
                <td><?php echo htmlspecialchars($klient['nazwisko']); ?></td>
                <td><?php echo htmlspecialchars($klient['email']); ?></td>
                <td><?php echo htmlspecialchars($klient['telefon']); ?></td>
                <td>
                    <?php 
                    if (isset($klient['data_rejestracji']) && $klient['data_rejestracji'] instanceof MongoDB\BSON\UTCDateTime) {
                        $dataRejestracji = $klient['data_rejestracji']->toDateTime();
                        echo htmlspecialchars($dataRejestracji->format('Y-m-d H:i:s'));
                    } else {
                        echo 'Brak danych';
                    }
                    ?>
                </td>
                <td><?php echo htmlspecialchars($klient['ilosc_wypozyczen']); ?></td>
                <td>
                <form method="post">
        <input type="hidden" name="id_klienta" value="<?php echo $klient['_id']; ?>">
        <input type="submit" name="usunKlienta" value="Usuń" class="usunKlienta-button">
    </form>
    <a href="modyfikuj_klienta.php?id=<?php echo $klient['_id']; ?>" class="modyfikujKlienta-button">Modyfikuj</a>
     </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>