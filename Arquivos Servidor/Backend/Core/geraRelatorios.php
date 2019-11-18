<?php
require "../Config/Controller.php";
$mysql = new Controller();
$tipo = $_POST['tipo'];

//Mostrando todos os erro do PHP
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

if($tipo == 1){
    
    if(strtotime($_POST['dia']) > strtotime(date("Y-m-d"))){
        echo 'Não é possível Selecionar Datas Futuras!';
        exit;
    }else{
        //relatório diário
        $mysql->relatorioDiario($_POST['sensor'], $_POST['dia']);
    }
    
    
}

if($tipo == 2){
    
    $data = new DateTime( $_POST['mes'] );
    $hoje = new DateTime( 'now' );
    
    if($data->format("Y-m-d") > $hoje->format("Y-m-d")){
        echo 'Não é possível Selecionar Datas Futuras!';
        exit;
    }else{
        //relatório Mensal
        $mysql->relatorioMensal($_POST['mes'], $_POST['sensor']);
    }
    
}