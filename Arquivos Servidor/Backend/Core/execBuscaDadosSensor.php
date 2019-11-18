<?php
require "../Config/Controller.php";
$mysql = new Controller();
$mysql->buscaDados();

$id = $_POST['id'];

$dados = array();

//buscando os litros do mes atual
$dados['mesAtual'] = $mysql->dadosGraficoMesSensor($id);
$dados['diaAtual'] = $mysql->dadosGraficoDiaSensor($id);

echo json_encode($dados);