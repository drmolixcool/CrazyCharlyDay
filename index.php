<?php
use App\factory\ConnectionFactory;
use App\dispatch\Dispatcher;
use App\exception\DatabaseConnectionException;

session_start();

require_once 'vendor/autoload.php';

ConnectionFactory::setConfig('dbconfig.ini');

$action = $_GET['action'] ?? '';

$dispatcher = new Dispatcher($action);

try {
    $dispatcher->run();
} catch (DatabaseConnectionException $exception) {
    echo $exception->getMessage();
}
echo
 "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <title>CrazyCharlyDay</title>
</head>
<body>

    <header>
        <a href=''>Accueil</a>
        <a href=''>Catalogue</a>
        <a href=''>Mon Panier</a>
        <a href=''>Mon Compte</a>

    </header>

<img src='Images/1.jpg' alt='image1'>


</body>

<footer class='fixed bottom-0 h-10 bg-black text-center'>
        <a href=''>Mentions légales</a>
        <a href=''>Nous contacter</a>
</footer>

</html>" ;