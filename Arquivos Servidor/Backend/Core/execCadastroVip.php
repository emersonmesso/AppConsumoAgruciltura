<?php
require "../Config/Controller.php";
$mysql = new Controller();

//recebendo os dados
$nome = $_POST['nome'];
$idade = $_POST['idade'];
$sexo = $_POST['sexo'];
$usuario = $_POST['usuario'];
$email = $_POST['email'];
$senha = $_POST['senha'];

//verificando se não existe usuários cadastrados com email e usuário
$sql = $mysql->select("usuarios", "*", "usuario = '$usuario' OR email = '$email'");
if(mysqli_num_rows($sql) == 0){
    
}else{
    echo 0;
}