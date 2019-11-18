<?php
require "../Config/Controller.php";
$mysql = new Controller();
$id = $_POST['idSensor'];

$dados = $mysql->mesesDiferentes($id);

echo json_encode($dados);
