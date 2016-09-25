<?php

namespace SistemaTCC\Model;

use DateTime;
use SistemaTCC\Model\EtapaTipo;
use SistemaTCC\Model\Semestre;

class Etapa {

    private $id;
    private $nome;
    private $dataInicio;
    private $dataFim;
    private $peso;
    private $ordem;
    private $enviarEmailAdministrador;
    private $enviarEmailBanca;
    private $enviarEmailOrientador;
    private $semestre;
    private $etapaTipo;

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

    public function getPeso() {
        return $this->peso;
    }

    public function setPeso($peso) {
        $this->peso = $peso;
    }

    public function getOrdem() {
        return $this->ordem;
    }

    public function setOrdem($ordem) {
        $this->ordem = $ordem;
    }

    public function getEnviaEmailAdministrador() {
        return $this->enviarEmailAdministrador;
    }

    public function setEnviaEmailAdministrador($flag) {
        $this->enviarEmailAdministrador = (boolean) $flag;
    }

    public function getEnviaEmailBanca() {
        return $this->enviarEmailBanca;
    }

    public function setEnviaEmailBanca($flag) {
        $this->enviarEmailBanca = (boolean) $flag;
    }

    public function getEnviaEmailOrientador() {
        return $this->enviarEmailOrientador;
    }

    public function setEnviaEmailOrientador($flag) {
        $this->enviarEmailOrientador = (boolean) $flag;
    }

    public function getSemestre() {
        return $this->semestre;
    }

    public function setSemestre(Semestre $semestre) {
        $this->semestre = $semestre;
    }

    public function getEtapaTipo() {
        return $this->dataFim;
    }

    public function setEtapaTipo(EtapaTipo $tipo) {
        $this->etapaTipo = $tipo;
    }

}
