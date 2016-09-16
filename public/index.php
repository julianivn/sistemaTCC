<?php

// Inclui o arquivo de autoload do Composer
require_once __DIR__.'/../vendor/autoload.php';

// Utilizando componentes
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Instância uma aplicação Silex
$app = new Silex\Application();

// ATENÇÃO: utilizar apenas em ambiente de desenvolvimento
$app['debug'] = true;

// Registrando serviços
$app->register(new Silex\Provider\TwigServiceProvider(), ['twig.path' => __DIR__ . '/../src/View/']);

// Define a rota para a raiz do site quando o método for GET
$app->get('/', function () use ($app) {
    return $app['twig']->render('index.twig');
});

// Exemplos de rotas
$app->get('/creditos/', function (Silex\Application $app) {
    return $app['twig']->render('creditos.twig');
});

// Dispara a aplicação
$app->run();
