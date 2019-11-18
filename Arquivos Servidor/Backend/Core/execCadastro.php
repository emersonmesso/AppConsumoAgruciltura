<?php
require "../Config/Controller.php";
$mysql = new Controller();

$dados = array();

//recebendo os dados
$nome = $_POST['nome'];
$email = $_POST['email'];
$pessoas = $_POST['pessoas'];
$senha = $_POST['senha'];
$nSenha = md5($senha);

//verificando se não existe usuário cadastrado com o email
$sql_email = $mysql->select("users", "*", "email = '$email'");
if(mysqli_num_rows($sql_email) > 0){
    echo  "erro";
}else{
    //faz o cadastro do usuário
    $sql = $mysql->insere("users", "nome, email, pessoas, senha", "'$nome', '$email', '$pessoas', '$nSenha'");
    echo  "";
}