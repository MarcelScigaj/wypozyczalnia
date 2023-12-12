<?php
require_once "connection.php";
//include "Klient.php";

class User {
    private $db;
    private $klienciCollection;

    public function __construct() {
        $this->db = getMongoDB();
        $this->klienciCollection = $this->db->klienci;
    }

    public function isLoginEmailUnique($login, $email) {
        $loginCount = $this->klienciCollection->countDocuments(['login' => $login]);
        $emailCount = $this->klienciCollection->countDocuments(['email' => $email]);

        return $loginCount == 0 && $emailCount == 0;
    }

    public function registerUser($login, $email, $password, $imie, $nazwisko, $telefon, $rola,$ilosc_wypozyczen) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $dataRejestracji = new MongoDB\BSON\UTCDateTime();

        $userDocument = [
            "login" => $login,
            "email" => $email,
            "password" => $hashedPassword,
            "imie" => $imie,
            "nazwisko" => $nazwisko,
            "telefon" => $telefon,
            "rola" => $rola,
            "ilosc_wypozyczen" => $ilosc_wypozyczen,
            "data_rejestracji" => $dataRejestracji
        ];

        try {
            $result = $this->klienciCollection->insertOne($userDocument);
            return $result->getInsertedCount() == 1;
        } catch (Exception $e) {
            return false;
        }
    }


    public function getUserDetails($email) {
        return $this->klienciCollection->findOne(['email' => $email]);
    }

    public function verifyUser($email, $password) {
        $user = $this->klienciCollection->findOne(['email' => $email]);
    
        if ($user) {
            if (password_verify($password, $user['password'])) {
                return ['status' => true, 'id' => (string) $user['_id']]; // dane są poprawne
            } else {
                return ['status' => false, 'reason' => "wrong_password"]; // login pasuje, hasło nie
            }
        } else {
            return ['status' => false, 'reason' => "no_user"]; // nie ma konta z takim loginem
        }
    }


    public function getUserRole($email) {
        $user = $this->klienciCollection->findOne(['email' => $email], ['projection' => ['rola' => 1]]);
        return $user ? $user['rola'] : null;
    }

    public function pobierzWszystkichKlientow() {
        $cursor = $this->klienciCollection->find();
        return iterator_to_array($cursor);
    }

    public function usunKlienta($id_klienta) {
        // Sprawdź, czy klient ma aktywne wypożyczenia
        $wypozyczeniaCollection = $this->db->wypozyczenia;
        $aktywneWypozyczenia = $wypozyczeniaCollection->countDocuments(['id_klienta' => new MongoDB\BSON\ObjectId($id_klienta), 'data_zwrotu' => null]);
    
        if ($aktywneWypozyczenia > 0) {
            
            return ['status' => false, 'message' => 'Klient posiada aktywne wypożyczenia i nie może być usunięty.'];
        } else {
            
            $result = $this->klienciCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id_klienta)]);
            return ['status' => true, 'message' => 'Klient został usunięty.'];
        }
    }
    

    public function modyfikujKlienta($id_klienta, $noweDane) {
        // Logika modyfikacji danych klienta
        return $this->klienciCollection->updateOne(['_id' => new MongoDB\BSON\ObjectId($id_klienta)], ['$set' => $noweDane]);
    }
    public function pobierzKlienta($id_klienta) {
        return $this->klienciCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($id_klienta)]);
    }
}
?>
