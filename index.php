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




