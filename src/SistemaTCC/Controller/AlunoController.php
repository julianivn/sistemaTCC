<?php

namespace SistemaTCC\Controller;

use Silex\Application;

class AlunoController {

    public function indexAction() {
        return 'Index Aluno';
    }

    public function cadastrarAction() {
        return 'Cadastrar Aluno';
    }

    public function editarAction() {
        return 'Editar Aluno';
    }

    public function excluirAction() {
        return 'Excluir Aluno';
    }

    public function listarAction() {
        return 'Listar Aluno';
    }

}
