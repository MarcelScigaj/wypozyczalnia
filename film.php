<?php
require_once "connection.php";

class Film {
    private $db;

    public function __construct() {
        $this->db = getMongoDB();
    }

    public function dodajFilm($tytul, $gatunek, $rezyser, $czas_trwania, $ocena, $opis, $aktorzy) {

        $aktorzyArray = explode(',', $aktorzy);


        $filmDocument = [
            "tytuł" => $tytul,
            "gatunek" => $gatunek,
            "reżyser" => $rezyser,
            "dlugosc" => (int)$czas_trwania,
            "ocena" => (float)$ocena,
            "streszczenieFabuły" => $opis,
            "główniAktorzy" => $aktorzyArray,
            "data_dodania" => new MongoDB\BSON\UTCDateTime(),
            "wypozyczony" => false

        ];
        return $this->db->filmy->insertOne($filmDocument);
    }



    public function wyswietlFilmy($szukaj = null, $sortuj = null) {
        $query = [];
        $options = [];
    
        if ($szukaj) {
            $regex = new MongoDB\BSON\Regex($szukaj, 'i');
            $query = [
                '$or' => [
                    ['tytuł' => $regex],
                    ['gatunek' => $regex] // Szukanie w całej tablicy gatunków
                ]
            ];
        }
    
        else if ($sortuj) {
            $sort = [];
    
            if ($sortuj == 'gatunek') {
                $sort = ['gatunek.0' => 1]; // Sortowanie po pierwszym elemencie w tablicy gatunków
            } elseif ($sortuj == 'tytul') {
                $sort = ['tytuł' => 1];
            }
    
            $options['sort'] = $sort;
    
        }
    
        $cursor = $this->db->filmy->find($query, $options);
    
        return iterator_to_array($cursor);
    }
    
    
    
    
    
    public function czyFilmIstnieje($tytul) {
        $wynik = $this->db->filmy->findOne(['tytuł' => $tytul]);
        return $wynik !== null;
    }

    public function pobierzTytulFilmu($id_filmu) {
        $film = $this->db->filmy->findOne(['_id' => new MongoDB\BSON\ObjectId($id_filmu)]);
        return $film ? $film['tytuł'] : null;
    }

}

?>

