<?php
require "../Config/Controller.php";
$mysql = new Controller();


//apaga o arquivo
if(unlink($_POST['link'])){
    $sql = $mysql->delete("relatorios", "id = '".$_POST['id']."'");
    if($sql){
        
    }else{
        echo "Não foi possível Apagar";
    }
}else{
    echo "Não foi possível Apagar";
}