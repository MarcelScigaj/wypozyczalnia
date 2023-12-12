<?php
require_once "connection.php";

class Klient {
    private $db;

    public function __construct() {
        $this->db = getMongoDB();
    }

    public function dodajKlienta($imie, $nazwisko, $adres, $telefon, $data_rejestracji) {
        // Implementacja dodawania klienta
        $klientDocument = [
            "imie" => $imie,
            "nazwisko" => $nazwisko,
            "adres" => $adres,
            "telefon" => $telefon,
            "data_rejestracji" => $data_rejestracji
        ];
        return $this->db->klienci->insertOne($klientDocument);
    }

    public function usunKlienta($id_klienta) {
        // Implementacja usuwania klienta
        return $this->db->klienci->deleteOne(['_id' => $id_klienta]);
    }

    public function modyfikujKlienta($id_klienta, $noweDane) {
        // Implementacja modyfikacji danych klienta
        return $this->db->klienci->updateOne(['_id' => $id_klienta], ['$set' => $noweDane]);
    }
}

?>
