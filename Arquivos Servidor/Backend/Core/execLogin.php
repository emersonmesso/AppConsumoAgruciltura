<?php
require "../Config/Controller.php";
$mysql = new Controller();

$dados = array();
//recebendo os dados
$email = $_POST['email'];
$senha = $_POST['senha'];
$nSenha = md5($senha);

$sql = $mysql->select("users", "*", "email = '$email' AND senha = '$nSenha'");
if(mysqli_num_rows($sql) > 0){
    
    //cria a sessão
    if(!session_start()){
        session_start();
    }
    $_SESSION['login'] = $email;
    $_SESSION['password'] = $nSenha;
    
    $dados['erro'] = false;
    
}else{
    $dados['erro'] = true;
    $dados['msg'] = "Email ou senha não confere!";
}
echo json_encode($dados);