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
                new Assert\Type([
                        'type'    => 'integer',
                        'message' => 'O valor {{ value }} não é um {{ type }} válido.',
                    ]),
            ],
            'campus' => [
                new Assert\NotBlank(['message' => 'Preencha esse campo']),
                new Assert\Type([
                        'type'    => 'integer',
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
            'tipo'       => (int)$request->get('tipo'),
            'campus'     => (int)$request->get('campus'),
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
            return new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return 'Semestre criado com ID #' . $semestre->getId();
    }

    public function edit(Application $app, Request $request, $id) {

        $semestre = $app['orm']->find('\\SistemaTCC\\Model\\Semestre', $request->get('id'));
        if (!$semestre) {
          return $app->json(['semestre' => 'Não existe semestre cadastrado'], 400);
        }
          $campus = $app['orm']->find('\\SistemaTCC\\Model\\Campus', $request->get('campus'));
        if (!$campus) {
            return $app->json(['campus' => 'Não existe campus cadastrado'], 400);
        }

        $errors = $this->validacao($app, $dados);
        if (count($errors) > 0) {
            return $app->json($errors, 400);
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
            return new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new Response('Semestre editado com sucesso.', Response::HTTP_OK);
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
        return new Response('Semestre excluído com sucesso.', Response::HTTP_OK);
    }

    public function find(Application $app, Request $request, $id) {

        if (null === $semestre = $app['orm']->find('\SistemaTCC\Model\Semestre', (int) $id))
            return new Response('O semestre não existe.', Response::HTTP_NOT_FOUND);

        return new Response($semestre->getNome());
    }

    public function indexAction() {
        return 'Index Semestre';
    }

    public function cadastrarAction() {
        return 'Cadastrar Semestre';
    }

    public function editarAction() {
        return 'Editar Semestre';
    }

    public function excluirAction() {
        return 'Excluir Semestre';
    }

    public function listarAction() {
        return 'Listar Semestre';
    }

}