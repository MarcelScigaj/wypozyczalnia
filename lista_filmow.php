<?php
require_once "film.php";
require_once "wypozyczenie.php";
require_once "user.php";
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

/*if (!isset($_SESSION['form_token'])) {
    $_SESSION['form_token'] = bin2hex(random_bytes(32));
}*/

$film = new Film();
$komunikat = "";
$listaFilmow = [];


if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['szukaj'])) {
        $szukaj = $_GET['szukaj'];
        $listaFilmow = $film->wyswietlFilmy($szukaj, null);
    } elseif (isset($_GET['sortuj'])) {
        $sortuj = $_GET['sortuj'];
        $listaFilmow = $film->wyswietlFilmy(null, $sortuj);
    } else {
        $listaFilmow = $film->wyswietlFilmy();
    }
} else {
    $listaFilmow = $film->wyswietlFilmy();
} 



// Obsługa żądania POST dla dodawania filmu
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dodajFilm'])) {
    $tytul = $_POST['tytuł'];
    $gatunki = $_POST['gatunek'];
    $rezyser = $_POST['reżyser'];
    $czas_trwania = $_POST['czas_trwania'];
    $ocena = $_POST['ocena'];
    $opis = $_POST['opis'];
    $aktorzy = $_POST['aktorzy'];

    // Sprawdzenie, czy film o takim tytule już istnieje
    if ($film->czyFilmIstnieje($tytul)) {
        $komunikat = "Film o takim tytule już istnieje.";
    } else {
        // Dodanie filmu do bazy danych
        $wynik = $film->dodajFilm($tytul, $gatunki, $rezyser, $czas_trwania, $ocena, $opis, $aktorzy);
        if ($wynik) {
            $komunikat = "Film dodany pomyślnie.";
        } else {
            $komunikat = "Nie udało się dodać filmu.";
        }
    }
}

        
        // Obsługa żądania POST dla wypożyczenia filmu
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['wypozycz']) /*&& isset($_POST['form_token']) && $_POST['form_token'] === $_SESSION['form_token']*/) {
            require_once "Wypozyczenie.php";
            $wypozyczenie = new Wypozyczenie();
            $tytul = $_POST['tytul'];
            $wynikWypozyczenia = $wypozyczenie->wypozyczFilm($tytul);
            $komunikat = $wynikWypozyczenia;
            //$_SESSION['form_token'] = bin2hex(random_bytes(32));  Odśwież token
        }

        
        


?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Lista Filmów</title>
    <link rel="stylesheet" href="glowna.css">
    <link rel="stylesheet" href="style_lista_filmow.css">
    <script>
        function toggleDescription(id) {
            var x = document.getElementById(id);
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }

        function showAddFilmModal() {
            var modal = document.getElementById('modalDodajFilm');
            if (modal.style.display === "none" || modal.style.display === "") {
                modal.style.display = 'block';
            } else {
                modal.style.display = 'none';
            }
        }

        function showAlert(message) {
            if (message) {
                alert(message);
            }
        }
    </script>
</head>
<body onload="showAlert('<?php echo addslashes($komunikat); ?>')">
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
    <div class="main">
        <form action="lista_filmow.php" method="get">
            Szukaj: <input type="text" name="szukaj" placeholder="Tytuł lub gatunek">
            <input type="submit" value="Szukaj">
        </form>

        <form action="lista_filmow.php" method="get">
            Sortuj według:
                    <select name="sortuj">
            <option value="tytul">Tytuł</option>
            <option value="gatunek">Gatunek</option>
        </select>
            <input type="submit" value="Sortuj">
        </form>

        <?php if (isset($_SESSION['userRole']) && $_SESSION['userRole'] == 1): ?>
            <button class="add-film-button" onclick="showAddFilmModal()">Dodaj nowy Film</button>
            <div id="modalDodajFilm" class="modal" style="display:none;">
                <form action="lista_filmow.php" method="post">
                    Tytuł: <input type="text" name="tytuł" required><br>
                    Gatunek: 
                    <select name="gatunek[]" multiple required>
                        <option value="Komedia">Komedia</option>
                        <option value="Dreszczowiec">Dreszczowiec</option>
                        <option value="Dramat">Dramat</option>
                        <option value="Melodramat">Melodramat</option>
                        <option value="Fantasy">Fantasy</option>
                        <option value="Fantastycznonaukowy">Fantastycznonaukowy</option>
                        <option value="Kryminał">Kryminał</option>
                        <option value="Horror">Horror</option>
                        <option value="przygodowy">Przygodowy</option>
                        <option value="Muzyczny">Muzyczny</option>
                        <option value="Kostiumowy">Kostiumowy</option>
                        <option value="Sensacyjny">Sensacyjny</option>
                        <option value="Wojenny">Wojenny</option>
                        <option value="Western">Western</option>
                    </select><br>
                    Reżyser: <input type="text" name="reżyser" required><br>
                    Czas trwania: <input type="number" name="czas_trwania" min="1" required><br>
                    Ocena: <input type="number" step="0.1" name="ocena" min="1" required><br>
                    Opis: <textarea name="opis" required></textarea><br>
                    Aktorzy: <input type="text" name="aktorzy" required><br>
                    <input type="hidden" name="dodajFilm" value="1">
                    <input type="submit" value="Dodaj Film">
                </form>
            </div>
        <?php endif; ?>

        <?php if ($listaFilmow): ?> 
            <?php foreach ($listaFilmow as $index => $film): ?>
                <div class="film-item" style="<?php echo $film['wypozyczony'] ? 'border: 1px solid red;' : ''; ?>">
                    <div class="film-title" onclick="toggleDescription('description-<?php echo $index; ?>')">
                        <?php echo htmlspecialchars($film['tytuł']); ?>
                    </div>
                    <div id="description-<?php echo $index; ?>" class="film-description" style="display:none;">
                        Gatunek: <?php echo htmlspecialchars(implode(', ', $film['gatunek']->getArrayCopy())); ?><br>
                        Reżyser: <?php echo htmlspecialchars($film['reżyser']); ?><br>
                        Czas trwania: <?php echo htmlspecialchars($film['dlugosc']); ?> min<br>
                        Ocena: <?php echo htmlspecialchars($film['ocena']); ?><br>
                        Opis: <?php echo htmlspecialchars($film['streszczenieFabuły']); ?><br>
                        Aktorzy: <?php echo htmlspecialchars(implode(', ', $film['główniAktorzy']->getArrayCopy())); ?><br>
                        Data dodania: <?php echo htmlspecialchars(date('Y-m-d H:i:s', $film['data_dodania']->toDateTime()->getTimestamp())); ?><br>
                    </div>

                    <?php if (isset($_SESSION['user'])): ?>
                        <?php if (isset($film['wypozyczony']) && $film['wypozyczony']): ?>
                            <button class="wypozycz-button" disabled>Wypożyczony</button>
                        <?php else: ?>
                            <form action="lista_filmow.php" method="post">
                                <input type="hidden" name="tytul" value="<?php echo htmlspecialchars($film['tytuł']); ?>">
                                <input type="submit" name="wypozycz" value="Wypożycz" class="wypozycz-button">
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Brak filmów do wyświetlenia.</p>
        <?php endif; ?>
    </div>
</body>
</html>
