<?php

namespace SistemaTCC\Model;

use DateTime;
use SistemaTCC\Model\Campus;

class Semestre {

    private $id;
    private $nome;
    private $dataInicio;
    private $dataFim;
    private $tipo;
    private $campus;

    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getDataInicio() {
        return $this->dataInicio;
    }

    public function setDataInicio(DateTime $data) {
        $this->dataInicio = $data;
    }

    public function getDataFim() {
        return $this->dataFim;
    }

    public function setDataFim(DateTime $data) {
        $this->dataFim = $data;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function getCampus() {
        return $this->campus;
    }

    public function setCampus(Campus $campus) {
        $this->campus = $campus;
    }
}
