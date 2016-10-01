<?php

namespace SistemaTCC\Controller;

use DateTime;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use SistemaTCC\Model\Campus;
use SistemaTCC\Model\Semestre;

class SemestreController {

    public function add(Application $app, Request $request) {

        $semestre = new Semestre();
        $campus = $app['orm']->find('\\SistemaTCC\\Model\\Campus', $request->get('campus'));

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
        $campus = $app['orm']->find('\\SistemaTCC\\Model\\Campus', $request->get('campus'));

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

        $semestre = new Semestre();
        $semestre = $app['orm']->find('\\SistemaTCC\\Model\\Semestre', $request->get('id'));

        try {
            $app['orm']->remove($semestre);
            $app['orm']->flush();
        }
        catch (\Exception $e) {
            return new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
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

    public function cadastrarAction(Application $app) {
        return $app['twig']->render('semestre/cadastrar.twig');
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
