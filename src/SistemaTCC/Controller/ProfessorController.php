<?php

namespace SistemaTCC\Controller;

use Silex\Application;

class ProfessorController {

    public function indexAction() {
        return 'Index Professor';
    }

    public function cadastrarAction() {
        return 'Cadastrar Professor';
    }

    public function editarAction() {
        return 'Editar Professor';
    }

    public function excluirAction() {
        return 'Excluir Professor';
    }

    public function listarAction() {
        return 'Listar Professor';
    }

}
