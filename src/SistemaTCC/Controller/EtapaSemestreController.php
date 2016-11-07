<?php

namespace SistemaTCC\Controller;

use DateTime;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use SistemaTCC\Model\EtapaTipo;
use SistemaTCC\Model\Etapa;
use Symfony\Component\Validator\Constraints as Assert;

class EtapaSemestreController {

    private function validacao($app, $dados) {
        $asserts = [
            'nome' => [
                new Assert\NotBlank(['message' => 'Preencha esse campo']),
                new Assert\Regex([
                    'pattern' => '/^[a-zA-ZÀ-ú ]+$/i',
                    'message' => 'Seu nome deve possuir apenas letras'
                ]),
                new Assert\Length([
                    'min' => 3,
                    'max' => 50,
                    'minMessage' => 'Seu nome precisa possuir pelo menos {{ limit }} caracteres',
                    'maxMessage' => 'Seu nome não deve possuir mais que {{ limit }} caracteres',
                ])
            ],
			'tipo' => [
                new Assert\NotBlank(['message' => 'Preencha esse campo']),
                new Assert\Type([
                        'type'    => 'numeric',
                        'message' => 'O valor {{ value }} não é um {{ type }} válido.',
                    ]),
            ],
			'semestre' => [
                new Assert\NotBlank(['message' => 'Preencha esse campo']),
                new Assert\Type([
                        'type'    => 'numeric',
                        'message' => 'O valor {{ value }} não é um {{ type }} válido.',
                    ]),
            ],
			'peso' => [
                new Assert\NotBlank(['message' => 'Preencha esse campo']),
                new Assert\Type([
                        'type'    => 'numeric',
                        'message' => 'O valor {{ value }} não é um {{ type }} válido.',
                    ]),
            ],
            'dataInicio' => [
                new Assert\NotBlank(['message' => 'Preencha esse campo']),
                new Assert\Date(['message' => 'Preencha a data']),
            ],
            'dataFim' => [
                new Assert\NotBlank(['message' => 'Preencha esse campo']),
                new Assert\Date(['message' => 'Preencha a data']),
            ],
			'ordem' => [
                new Assert\NotBlank(['message' => 'Preencha esse campo']),
                new Assert\Type([
                        'type'    => 'numeric',
                        'message' => 'O valor {{ value }} não é um {{ type }} válido.',
                    ]),
            ],
			'tcc' => [
                new Assert\NotBlank(['message' => 'Preencha esse campo']),
                new Assert\Type([
                        'type'    => 'numeric',
                        'message' => 'O valor {{ value }} não é um {{ type }} válido.',
                    ]),
            ],
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
            'nome'       => $request->get('nome'),
			      'semestre'	 => $request->get('semestre'),
			      'tipo'       => $request->get('tipo'),
            'dataInicio' => $request->get('dataInicio'),
            'dataFim'    => $request->get('dataFim'),
            'peso'     	 => $request->get('peso'),
			      'ordem'		 => $request->get('ordem'),
			      'tcc'		 => $request->get('tcc')
        ];

        $errors = $this->validacao($app, $dados);
        if (count($errors) > 0) {
            return $app->json($errors, 400);
        }

        $etapa = new Etapa();
		$tipo = $app['orm']->find('\\SistemaTCC\\Model\\EtapaTipo', $request->get('tipo'));
        if (!$tipo) {
            return $app->json(['tipo' => 'Não existe tipo cadastrado'], 400);
        }

		$semestre = $app['orm']->find('\\SistemaTCC\\Model\\Semestre', $request->get('semestre'));
        if (!$semestre) {
            return $app->json(['semestre' => 'Não existe semestre cadastrado'], 400);
        }

		date_default_timezone_set('UTC');
		$etapa->setNome($request->get('nome'));
		$etapa->setSemestre($semestre);
		$etapa->setEtapaTipo($tipo);
		$etapa->setDataInicio(new DateTime($request->get('dataInicio')));
		$etapa->setDataFim(new DateTime($request->get('dataFim')));
		$etapa->setPeso($request->get('peso'));
		$etapa->setOrdem($request->get('ordem'));
		$etapa->setTcc($request->get('tcc'));
		$etapa->setEnviarEmailAdministrador(true);
		$etapa->setEnviarEmailBanca(true);
		$etapa->setEnviarEmailOrientador(true);

        try {
            $app['orm']->persist($etapa);
            $app['orm']->flush();
        }
        catch (\Exception $e) {
            return $app->json(['etapa' => $e->getMessage()], 400);
        }

        return $app->json(['etapa' => 'Semestre criado com sucesso', 'id' => $etapa->getId()]);
    }

    public function edit(Application $app, Request $request, $id) {

        $etapa = $app['orm']->find('\\SistemaTCC\\Model\\Etapa', (int) $id);
        if (!$etapa) {
          return $app->json(['etapa' => 'Não existe etapa cadastrada'], 400);
        }

        $dados = [
            'nome'       => $request->get('nome'),
			      'semestre'	 => $request->get('semestre'),
			      'tipo'       => $request->get('tipo'),
            'dataInicio' => $request->get('dataInicio'),
            'dataFim'    => $request->get('dataFim'),
            'peso'     	 => $request->get('peso'),
			      'ordem'		 => $request->get('ordem'),
			      'tcc'		 => $request->get('tcc')
        ];

        $errors = $this->validacao($app, $dados);
        if (count($errors) > 0) {
            return $app->json($errors, 400);
        }

        $tipo = $app['orm']->find('\\SistemaTCC\\Model\\EtapaTipo', $request->get('tipo'));
        if (!$tipo) {
            return $app->json(['tipo' => 'Não existe tipo cadastrado'], 400);
        }

		$semestre = $app['orm']->find('\\SistemaTCC\\Model\\Semestre', $request->get('semestre'));
        if (!$semestre) {
            return $app->json(['semestre' => 'Não existe semestre cadastrado'], 400);
        }
		date_default_timezone_set('UTC');
		$etapa->setNome($request->get('nome'));  //nome
		$etapa->setSemestre($semestre);  //semestre
		$etapa->setEtapaTipo($tipo);  //etapatipo
		$etapa->setDataInicio(new DateTime($request->get('dataInicio')));  //date
		$etapa->setDataFim(new DateTime($request->get('dataFim')));  //date
		$etapa->setPeso($request->get('peso')); //int
		$etapa->setOrdem($request->get('ordem')); //int
		$etapa->setTcc($request->get('tcc')); //int


        try {
            $app['orm']->flush();
        }
        catch (\Exception $e) {
            return $app->json(['etapa' => $e->getMessage()], 400);
        }
        return $app->json(['etapa' => 'Etapa alterada com sucesso']);
    }

    public function del(Application $app, Request $request, $id) {

        $etapa = $app['orm']->find('\\SistemaTCC\\Model\\Etapa', $id);
        if (!$etapa) {
            return $app->json(['etapa' => 'Não existe etapa cadastrada'], 400);
        }

        try {
            $app['orm']->remove($etapa);
            $app['orm']->flush();
        }
        catch (\Exception $e) {
          return $app->json(['etapa' => $e->getMessage()], 400);
        }
        return $app->json(['etapa' => 'Etapa excluido com sucesso']);
    }

    public function find(Application $app, Request $request, $id) {

        if (null === $etapa = $app['orm']->find('\SistemaTCC\Model\Etapa', (int) $id))
            return new Response('A etapa não existe.', Response::HTTP_NOT_FOUND);

        $etapaTipo = $app['orm']->getRepository('\SistemaTCC\Model\EtapaTipo')->find($etapa->getEtapaTipo());

        $dados = [
            'id'         => $etapa->getId(),
            'nome'       => $etapa->getNome(),
            'tipo'       => $etapa->getEtapaTipo()->getId(),
            'peso'       => $etapa->getPeso(),
            'ordem'      => $etapa->getOrdem(),
            'dataFim'    => $etapa->getDataFim()->format('Y-m-d'),
            'dataInicio' => $etapa->getDataInicio()->format('Y-m-d'),
            'tcc'        => $etapa->getTcc()
        ];

        return $app->json($dados);
    }

    public function indexAction(Application $app, Request $request) {
        return $app->redirect('../etapa/listar');
    }

    public function cadastrarAction(Application $app, Request $request) {
        // $db = $app['orm']->getRepository('\SistemaTCC\Model\Campus');
        // $campus = $db->findAll();
		//
        // $dadosParaView = [
        //      'titulo' => 'Cadastrar Semestre',
        //      'values' => [
        //          'campus'   => $campus,
        //          'nome'      => '',
        //          'datainicio'  => '',
        //          'datafim'  => '',
        //     'etapa_tcc1'  => [],
        //     'etapa_tcc2'  => [],
        //      ],
        // ];
        // return $app['twig']->render('semestre/formulario.twig', $dadosParaView);
    }

    public function editarAction(Application $app, Request $request, $id) {
        $dbSem = $app['orm']->getRepository('\SistemaTCC\Model\Semestre');
        $semestre = $dbSem->find($id);

        $dbCampus = $app['orm']->getRepository('\SistemaTCC\Model\Campus');
        $campus = $dbCampus->findAll();

        $dbTipo = $app['orm']->getRepository('\SistemaTCC\Model\EtapaTipo');
        $tipos = $dbTipo->findAll();

        if (!$semestre) {
            return $app->redirect('../semestre/listar');
        }

        $dadosParaView = [
            'titulo' => 'Editar Semestre: ' .$semestre->getNome(),
            'id'     => $id,
            'values' => [
                'campus'        => $campus,
                'campusid'      => $semestre->getCampus()->getId(),
                'nome'          => $semestre->getNome(),
                'datainicio'    => $semestre->getDataInicio()->format('Y-m-d'),
                'datafim'       => $semestre->getDataFim()->format('Y-m-d'),
                'tipos'         => $tipos,
                'etapa_tcc1'    => [],
                'etapa_tcc2'    => []
            ]
        ];
        return $app['twig']->render('semestre/formulario.twig', $dadosParaView);
    }

    public function excluirAction() {
        return 'Excluir Etapa';
    }

    public function listarAction(Application $app) {
     $db = $app['orm']->getRepository('\SistemaTCC\Model\Etapa');
      $query = $app['orm']->createQuery($sql);
       $etapa = $db->findAll();
        $dadosParaView = [
            'titulo' => 'Etapa Listar',
            'etapas' => $etapa,
        ];
        return $app['twig']->render('etapa/listar.twig', $dadosParaView);
    }

}
