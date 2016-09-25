<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once __DIR__ . "/../vendor/autoload.php";

$isDevMode = true;
$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/orm/"), $isDevMode);

$conn = [
    'driver'   => 'pdo_mysql',
    'host'     => 'localhost',
    'dbname'   => 'sistema_tcc',
    'user'     => 'root',
    'password' => '',
    'charset'  => 'utf8mb4',
];

$entityManager = EntityManager::create($conn, $config);
