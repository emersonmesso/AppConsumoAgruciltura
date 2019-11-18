<?php
require "../Config/Controller.php";
$mysql = new Controller();
if(!isset($_GET['valor'])){
    echo 'Erro';
    exit;
}

$valor = $_GET['valor'];
$sensor = $_GET['id_sensor'];
$dia = date("d");
$mes = date("m");
$ano = date("Y");
$horas = date("H:i:s");

if($valor != 0){
    $mysql->insere("info_sensor", "id_sensor, valor, dia, mes, ano, horas", "$sensor, '$valor', '$dia', '$mes', '$ano', '$horas'");
    $mysql->insere("info_sensor", "id_sensor, valor, dia, mes, ano, horas", "3, '$valor', '$dia', '$mes', '$ano', '$horas'");
    echo 'Adicionado com sucesso!';
}

