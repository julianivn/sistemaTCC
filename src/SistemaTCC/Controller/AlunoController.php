<?php

namespace SistemaTCC\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        'matricula' => [
          new Assert\NotBlank(['message' => 'Preencha esse campo']),
        ],
        'cgu' => [
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

        $pessoa = new \SistemaTCC\Model\Pessoa();
        $aluno = new \SistemaTCC\Model\Aluno();

        $pessoa->setNome($request->get('nome'))
               ->setEmail($request->get('email'))
               ->setTelefone($request->get('telefone'))
               ->setSexo($request->get('sexo'));

        $aluno->setMatricula($request->get('matricula'))
              ->setCgu($request->get('cgu'))
              ->setPessoa($pessoa);

        try {
            $app['orm']->persist($aluno);
            $app['orm']->flush();
        }
        catch (\Exception $e) {
            return new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new Response('Aluno cadastrado com sucesso.', Response::HTTP_CREATED);
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
               ->setTelefone($request->get('telefone', $pessoa->getTelefone()))
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

    public function indexAction() {
        return 'Index Aluno';
    }

    public function cadastrarAction(Application $app) {
        $dadosParaView = [
          'titulo' => 'Cadastrar Aluno',
          'values' => [
              'nome' => '',
              'email' => '',
              'telefone' => '',
              'sexo' => '',
              'matricula' => '',
              'cgu' => '',
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
            return $app->redirect('/aluno/listar');
        }

		$dadosParaView = [
			'id'=>$id,
			'values' => [
			'nome'		=> $aluno->getPessoa()->getNome(),
			'telefone'	=> $aluno->getPessoa()->getTelefone(),
			'email'		=> $aluno->getPessoa()->getEmail(),
			'sexo'		=> $aluno->getPessoa()->getSexo(),
			'cgu'		=> $aluno->getCgu(),
			'matricula'	=> $aluno->getMatricula()
		      ],
		];


		return $app['twig']->render('aluno/editar.twig', $dadosParaView);
    }

    public function excluirAction() {
        return 'Excluir Aluno';
    }

	public function listarAction(Application $app) {
		$sql = 'SELECT a.id, a.matricula, p.nome FROM \SistemaTCC\Model\Aluno a JOIN a.pessoa p';
		$query = $app['orm']->createQuery($sql);
		$alunos = $query->getResult();
		return $app['twig']->render('aluno/listar.twig', array('alunos' => $alunos));
	}

}
