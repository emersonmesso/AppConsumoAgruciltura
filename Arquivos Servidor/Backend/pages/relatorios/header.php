<?php include "Backend/Config/config.php";
//verifica login
$mysql->verLogin();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="shortcut icon" type="image/x-icon" href="Backend/Images/ico.ico">
        <title>Meu Aplicativo</title>
        <link href="../Backend/Styles/reset.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
    </head>
    <body style="background-color: #DFDCDC;">
        <!--MENU CIMA-->
        <div class="container-fluid bg-light" id="menuCima">
            <div class="p-3">
                <div class="row">

                    <div class="col-6 text-left font-weight-bold text-info">
                        <b class="">Smart Water Solution</b>
                    </div>

                    <div class="col-6 text-right">
                        <div class="row">
                            <div class="col-4">

                            </div>
                            <div class="col-4">
                                <button type="button" class="btn btn-light text-info"><i class="far fa-user"></i></button>
                            </div>
                            <div class="col-4 text-right">
                                <button type="button" id="btnSair" data-toggle="modal" data-target="#exampleModal" class="btn btn-light text-info"><i class="fas fa-sign-out-alt"></i></button>
                            </div>
                        </div>


                    </div>

                </div>
            </div>
    
    
        </div>
<!--MENU CIMA-->