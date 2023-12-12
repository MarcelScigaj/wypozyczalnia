<?php
require "film.php";
require "user.php";
require "wypozyczenie.php";

class Admin {
    private $film;
    private $klient;
    private $wypozyczenie;

    public function __construct() {
        $this->film = new Film();
        $this->user = new User();
        $this->wypozyczenie = new Wypozyczenie();
    }

     
    public function wyswietlWszystkieWypozyczenia() {
        $wypozyczenia = $this->wypozyczenie->wyswietlWszystkieWypozyczenia();
        foreach ($wypozyczenia as $key => $wypozyczenie) {
            $id_filmu = $wypozyczenie['id_filmu'];
            $tytul_filmu = $this->film->pobierzTytulFilmu($id_filmu);
            $wypozyczenia[$key]['tytul_filmu'] = $tytul_filmu;
        }
        return $wypozyczenia;
    }

    
    public function usunKlienta($id_klienta) {
        return $this->user->usunKlienta($id_klienta);
    }

    // Modyfikacja danych klienta
    public function modyfikujDaneKlienta($id_klienta, $noweDane) {
        return $this->user->modyfikujKlienta($id_klienta, $noweDane);
    }

    // Modyfikacja opisu filmu
    public function modyfikujOpisFilmu($id_filmu, $noweDane) {
        return $this->film->modyfikujFilm($id_filmu, $noweDane);
    }

    // Usuwanie filmu
    public function usunFilm($id_filmu) {
        return $this->film->usunFilm($id_filmu);
    }

    public function pobierzKlientow() {
        return $this->user->pobierzWszystkichKlientow();
    }

    public function pobierzKlienta($id_klienta) {
        return $this->user->pobierzKlienta($id_klienta);
    }

    
}
?>
