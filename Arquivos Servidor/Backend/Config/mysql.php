<?php
class mysql{
    public $sql;
    public function conecta(){
        $host = "localhost";
        $user = "padraoto_paempi";
        $pass = "sistemapaempi";
        $banco = "padraoto_paempi";
        //
        $this->sql = new mysqli($host, $user, $pass, $banco);
        //
    }
}