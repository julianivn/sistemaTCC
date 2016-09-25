<?php

namespace SistemaTCC\Application;

use Symfony\Componenet\HttpFoundation\Request;
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use SistemaTCC\Provider\DoctrineOrmServiceProvider;

class SistemaTCC extends Application {

    public function __construct() {

        parent::__construct();

        $app = $this;

        $app['debug'] = true;

        // Provider
        $this->register(new DoctrineOrmServiceProvider());
        $app->register(new TwigServiceProvider(), ['twig.path' => __DIR__ . '/../View/']);

        // Controller
        $app->get('/', "\\SistemaTCC\\Controller\\IndexController::indexAction");
        $app->get('/creditos/', "\\SistemaTCC\\Controller\\IndexController::creditosAction");
        $app->get('/login/', "\\SistemaTCC\\Controller\\IndexController::creditosAction");

        $app->get('/aluno/', "\\SistemaTCC\\Controller\\AlunoController::indexAction");
        $app->get('/aluno/cadastrar/', "\\SistemaTCC\\Controller\\ProfessorController::cadastrarAction");
        $app->get('/aluno/editar/', "\\SistemaTCC\\Controller\\ProfessorController::editarAction");
        $app->get('/aluno/excluir/', "\\SistemaTCC\\Controller\\ProfessorController::excluirAction");
        $app->get('/aluno/listar/', "\\SistemaTCC\\Controller\\ProfessorController::listarAction");

        $app->get('/professor/', "\\SistemaTCC\\Controller\\ProfessorController::indexAction");
        $app->get('/professor/cadastrar/', "\\SistemaTCC\\Controller\\ProfessorController::cadastrarAction");
        $app->get('/professor/editar/', "\\SistemaTCC\\Controller\\ProfessorController::editarAction");
        $app->get('/professor/excluir/', "\\SistemaTCC\\Controller\\ProfessorController::excluirAction");
        $app->get('/professor/listar/', "\\SistemaTCC\\Controller\\ProfessorController::listarAction");

        $app->get('/semestre/', "\\SistemaTCC\\Controller\\SemestreController::indexAction");
        $app->get('/semestre/cadastrar/', "\\SistemaTCC\\Controller\\SemestreController::cadastrarAction");
        $app->get('/semestre/editar/', "\\SistemaTCC\\Controller\\SemestreController::editarAction");
        $app->get('/semestre/excluir/', "\\SistemaTCC\\Controller\\SemestreController::excluirAction");
        $app->get('/semestre/listar/', "\\SistemaTCC\\Controller\\SemestreController::listarAction");

        $app->post('/semestre/', "\\SistemaTCC\\Controller\\SemestreController::add");
        $app->put('/semestre/{id}/', "\\SistemaTCC\\Controller\\SemestreController::edit");
        $app->delete('/semestre/{id}/', "\\SistemaTCC\\Controller\\SemestreController::del");

    }

}
