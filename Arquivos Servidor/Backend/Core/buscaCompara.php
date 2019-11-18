<?php
require "../Config/Controller.php";
$mysql = new Controller();

$dados = array();
//recebe o mes
$dado = $_POST['mes'];
$idSensor = $_POST['sensor'];

//verifica o mês, se for igual a 13, compara o mes atual com todos os meses do ano
//Se for menor que 13, compara com o valor do mes ao atual
$mes = explode(":", $dado);


if($mes[0] != 13){
    $dados = $mysql->geraDadosComparaMes($dado, $idSensor);
    
}else{
    //compara com todos os meses do ano
    $dados = $mysql->gerDadosCamparaMesAno($idSensor);
}

echo json_encode($dados);