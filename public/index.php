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

// Define a rota para a raiz do site quando o método for GET
$app->get('/', function () {
    return "sistemaTCC\n";
});

// Exemplos de rotas
$app->get('/creditos/', function () {
    return "Créditos: Alunos do Curso de Ciência da Computação\n";
});
$app->get('/aluno/{nome}/', function ($nome) {
    return "O nome do aluno é {$nome}\n";
});
$app->get('/login/', function () {
    return "Login\n";
});
$app->post('/login/', function (Request $request) {
    return new Response('Obrigado pela visita: ' . $request->get('nome') . "\n", 201);
});

// Dispara a aplicação
$app->run();
