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

        $app['orm']->persist($semestre);
        $app['orm']->flush();

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

        $app['orm']->flush();

        return 'Semestre alterado';
    }

    public function del(Application $app, Request $request, $id) {

        $semestre = new Semestre();
        $semestre = $app['orm']->find('\\SistemaTCC\\Model\\Semestre', $request->get('id'));

        $app['orm']->remove($semestre);
        $app['orm']->flush();

        return 'Semestre excluído';
    }

    public function find(Application $app, Request $request, $id) {

        $semestre = new Semestre();
        $campus = $app['orm']->find('\\SistemaTCC\\Model\\Campus', $request->get('campus')); // Gravataí

        $semestre->setNome($request->get('nome'));
        $semestre->setDataInicio(new DateTime($request->get('dataInicio')));
        $semestre->setDataFim(new DateTime($request->get('dataFim')));
        $semestre->setTipo($request->get('tipo'));
        $semestre->setCampus($campus);
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
