<?php
//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);
require "../../Config/Controller.php";
$Controller = new Controller();
$dados = array();
$id = $_GET['id'];

//retorna meus sensores
$dados['sensores'] = $Controller->getSensores($id);

//retorno
echo json_encode($dados);