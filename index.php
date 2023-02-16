<?php

use App\factory\ConnectionFactory;
use App\dispatch\Dispatcher;

require_once 'vendor/autoload.php';

session_start();
ConnectionFactory::setConfig("dbconfig.ini");

$dispatcher = new Dispatcher();
$dispatcher->run();
