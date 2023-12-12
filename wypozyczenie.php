<?php
require_once "connection.php";
require_once "film.php";
require_once "user.php";
date_default_timezone_set('Europe/Warsaw');

class Wypozyczenie {
    private $db;
    private $filmyCollection;
    private $klienciCollection;
    private $wypozyczeniaCollection;
    private $film;

    public function __construct() {
        $this->db = getMongoDB();
        $this->filmyCollection = $this->db->filmy;
        $this->klienciCollection = $this->db->klienci;
        $this->wypozyczeniaCollection = $this->db->wypozyczenia;
        $this->film = new Film();
    }

    public function wypozyczFilm($tytul) {
        // Pobierz ID klienta z sesji
        $id_klienta = $_SESSION['userId'];

        // Sprawdź, czy film istnieje i jest dostępny
        $film = $this->filmyCollection->findOne(['tytuł' => $tytul]);
        if (!$film) {
            return "Film nie istnieje";
        }

        // Sprawdź, czy klient nie przekroczył limitu wypożyczeń
        $klient = $this->klienciCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($id_klienta)]);
        if ($klient['ilosc_wypozyczen'] >= 3) {
            return "Przekroczono limit wypożyczeń";
        }

        // Rejestruj wypożyczenie
        $wypozyczenieDocument = [
            "id_klienta" => new MongoDB\BSON\ObjectId($id_klienta),
            "id_filmu" => $film['_id'],
            "data_wypozyczenia" => new MongoDB\BSON\UTCDateTime(),
            "data_planowanego_zwrotu" => new MongoDB\BSON\UTCDateTime((new DateTime())->modify('+2 days')->getTimestamp()*1000)
        ];
        $this->wypozyczeniaCollection->insertOne($wypozyczenieDocument);


        $this->filmyCollection->updateOne(
            ['_id' => $film['_id']],
            ['$set' => ['wypozyczony' => true]]
        );

        // Zaktualizuj ilość wypożyczeń klienta
        $this->klienciCollection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($id_klienta)],
            ['$inc' => ['ilosc_wypozyczen' => 1]]
        );

        return "Film wypożyczony pomyślnie";
    }

    public function zwrocFilm($id_wypozyczenia) {
        $wypozyczenie = $this->wypozyczeniaCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($id_wypozyczenia)]);
    
       //
    
        // Jeśli wypożyczenie istnieje, zaktualizuj je, dodając datę zwrotu
        if ($wypozyczenie) {
            $this->wypozyczeniaCollection->updateOne(
                ['_id' => $wypozyczenie['_id']],
                ['$set' => ['data_zwrotu' => new MongoDB\BSON\UTCDateTime()]]
            );
    
            // Zaktualizuj wartość 'wypozyczony' na 'false' dla filmu
            $updateResult = $this->filmyCollection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($wypozyczenie['id_filmu'])],
                ['$set' => ['wypozyczony' => false]]
            );
    
            // Zaktualizuj ilość wypożyczeń klienta, który zwraca film
            if ($updateResult->getModifiedCount() > 0) {
                $this->klienciCollection->updateOne(
                    ['_id' => $wypozyczenie['id_klienta']],
                    ['$inc' => ['ilosc_wypozyczen' => -1]]
                );
                return "Film zwrócony pomyślnie";
            } else {
                return "Nie udało się zaktualizować stanu filmu";
            }
        } else {
            return "Nie istnieje wypożyczenie tego filmu";
        }
    }
    

    public function wyswietlWypozyczoneFilmy($id_klienta) {
        $query = [
            'id_klienta' => new MongoDB\BSON\ObjectId($id_klienta)
        ];
        $wypozyczone_filmy = $this->wypozyczeniaCollection->find($query)->toArray();
    
        foreach ($wypozyczone_filmy as $key => $wypozyczenie) {
            $id_filmu = $wypozyczenie['id_filmu'];
            $tytul_filmu = $this->film->pobierzTytulFilmu($id_filmu);
            $wypozyczone_filmy[$key]['tytul_filmu'] = $tytul_filmu;
        }
    
        return $wypozyczone_filmy;
    }


    public function wyswietlWszystkieWypozyczenia($szukaj = '', $sortuj = '') {
        $pipeline = [];
        

        // Łączenie z kolekcją filmów
        $pipeline[] = [
            '$lookup' => [
                'from' => 'filmy',
                'localField' => 'id_filmu',
                'foreignField' => '_id',
                'as' => 'film_info'
            ]
        ];

        // Łączenie z kolekcją klientów
        $pipeline[] = [
            '$lookup' => [
                'from' => 'klienci',
                'localField' => 'id_klienta',
                'foreignField' => '_id',
                'as' => 'klient_info'
            ]
        ];

        // Dodanie pola tytul_filmu i imie_nazwisko
        $pipeline[] = [
            '$addFields' => [
                'tytul_filmu' => ['$arrayElemAt' => ['$film_info.tytuł', 0]],
                'imie_nazwisko' => ['$concat' => [
                    ['$arrayElemAt' => ['$klient_info.imie', 0]],
                    ' ',
                    ['$arrayElemAt' => ['$klient_info.nazwisko', 0]]
                ]],
                'nazwisko' => ['$arrayElemAt' => ['$klient_info.nazwisko', 0]]
            ]
        ];
        


        if (!empty($szukaj)) {
            $regex = new MongoDB\BSON\Regex($szukaj, 'i');
            $pipeline[] = [
                '$match' => [
                    '$or' => [
                        ['tytul_filmu' => $regex],
                        ['imie_nazwisko' => $regex]
                    ]
                ]
            ];
        }

        

        // Sortowanie
        if (empty($szukaj) && !empty($sortuj)) {
            $sortField = null;
            switch ($sortuj) {
                case 'tytul_filmu':
                    $sortField = 'tytul_filmu';
                    break;
                case 'data_wypozyczenia':
                    $sortField = 'data_wypozyczenia';
                    break;
                case 'nazwisko':
                    $sortField = 'nazwisko';
                    break;
            }
            if ($sortField) {
                $pipeline[] = ['$sort' => [$sortField => 1]];
            }
        }

        return $this->wypozyczeniaCollection->aggregate($pipeline)->toArray();
    }
    
    
    public function usunWypozyczenie($id_wypozyczenia) {
        return $this->wypozyczeniaCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id_wypozyczenia)]);
    }
    

}

?>
