<?php

namespace SistemaTCC\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EnviarEtapaController {

	public function indexAction() {
		return 'Index Etapa Aluno';
	}

	public function listarAction(Application $app) {
		$semestre = 1; //Id Semestre das etapas a serem listadas
		$sql = 'SELECT e.id, e.nome, e.dataInicio, e.dataFim FROM \SistemaTCC\Model\Etapa e WHERE e.semestre = ' . $semestre
				. ' ORDER BY e.ordem';
		$query = $app['orm']->createQuery($sql);
		$etapas = $query->getResult();
		return $app['twig']->render('enviaretapa/listar.twig', array('etapas' => $etapas));
	}

	public function notaAction() {
		return 'Nota Etapa Aluno';
	}

	public function enviarAction() {
		return 'Enviar Etapa Aluno';
	}

}
