<?php
require "../Config/Controller.php";
$mysql = new Controller();

$dados['dados'] = $mysql->graficoBorda();
echo json_encode($dados, true);