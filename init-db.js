db = db.getSiblingDB('wypozyczalnia');

db.filmy.insertMany([
  {
    "_id": ObjectId("6571ce88442553cba36f11a1"),
    "tytuł": "Avatar",
    "reżyser": "James Cameron",
    "gatunek": ["Akcja", "Przygodowy", "Sci-Fi"],
    "rokPremiery": 2009,
    "główniAktorzy": ["Sam Worthington", "Zoe Saldana", "Sigourney Weaver", "Stephen Lang"],
    "ocena": 7.4,
    "streszczenieFabuły": "Główny bohater, Jake Sully, to dawny marine, sparaliżowany od pasa w dół w wyniku walki na Ziemi. Otrzymuje rozkaz uczestniczenia w programie Avatar, który da mu zdrowe ciało. Zadaniem Jake'a jest podróżowanie po Pandorze, bujnej planecie porośniętej lasem deszczowym, wypełnionej niezwykłymi istotami.",
    "dlugosc": 194,
    "data_dodania": new Date("2023-12-07T13:55:18.573Z"),
    "wypozyczony": false
  },
  {
    "_id": ObjectId("6571ce88442553cba36f11a2"),
    "tytuł": "Titanic",
    "reżyser": "James Cameron",
    "gatunek": ["Dramat", "Romantyczny"],
    "rokPremiery": 1997,
    "główniAktorzy": ["Leonardo DiCaprio", "Kate Winslet", "Billy Zane", "Kathy Bates"],
    "ocena": 7.3,
    "streszczenieFabuły": "Film opowiada historię Jacka Dawsona (Leonardo DiCaprio) i Rose DeWitt Bukater (Kate Winslet) – pary młodych kochanków, którzy poznają się i zakochują podczas dziewiczego rejsu na uznawanym za niezatapialny transatlantyku. Jack to ubogi malarz, który bilet na statek wygrywa w karty.",
    "dlugosc": 195,
    "data_dodania": new Date("2023-12-07T13:55:18.573Z"),
    "wypozyczony": false
  },
  {
    "_id": ObjectId("6571ce88442553cba36f11a3"),
    "tytuł": "Efekt Motyla",
    "reżyser": "Eric Bress, J. Mackye Gruber",
    "gatunek": ["Thriller", "Sci-Fi"],
    "rokPremiery": 2004,
    "główniAktorzy": ["Ashton Kutcher", "Amy Smart", "Melora Walters", "Elden Henson"],
    "ocena": 7.8,
    "streszczenieFabuły": "\"Efekt motyla\", jak wyjaśnia Amy Smart, to teoria zakładająca, że machnięcie motylego skrzydełka w Nowym Jorku może spowodować huragan w Japonii. Film jest ilustracją tej teorii. Drobne zmiany, jakie Evan Treborn (Ashton Kutcher) wprowadza w swojej przeszłości, mają potężny wpływ na jego teraźniejszość.",
    "dlugosc": 113,
    "data_dodania": new Date("2023-12-07T13:55:18.573Z"),
    "wypozyczony": false
  }
]
)

db.klienci.insertMany([
  {
    "_id": ObjectId("657098bc235300005b0079c3"),
    "login": "Marcelek1",
    "email": "marcel.scigaj@wp.pl",
    "password": "$2y$10$exjF/kUR1t5A01ekNfFmkOuohlwxhD2BqlVzzBI7ELNOmfW5LZWxu",
    "imie": "Marcelek",
    "nazwisko": "Wojtusek",
    "telefon": "123456788",
    "rola": 1,
    "ilosc_wypozyczen": 0,
    "data_rejestracji": new Date("2023-12-10T16:02:48.625Z")
  },
  {
    "_id": ObjectId("65721dca5720000081003b42"),
    "login": "Scigu",
    "email": "marcel.nieznajomy@wp.pl",
    "password": "$2y$10$0qlXgiVvMh3xrH9URU/TTeUBsLSz1zv0SkcNUPPK3Tbzsgba/qZ8y",
    "imie": "Marcel",
    "nazwisko": "Testowy",
    "telefon": "826504228",
    "rola": 0,
    "ilosc_wypozyczen": 1,
    "data_rejestracji": new Date("2023-12-10T16:02:48.625Z")
  }
]);

db.wypozyczenia.insertMany([
  {
    "_id": ObjectId("6575c5cb9c7c0000ee001142"),
    "id_klienta": ObjectId("65721dca5720000081003b42"),
    "id_filmu": ObjectId("6571ce88442553cba36f11a7"),
    "data_wypozyczenia": new Date("2023-12-10T14:06:03.036Z"),
    "data_planowanego_zwrotu": new Date("2023-12-12T14:06:03.000Z"),
    "data_zwrotu": new Date("2023-12-10T16:54:48.235Z")
  },
  {
    "_id": ObjectId("6575c5cd9c7c0000ee001143"),
    "id_klienta": ObjectId("65721dca5720000081003b42"),
    "id_filmu": ObjectId("6571ce88442553cba36f11a2"),
    "data_wypozyczenia": new Date("2023-12-10T14:06:05.532Z"),
    "data_planowanego_zwrotu": new Date("2023-12-12T14:06:05.000Z"),
    "data_zwrotu": new Date("2023-12-10T16:30:46.262Z")
  },
  {
    "_id": ObjectId("6575e76a9c7c0000ee001147"),
    "id_klienta": ObjectId("657098bc235300005b0079c3"),
    "id_filmu": ObjectId("6572075f442553cba36f11aa"),
    "data_wypozyczenia": new Date("2023-12-10T16:29:30.657Z"),
    "data_planowanego_zwrotu": new Date("2023-12-12T16:29:30.000Z"),
    "data_zwrotu": new Date("2023-12-10T16:48:37.298Z")
  },
  {
    "_id": ObjectId("6575e7c29c7c0000ee001148"),
    "id_klienta": ObjectId("657098bc235300005b0079c3"),
    "id_filmu": ObjectId("657317f6f612000069005e33"),
    "data_wypozyczenia": new Date("2023-12-10T16:30:58.622Z"),
    "data_planowanego_zwrotu": new Date("2023-12-12T16:30:58.000Z"),
    "data_zwrotu": new Date("2023-12-10T16:31:17.438Z")
  },
  {
    "_id": ObjectId("6575e7c49c7c0000ee001149"),
    "id_klienta": ObjectId("657098bc235300005b0079c3"),
    "id_filmu": ObjectId("6571ce88442553cba36f11a3"),
    "data_wypozyczenia": new Date("2023-12-10T16:31:00.389Z"),
    "data_planowanego_zwrotu": new Date("2023-12-12T16:31:00.000Z"),
    "data_zwrotu": new Date("2023-12-10T16:31:08.638Z")
  }
]
)
