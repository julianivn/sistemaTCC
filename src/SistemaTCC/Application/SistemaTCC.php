<?php

namespace SistemaTCC\Application;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use SistemaTCC\Provider\DoctrineOrmServiceProvider;
use Silex\Provider\ValidatorServiceProvider;

class SistemaTCC extends Application {

	use Application\UrlGeneratorTrait;

	public function __construct() {

		parent::__construct();

		$app = $this;

		Request::enableHttpMethodParameterOverride();

		$app['debug'] = true;

		// Provider
		$this->register(new DoctrineOrmServiceProvider());
		$app->register(new TwigServiceProvider(), ['twig.path' => __DIR__ . '/../View/']);

		// Validator
		$app->register(new ValidatorServiceProvider());

		// Controller
		$app->get('/', "\\SistemaTCC\\Controller\\IndexController::indexAction")->bind('/');
		$app->get('/creditos/', "\\SistemaTCC\\Controller\\IndexController::creditosAction");
		$app->get('/login/', "\\SistemaTCC\\Controller\\IndexController::creditosAction");

		$app->get('/aluno/', "\\SistemaTCC\\Controller\\AlunoController::indexAction");
		$app->get('/aluno/cadastrar/', "\\SistemaTCC\\Controller\\AlunoController::cadastrarAction");
		$app->get('/aluno/editar/{id}/', "\\SistemaTCC\\Controller\\AlunoController::editarAction");
		$app->get('/aluno/excluir/', "\\SistemaTCC\\Controller\\AlunoController::excluirAction");
		$app->get('/aluno/listar/', "\\SistemaTCC\\Controller\\AlunoController::listarAction");

		$app->get('/professor/', "\\SistemaTCC\\Controller\\ProfessorController::indexAction");
		$app->get('/professor/cadastrar/', "\\SistemaTCC\\Controller\\ProfessorController::cadastrarAction");
		$app->get('/professor/editar/{id}', "\\SistemaTCC\\Controller\\ProfessorController::editarAction");
		// $app->get('/professor/excluir/', "\\SistemaTCC\\Controller\\ProfessorController::excluirAction");
		$app->get('/professor/listar/', "\\SistemaTCC\\Controller\\ProfessorController::listarAction");

		$app->get('/semestre/', "\\SistemaTCC\\Controller\\SemestreController::indexAction");
		$app->get('/semestre/cadastrar/', "\\SistemaTCC\\Controller\\SemestreController::cadastrarAction");
		$app->get('/semestre/editar/', "\\SistemaTCC\\Controller\\SemestreController::editarAction");
		$app->get('/semestre/excluir/', "\\SistemaTCC\\Controller\\SemestreController::excluirAction");
		$app->get('/semestre/listar/', "\\SistemaTCC\\Controller\\SemestreController::listarAction");
		
		$app->get('/tcc/', "\\SistemaTCC\\Controller\\TccController::indexAction");
		$app->get('/tcc/cadastrar/', "\\SistemaTCC\\Controller\\TccController::cadastrarAction");
		$app->get('/tcc/editar/', "\\SistemaTCC\\Controller\\TccController::editarAction");
		$app->get('/tcc/excluir/', "\\SistemaTCC\\Controller\\TccController::excluirAction");
		$app->get('/tcc/listar/', "\\SistemaTCC\\Controller\\TccController::listarAction");



		// REST Aluno
		$app->post('/aluno/', "\\SistemaTCC\\Controller\\AlunoController::add");
		$app->get('/aluno/{id}/', "\\SistemaTCC\\Controller\\AlunoController::find");
		$app->put('/aluno/{id}/', "\\SistemaTCC\\Controller\\AlunoController::edit");
		$app->delete('/aluno/{id}/', "\\SistemaTCC\\Controller\\AlunoController::del");

		// REST Professor
		$app->post('/professor/', "\\SistemaTCC\\Controller\\ProfessorController::add");
		$app->get('/professor/{id}/', "\\SistemaTCC\\Controller\\ProfessorController::find");
		$app->put('/professor/{id}/', "\\SistemaTCC\\Controller\\ProfessorController::edit");
		$app->delete('/professor/{id}/', "\\SistemaTCC\\Controller\\ProfessorController::del");


		$app->get('/enviaretapa/', "\\SistemaTCC\\Controller\\EnviarEtapaController::indexAction");
		$app->get('/enviaretapa/listar/', "\\SistemaTCC\\Controller\\EnviarEtapaController::listarAction");
		$app->get('/enviaretapa/enviar/{id}/', "\\SistemaTCC\\Controller\\EnviarEtapaController::enviarAction");
		$app->get('/enviaretapa/nota/{id}/', "\\SistemaTCC\\Controller\\EnviarEtapaController::notaAction");

		// REST Etapa Status
		$app->post('/etapa-status/', "SistemaTCC\Controller\EtapaStatusController::add");
		$app->get('/etapa-status/', "SistemaTCC\Controller\EtapaStatusController::all");
		$app->get('/etapa-status/{id}/', "SistemaTCC\Controller\EtapaStatusController::find");
		$app->put('/etapa-status/{id}/', "SistemaTCC\Controller\EtapaStatusController::edit");
		$app->delete('/etapa-status/{id}/', "SistemaTCC\Controller\EtapaStatusController::del");

		// REST semestre
		$app->post('/semestre/', "\\SistemaTCC\\Controller\\SemestreController::add");
		$app->put('/semestre/{id}/', "\\SistemaTCC\\Controller\\SemestreController::edit");
		$app->delete('/semestre/{id}/', "\\SistemaTCC\\Controller\\SemestreController::del");
		
		// REST tcc
		$app->post('/tcc/', "\\SistemaTCC\\Controller\\TccController::add");
		$app->put('/tcc/{id}/', "\\SistemaTCC\\Controller\\TccController::edit");
		$app->delete('/tcc/{id}/', "\\SistemaTCC\\Controller\\TccController::del");
		
		// Twig Extensions

        $app['twig'] = $app->extend('twig', function ($twig, $app) {
			/**
			 * Função de extensão do Twig para formatar números de telefone para exibição.
			 */
			$fone_format = new \Twig_SimpleFilter('fone_format', function($tel) {
				$formatado = '';
				if(strlen($tel)>=10){
					$formatado = '(' . substr($tel,0,2) . ') ';
				}
				$formatado .= substr($tel,2,-4) . '-' . substr($tel,-4);
				return $formatado;
			});
			$twig->addFilter($fone_format);
			return $twig;
		});
	}

}
