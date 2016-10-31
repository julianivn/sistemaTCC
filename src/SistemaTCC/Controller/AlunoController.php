<?php

namespace SistemaTCC\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

class AlunoController {

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
                    'max' => 255,
                    'minMessage' => 'Seu nome precisa possuir pelo menos {{ limit }} caracteres',
                    'maxMessage' => 'Seu nome não deve possuir mais que {{ limit }} caracteres'
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
			'cgu' => [
                new Assert\NotBlank(['message' => 'Preencha esse campo']),
                new Assert\Length([
                     'min' => 0,
                     'max' => 10,
                     'minMessage' => 'Seu CGU precisa possuir pelo menos {{ limit }} caracteres',
                     'maxMessage' => 'Seu CGU não deve possuir mais que {{ limit }} caracteres'
                ])
            ],
			'matricula' => [
                new Assert\NotBlank(['message' => 'Preencha esse campo']),
                new Assert\Length([
                     'min' => 0,
                     'max' => 10,
                     'minMessage' => 'Seu CGU precisa possuir pelo menos {{ limit }} caracteres',
                     'maxMessage' => 'Seu CGU não deve possuir mais que {{ limit }} caracteres'
                ])
            ]

        ];
        $constraint = new Assert\Collection($asserts);
        $errors     = $app['validator']->validate($dados, $constraint);
        $retorno    = [];
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
            'sexo'      => $request->get('sexo'),
			'cgu'		=> $request->get('cgu'),
			'matricula'	=> $request->get('matricula')
        ];

        $errors = $this->validacao($app, $dados);
        if (count($errors) > 0) {
            return $app->json($errors, 400);
        }
        $pessoa = new \SistemaTCC\Model\Pessoa();
        $aluno = new \SistemaTCC\Model\Aluno();

        $pessoa->setNome($request->get('nome'))
               ->setEmail($request->get('email'))
               ->setTelefone(str_replace(array('(',')',' ','-'),'',$request->get('telefone')))
               ->setSexo($request->get('sexo'));

        $aluno->setMatricula($request->get('matricula'))
              ->setCgu($request->get('cgu'))
              ->setPessoa($pessoa);

        try {
            $app['orm']->persist($aluno);
            $app['orm']->flush();
        }
        catch (\Exception $e) {
            return $app->json([$e->getMessage()], 400);
        }
        return $app->json(['success' => 'Aluno cadastrado com sucesso.'], 201);
    }

    public function find(Application $app, Request $request, $id) {

        if (null === $aluno = $app['orm']->find('\SistemaTCC\Model\Aluno', (int) $id))
            return new Response('O aluno não existe.', Response::HTTP_NOT_FOUND);

        return new Response($aluno->getPessoa()->getNome());
    }

    public function edit(Application $app, Request $request, $id) {

        if (null === $aluno = $app['orm']->find('\SistemaTCC\Model\Aluno', (int) $id))
            return new Response('O aluno não existe.', Response::HTTP_NOT_FOUND);

        $pessoa = $aluno->getPessoa();

        $pessoa->setNome($request->get('nome', $pessoa->getNome()))
               ->setEmail($request->get('email', $pessoa->getEmail()))
               ->setTelefone(str_replace(array('(',')',' ','-'),'',$request->get('telefone', $pessoa->getTelefone())))
               ->setSexo($request->get('sexo', $pessoa->getSexo()));

        $aluno->setMatricula($request->get('matricula', $aluno->getMatricula()))
              ->setCgu($request->get('cgu', $aluno->getCgu()));

        try {
            $app['orm']->flush();
        }
        catch (\Exception $e) {
            return new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

         return new Response(json_encode(['success' => 'Aluno cadastrado com sucesso.']), Response::HTTP_OK);
    }

    public function del(Application $app, Request $request, $id) {

        if (null === $aluno = $app['orm']->find('\SistemaTCC\Model\Aluno', (int) $id))
            return new Response('O aluno não existe.', Response::HTTP_NOT_FOUND);

        try {
            $app['orm']->remove($aluno);
            $app['orm']->flush();
        }
        catch (\Exception $e) {
            return new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

		return new Response(json_encode(array('Aluno excluído com sucesso.')), Response::HTTP_OK);
	}

    public function indexAction(Application $app, Request $request) {
        return $app->redirect('../aluno/listar');
    }

    public function cadastrarAction(Application $app, Request $request) {
        $dadosParaView = [
            'titulo' => 'Cadastrar Aluno',
            'values' => [
                'nome'      => '',
                'email'     => '',
                'telefone'  => '',
                'sexo'      => '',
				'cgu'		=> '',
				'matricula'	=> '',
            ],
        ];
        return $app['twig']->render('aluno/formulario.twig', $dadosParaView);
    }

    public function editarAction(Application $app, Request $request, $id) {
        // isso é o cara que pega os dados do banco, mas
        $db = $app['orm']->getRepository('\SistemaTCC\Model\Aluno');
        // o metodo 'find' passando um 'id' retorna um objeto do tipo Aluno
        // $aluno === Aluno.php, usa os metodos get
        $aluno = $db->find($id);
        // se nao existir o aluno, ele retorna null, ai redireciona
        if (!$aluno) {
            return $app->redirect('../aluno/listar');
        }

		$dadosParaView = [
            'titulo' => 'Editando Aluno ' . $id,
			'id'     => $id,
			'values' => [
			'nome'		=> $aluno->getPessoa()->getNome(),
			'telefone'	=> $aluno->getPessoa()->getTelefone(),
			'email'		=> $aluno->getPessoa()->getEmail(),
			'sexo'		=> $aluno->getPessoa()->getSexo(),
			'cgu'		=> $aluno->getCgu(),
			'matricula'	=> $aluno->getMatricula()
		    ],
		];

		return $app['twig']->render('aluno/formulario.twig', $dadosParaView);
    }

    public function excluirAction() {
        return 'Excluir Aluno';
    }

	public function listarAction(Application $app) {
        $db = $app['orm']->getRepository('\SistemaTCC\Model\Aluno');
        $alunos = $db->findAll();
        $dadosParaView = [
            'titulo' => 'Aluno Listar',
            'alunos' => $alunos,
        ];
        return $app['twig']->render('aluno/listar.twig', $dadosParaView);
    }

}
