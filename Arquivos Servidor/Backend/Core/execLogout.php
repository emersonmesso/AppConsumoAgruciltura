<?php
if(!session_start()){
    session_start();
}
//apaga a sessão
session_unset($_SESSION);