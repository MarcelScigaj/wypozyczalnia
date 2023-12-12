<?php
require_once 'vendor/autoload.php'; 

use MongoDB\Client;

function getMongoDB() {
    $host = 'localhost';
    $port = 27017;
    $dbName = 'wypozyczalnia';

    $client = new Client("mongodb://$host:$port");
    return $client->selectDatabase($dbName);
}
?>
