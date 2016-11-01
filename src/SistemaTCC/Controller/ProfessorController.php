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
                    'pattern' => '/^[a-zA-ZÀ-ú]+?[a-zA-ZÀ-ú ]+$/i',
                    'message' => 'Seu nome deve possuir apenas letras'
                ]),
                new Assert\Length([
                    'min' => 3,
                    'max' => 255,
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
				new Assert\Regex([
					'pattern' => '/^[0-9]+$/i',
					'message' => 'Seu telefone deve possuir apenas números'
				]),
                new Assert\Length([
                    'min' => 10,
                    'max' => 12,
                    'minMessage' => 'Informe no mínimo {{ limit }} números',
                    'maxMessage' => 'Informe no máximo {{ limit }} números',
                ])
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
            'telefone'  => str_replace(array('(',')',' ','-'),'',$request->get('telefone')),
            'sexo'      => $request->get('sexo')
        ];

        $errors = $this->validacao($app, $dados);
        if (count($errors) > 0) {
            return $app->json($errors, 400);
        }

        $pessoa = new \SistemaTCC\Model\Pessoa();
        $professor = new \SistemaTCC\Model\Professor();

        $pessoa->setNome($request->get('nome'))
               ->setEmail($request->get('email'))
               ->setTelefone(str_replace(array('(',')',' ','-'),'',$request->get('telefone')))
               ->setSexo($request->get('sexo'));

        $professor->setPessoa($pessoa);

        try {
            $app['orm']->persist($professor);
            $app['orm']->flush();
        }
        catch (\Exception $e) {
            return $app->json([$e->getMessage()], 400);
        }
        return $app->json(['success' => 'Professor cadastrado com sucesso.'], 201);
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
            'telefone'  => str_replace(array('(',')',' ','-'),'',$request->get('telefone')),
            'sexo'      => $request->get('sexo')
        ];
        $errors = $this->validacao($app, $dados);
        if (count($errors) > 0) {
            return $app->json($errors, 400);
        }

        $pessoa = $professor->getPessoa();

        $pessoa->setNome($request->get('nome', $pessoa->getNome()))
               ->setEmail($request->get('email', $pessoa->getEmail()))
               ->setTelefone(str_replace(array('(',')',' ','-'),'',$request->get('telefone', $pessoa->getTelefone())))
               ->setSexo($request->get('sexo', $pessoa->getSexo()));

        try {
            $app['orm']->flush();
        }
        catch (\Exception $e) {
            return $app->json([$e->getMessage()], 400);
        }
        return $app->json(['success' => 'Professor editado com sucesso.']);
    }

    public function del(Application $app, Request $request, $id) {

        if (null === $professor = $app['orm']->find('\SistemaTCC\Model\Professor', (int) $id))
            return $app->json([ 'error' => 'O professor não existe.'], 400);
        try {
            $app['orm']->remove($professor);
            $app['orm']->flush();
        }
        catch (\Exception $e) {
            return $app->json([$e->getMessage()], 400);
        }
        return $app->json(['success' => 'Professor excluído com sucesso.']);
    }

    public function indexAction(Application $app, Request $request) {
        return $app->redirect('../professor/listar');
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
            return $app->redirect('../professor/listar');
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
