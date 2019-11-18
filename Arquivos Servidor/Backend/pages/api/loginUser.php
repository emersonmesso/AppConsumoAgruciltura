<?php
require "../../Config/Controller.php";
$mysql = new Controller();
$dados = array();
$email = $_GET['email'];
$senha = md5($_GET['senha']);

$sql = $mysql->select("users", "*", "email = '$email' AND senha = '$senha'");
if(mysqli_num_rows($sql) > 0){
    
    $dados['result'] = array(
        'login' => true,
        'msg' =>'',
        'id' => $mysql->getIDUser($sql)
    );
}else{
    $dados['result'] = array(
        'login' => false,
        'msg' =>'Verifique os dados informados!'
    );
}
echo json_encode($dados);