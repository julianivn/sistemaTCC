<?php

namespace SistemaTCC\Controller;

use DateTime;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

class EnviarEtapaController {

	private function validacao($app, $dados) {
		$asserts = [
			'arquivo' => [
				new Assert\NotBlank(['message' => 'Selecione um arquivo']),
			]
		];
		$constraint = new Assert\Collection($asserts);
		$errors = $app['validator']->validate($dados, $constraint);
		$retorno = [];
		if (count($errors)) {
			foreach ($errors as $error) {
				$key = preg_replace("/[\[\]]/", '', $error->getPropertyPath());
				$retorno[$key] = $error->getMessage();
			}
		}
		return $retorno;
	}

	public function add(Application $app, Request $request) {

		$dados = [
			'arquivo' => $request->get('arquivo')
		];

		$errors = $this->validacao($app, $dados);
		if (count($errors) > 0) {
			return $app->json($errors, 400);
		}
		$etapaEntrega = new \SistemaTCC\Model\EtapaEntrega();
		$etapaEntregaArquivo = new \SistemaTCC\Model\EtapaEntregaArquivo();
		$etapa = $app['orm']->find('\SistemaTCC\Model\Etapa', $request->get('etapa'));
		$aluno = $app['orm']->find('\SistemaTCC\Model\Aluno', $request->getSession()->get('alunoId')); //Verificar como será armazenado as informações do usuário na sessão
		$etapaStatus = $app['orm']->find('\SistemaTCC\Model\EtapaStatus', 1); //Verificar qual será o status padrão
		$tipo = ''; //IMPLEMENTAR - Não implementado upload de arquivos
		$caminho = ''; //IMPLEMENTAR - Não implementado upload de arquivos

		$etapaEntrega->setData(new DateTime(time()))
				->setAluno($aluno)
				->setEtapa($etapa)
				->setEtapaStatus($etapaStatus);

		$etapaEntregaArquivo->setNome($request->get('nome'))
				->setTipo($tipo)
				->setCaminho($caminho)
				->setEtapaEntrega($etapaEntrega);

		try {
			$app['orm']->persist($etapaEntregaArquivo);
			$app['orm']->flush();
		} catch (\Exception $e) {
			return $app->json([$e->getMessage()], 400);
		}
		return $app->json(['success' => 'Arquivo enviado com sucesso.'], 201);
	}

	public function indexAction(Application $app, Request $request) {
		return $app->redirect('../enviaretapa/listar');
	}

	public function listarAction(Application $app, Request $request) {
		$semestre = 1; //$request->getSession()->get('semestreId'); //Id Semestre das etapas a serem listadas (Verificar como será armazenado as informações de sessão)
		$db = $app['orm']->getRepository('\SistemaTCC\Model\Etapa');
		$etapas = $db->findBy(array('semestre' => $semestre));
		$dadosParaView = [
			'titulo' => 'Etapas',
			'etapas' => $etapas,
			'data_atual' => (new DateTime())
		];
		return $app['twig']->render('enviaretapa/listar.twig', $dadosParaView);
	}

	public function notaAction(Application $app, Request $request, $id) {
		$db = $app['orm']->getRepository('\SistemaTCC\Model\EtapaNota');
		$etapa = $app['orm']->find('\SistemaTCC\Model\Etapa', $id);
		$etapaEntrega = array();
		$nota = array();
		$subtitulo = '';
		if ($etapa) {
			$subtitulo = $etapa->getNome();
			$etapaEntrega = $app['orm']->getRepository('\SistemaTCC\Model\EtapaEntrega')->findOneByEtapa($id);
			if ($etapaEntrega) {
				$nota = $db->findBy(array('etapaEntrega' => $etapaEntrega->getId()));
			}
		}
		$dadosParaView = [
			'titulo' => 'Nota Etapa:',
			'subtitulo' => $subtitulo,
			'etapa' => $etapa,
			'notas' => $nota,
			'etapa_entrega' => $etapaEntrega
		];
		return $app['twig']->render('enviaretapa/nota.twig', $dadosParaView);
	}

	public function enviarAction(Application $app, Request $request, $id) {
		$db = $app['orm']->getRepository('\SistemaTCC\Model\Etapa');
		$etapa = $db->find($id);
		if (!$etapa) {
			return $app->redirect('../enviaretapa/listar');
		}
		$dadosParaView = [
			'titulo' => 'Enviar Etapa:',
			'subtitulo' => $etapa->getNome(),
			'etapa' => $id,
			'data_inicio' => $etapa->getDataInicio()->format('d/m/Y H:i:s'),
			'data_fim' => $etapa->getDataFim()->format('d/m/Y H:i:s'),
			'arquivos' => '' //IMPLEMENTAR - Listar arquivos já enviados
		];
		return $app['twig']->render('enviaretapa/formulario.twig', $dadosParaView);
	}

}
