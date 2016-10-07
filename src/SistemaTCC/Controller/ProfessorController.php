<?php

namespace SistemaTCC\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

class ProfessorController {

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
            'email' => [
                new Assert\NotBlank(['message' => 'Preencha esse campo']),
                new Assert\Email([
                    'message' => 'Esse e-mail é inválido',
                ])
            ],
            'telefone' => [
                new Assert\NotBlank(['message' => 'Preencha esse campo']),
            ],
            'sexo' => [
                new Assert\NotBlank(['message' => 'Preencha esse campo']),
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
            'nome'      => $request->get('nome'),
            'email'     => $request->get('email'),
            'telefone'  => $request->get('telefone'),
            'sexo'      => $request->get('sexo')
        ];

        $errors = $this->validacao($app, $dados);
        if (count($errors) > 0) {
            return new Response(json_encode($errors), Response::HTTP_BAD_REQUEST);
        }

        $pessoa = new \SistemaTCC\Model\Pessoa();
        $professor = new \SistemaTCC\Model\Professor();

        $pessoa->setNome($request->get('nome'))
               ->setEmail($request->get('email'))
               ->setTelefone($request->get('telefone'))
               ->setSexo($request->get('sexo'));

        $professor->setPessoa($pessoa);

        try {
            $app['orm']->persist($professor);
            $app['orm']->flush();
        }
        catch (\Exception $e) {
            return new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new Response(json_encode(['success' => 'Professor cadastrado com sucesso.']), Response::HTTP_CREATED);
    }

    public function find(Application $app, Request $request, $id) {
        $professor = $app['orm']->find('\SistemaTCC\Model\Professor', (int) $id);
        if (null === $professor) {
            return new Response('O professor não existe.', Response::HTTP_NOT_FOUND);
        }
        return new Response($professor->getPessoa()->getNome());
    }

    public function edit(Application $app, Request $request, $id) {

        if (null === $professor = $app['orm']->find('\SistemaTCC\Model\Professor', (int) $id))
            return new Response('O professor não existe.', Response::HTTP_NOT_FOUND);

        $dados = [
            'nome'      => $request->get('nome'),
            'email'     => $request->get('email'),
            'telefone'  => $request->get('telefone'),
            'sexo'      => $request->get('sexo')
        ];
        $errors = $this->validacao($app, $dados);
        if (count($errors) > 0) {
            return new Response(json_encode($errors), Response::HTTP_BAD_REQUEST);
        }

        $pessoa = $professor->getPessoa();

        $pessoa->setNome($request->get('nome', $pessoa->getNome()))
               ->setEmail($request->get('email', $pessoa->getEmail()))
               ->setTelefone($request->get('telefone', $pessoa->getTelefone()))
               ->setSexo($request->get('sexo', $pessoa->getSexo()));

        try {
            $app['orm']->flush();
        }
        catch (\Exception $e) {
            return new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new Response(json_encode(['success' => 'Professor editado com sucesso.']), Response::HTTP_CREATED);
    }

    public function del(Application $app, Request $request, $id) {

        if (null === $professor = $app['orm']->find('\SistemaTCC\Model\Professor', (int) $id))
            return new Response(json_encode([ 'error' => 'O professor não existe.']), Response::HTTP_NOT_FOUND);

        try {
            $app['orm']->remove($professor);
            $app['orm']->flush();
        }
        catch (\Exception $e) {
            return new Response(json_encode([$e->getMessage()]), Response::HTTP_BAD_REQUEST);
        }

        return new Response(json_encode(['success' => 'Professor excluído com sucesso.']), Response::HTTP_OK);
    }

    public function indexAction(Application $app, Request $request) {
        return $app->redirect('/professor/listar');
    }

    public function cadastrarAction(Application $app, Request $request) {
        $dadosParaView = [
            'titulo' => 'Cadastrar Professor',
            'values' => [
                'nome'      => '',
                'email'     => '',
                'telefone'  => '',
                'sexo'      => '',
            ],
        ];
        return $app['twig']->render('professor/formulario.twig', $dadosParaView);
    }

    public function editarAction(Application $app, Request $request, $id) {
        $db = $app['orm']->getRepository('\SistemaTCC\Model\Professor');
        $professor = $db->find($id);
        if (!$professor) {
            return $app->redirect('/professor/listar');
        }
        $dadosParaView = [
            'titulo' => 'Alterando Professor: ' . $professor->getPessoa()->getNome(),
            'id' => $id,
            'values' => [
                'nome'      => $professor->getPessoa()->getNome(),
                'email'     => $professor->getPessoa()->getEmail(),
                'telefone'  => $professor->getPessoa()->getTelefone(),
                'sexo'      => $professor->getPessoa()->getSexo(),
            ],
        ];
        return $app['twig']->render('professor/formulario.twig', $dadosParaView);
    }

    public function excluirAction() {
        return 'Excluir Professor';
    }

    public function listarAction(Application $app, Request $request) {
        // return '1223';
        $db = $app['orm']->getRepository('\SistemaTCC\Model\Professor');
        $professores = $db->findAll();
        $dadosParaView = [
            'titulo' => 'Professor Listar',
            'professores' => $professores,
        ];
        return $app['twig']->render('professor/listar.twig', $dadosParaView);
    }

}
