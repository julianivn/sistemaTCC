<?php

// Inclui o arquivo de autoload do Composer
require_once __DIR__.'/../vendor/autoload.php';

// Instância uma aplicação Silex
$app = new Silex\Application();

// ATENÇÃO: utilizar apenas em ambiente de desenvolvimento
$app['debug'] = true;

// Define a rota para a raiz do site quando o método for GET
$app->get('/', function () {
    return 'sistemaTCC';
});

// Dispara a aplicação
$app->run();
