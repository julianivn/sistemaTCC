<?php

namespace SistemaTCC\Controller;

use DateTime;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use SistemaTCC\Model\Campus;
use SistemaTCC\Model\Semestre;
use Symfony\Component\Validator\Constraints as Assert;

class SemestreController {

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
            'dataInicio' => [
                new Assert\NotBlank(['message' => 'Preencha esse campo']),
                new Assert\Date(['message' => 'Preencha a data']),
            ],
            'dataFim' => [
                new Assert\NotBlank(['message' => 'Preencha esse campo']),
                new Assert\Date(['message' => 'Preencha a data']),
            ],
            'tipo' => [
                new Assert\NotBlank(['message' => 'Preencha esse campo']),
                new Assert\Regex([
                    'pattern' => '/^[1|2]$/',
                    'message' => 'Não é um tipo válido'
                ]),
            ],
            'campus' => [
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
            'dataInicio' => $request->get('dataInicio'),
            'dataFim'    => $request->get('dataFim'),
            'tipo'       => $request->get('tipo'),
            'campus'     => $request->get('campus'),
        ];

        $errors = $this->validacao($app, $dados);
        if (count($errors) > 0) {
            return $app->json($errors, 400);
        }

        $semestre = new Semestre();
        $campus = $app['orm']->find('\\SistemaTCC\\Model\\Campus', $request->get('campus'));

        if (!$campus) {
            return $app->json(['campus' => 'Não existe campus cadastrado'], 400);
        }

        $semestre->setNome($request->get('nome'));
        $semestre->setDataInicio(new DateTime($request->get('dataInicio')));
        $semestre->setDataFim(new DateTime($request->get('dataFim')));
        $semestre->setTipo($request->get('tipo'));
        $semestre->setCampus($campus);


        try {
            $app['orm']->persist($semestre);
            $app['orm']->flush();
        }
        catch (\Exception $e) {
            return $app->json(['semestre' => $e->getMessage()], 400);
        }

        return $app->json(['semestre' => 'Semestre criado com sucesso']);
    }

    public function edit(Application $app, Request $request, $id) {

        $semestre = $app['orm']->find('\\SistemaTCC\\Model\\Semestre', $id);
        if (!$semestre) {
          return $app->json(['semestre' => 'Não existe semestre cadastrado'], 400);
        }

        $dados = [
            'nome'       => $request->get('nome'),
            'dataInicio' => $request->get('dataInicio'),
            'dataFim'    => $request->get('dataFim'),
            'tipo'       => $request->get('tipo'),
            'campus'     => $request->get('campus'),
        ];

        $errors = $this->validacao($app, $dados);
        if (count($errors) > 0) {
            return $app->json($errors, 400);
        }

        $campus = $app['orm']->find('\\SistemaTCC\\Model\\Campus', $request->get('campus'));
        if (!$campus) {
            return $app->json(['campus' => 'Não existe campus cadastrado'], 400);
        }

        $semestre->setNome($request->get('nome'));
        $semestre->setDataInicio(new DateTime($request->get('dataInicio')));
        $semestre->setDataFim(new DateTime($request->get('dataFim')));
        $semestre->setTipo($request->get('tipo'));
        $semestre->setCampus($campus);

        try {
            $app['orm']->flush();
        }
        catch (\Exception $e) {
            return $app->json(['semestre' => $e->getMessage()], 400);
        }
        return $app->json(['semestre' => 'Semestre alterado com sucesso']);
    }

    public function del(Application $app, Request $request, $id) {

        $semestre = $app['orm']->find('\\SistemaTCC\\Model\\Semestre', $id);
        if (!$semestre) {
            return $app->json(['semestre' => 'Não existe semestre cadastrado'], 400);
        }

        try {
            $app['orm']->remove($semestre);
            $app['orm']->flush();
        }
        catch (\Exception $e) {
          return $app->json(['semestre' => $e->getMessage()], 400);
        }
        return $app->json(['semestre' => 'Semestre excluido com sucesso']);
    }

    public function find(Application $app, Request $request, $id) {

        if (null === $semestre = $app['orm']->find('\SistemaTCC\Model\Semestre', (int) $id))
            return new Response('O semestre não existe.', Response::HTTP_NOT_FOUND);

        return new Response($semestre->getNome());
    }

    public function indexAction() {
        return 'Index Semestre';
    }

    public function cadastrarAction(Application $app) {
        $dadosParaView = [
             'titulo' => 'Cadastrar Semestre',
             'values' => [
                 'campus'   => '',
                 'ano'      => '',
                 'semestre'  => '',
            'etapa_tcc1'  => [],
            'etapa_tcc2'  => [],
             ],
        ];
        return $app['twig']->render('semestre/formulario.twig', $dadosParaView);
    }

    public function editarAction(Application $app) {
      $dadosParaView = [
            'titulo' => 'Editar Semestre',
            'id'     => '111',
            'values' => [
                'campus'    => 'Gravataí',
                'ano'       => '2016',
                'semestre'  => '2',
            'etapa_tcc1'  => [
                ['id' => '1', 'nome' => 'Etapa11'],
                ['id' => '2', 'nome' => 'Etapa22'],
                ['id' => '3', 'nome' => 'Etapa33'],
                ['id' => '4', 'nome' => 'Etapa44']
            ],
            'etapa_tcc2'  => [
                ['id' => '4', 'nome' => 'Etapa44'],
                ['id' => '5', 'nome' => 'Etapa55'],
                ['id' => '6', 'nome' => 'Etapa66']
            ],
          ],
        ];
        return $app['twig']->render('semestre/formulario.twig', $dadosParaView);
    }

    public function excluirAction() {
        return 'Excluir Semestre';
    }

    public function listarAction(Application $app) {
      $sql = 'SELECT a.nome, a.dataInicio, a.dataFim, a.tipo, a.campus FROM \SistemaTCC\Model\Semestre a JOIN a.semestre p';
      $query = $app['orm']->createQuery($sql);
      $alunos = $query->getResult();
      return $app['twig']->render('semestre/listar.twig', array('semestre' => $semestres));
    }

  }
}
