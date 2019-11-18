$(document).ready(function(){
    /**/
    $("#telaDia").hide();
    $("#telaMes").hide();
    $("#alertaSelect").hide();
    /**/
    
    /*VARIÁVEIS*/
    var select = 0;
    var selected = false;
    
    /*VARIÁVEIS*/
    
    /*FUNÇÕES*/
    function escondeTudo (){
        $("#telaDia").hide();
        $("#telaMes").hide();
        $("#alertaSelect").hide();
    }
    /*FUNÇÕES*/
    
    //Botões inferiores
    $("#btnHome").on('click', function(){
        window.location = "../";
    });
    
    $("#btnRelatorios").on('click', function(){
        window.location = "../../relatorios";
    });
    
    //Inicialização da tela
    $("#alertErro").hide();
    $("#alertCerto").hide();
    $("#btnModalSair").hide();
    var urlProcessos = "../Backend/Core/";
    $("#telaErros").hide();
    
    /*BOTÃO SAIR*/
    $("#btnSair").on('click', function(){
        $("#btnModalSair").click();
    });
    
    //Confirmação sair
    $("#btnConfirmSair").on('click', function(){
        $.post(urlProcessos + "execLogout.php",function (){
            location.reload();
        });
    });
    
    //tela de alertas
    $("#telaErros").on('click', function(){
       $(this).fadeOut(300);
       setTimeout(function(){
           $(this).html('');
       },1000);
    });
    
    $("#selectTipo").change( function(){
        escondeTudo();
        select = $("#selectTipo").val();
        if(select != 0){
            
            //
            if(select == 1){ //Diário
                //mostra a tela de dia
                $("#telaDia").show();
            }else if(select == 2){ //Mensal
                $("#telaMes").show();
            }else if(select == 3){ //Semanal
                $("#telaDia").show();
            }else{
                
            }
        }
    });
    
    //
    //SELEÇÃO DOS DADOS
    $("#selectMes").change(function(){
        selected = true;
        $("#alertaSelect").hide();
    });
    $("#selectDia").change(function(){
        selected = true;
        $("#alertaSelect").hide();
    });
    $("#sensoresSelect").change(function(){
        selected = true;
        $("#alertaSelect").hide();
    });
    
    //Ação do botão
    $("#btnGerar").on('click', function(){
        $(this).attr('disabled', 'disabled');
        $(this).html('<img src="../../Backend/Images/loader.gif" class="" alt="loader" width="25px">');
        //verifica se foi tudo selecionado
        if(selected){
            var sensor = $("#sensoresSelect").val();
            var dia = $("#selectDia").val();
            var mes = $("#selectMes").val();
            var tipo = $("#selectTipo").val();
            
            if(sensor != 0){
                if(tipo == 1){
                    //diários
                    $.ajax({
                        url: urlProcessos + 'geraRelatorios.php',
                        data: {tipo : tipo, dia : dia, sensor : sensor},
                        type: 'POST',
                        success: function (data) {
                            if(data != ""){
                                $("#telaErros").html(data);
                                $('#telaErros').show();
                                $("#btnGerar").html('Gerar Dados');
                                $("#btnGerar").removeAttr("disabled");
                            }else{
                                //atualiza os relatórios
                                atualizaRelatorios();
                                $("#btnGerar").html('Gerar Dados');
                                $("#btnGerar").removeAttr("disabled");
                            }
                        },
                        error: function () {
                            alert("Erro");
                        }
                    });
                }else if(tipo == 2){
                    //mensal
                    $.ajax({
                        url: urlProcessos + 'geraRelatorios.php',
                        data: {tipo : tipo, mes : mes, sensor : sensor},
                        type: 'POST',
                        success: function (data) {
                            if(data != ""){
                                $("#telaErros").html(data);
                                $('#telaErros').show();
                                $("#btnGerar").html('Gerar Dados');
                                $("#btnGerar").removeAttr("disabled");
                            }else{
                                //atualiza os relatórios
                                atualizaRelatorios();
                                $("#btnGerar").html('Gerar Dados');
                                $("#btnGerar").removeAttr("disabled");
                            }
                        },
                        error: function () {
                            alert("Erro");
                        }
                    });

                } else if(tipo == 3){
                    //semanal
                    $.ajax({
                        url: urlProcessos + 'geraRelatorios.php',
                        data: {tipo : tipo, dia : dia, sensor : sensor},
                        type: 'POST',
                        success: function (data) {
                            console.log(data);
                        },
                        error: function () {
                            alert("Erro");
                        }
                    });
                }
            }else{
                $("#alertaSelect").html("Por Favor, Selecione o Sensor!");
                $("#alertaSelect").show();
                $("#btnGerar").html('Gerar Dados');
                $("#btnGerar").removeAttr("disabled");
            }
            
            //
            
        }else{
            $("#alertaSelect").html("Por Favor, Selecione os Dados!");
            $("#alertaSelect").show();
            $("#btnGerar").html('Gerar Dados');
            $("#btnGerar").removeAttr("disabled");
        }
    });
    
    
    /*ATUALIZA OS RELATÓRIOS*/
    function atualizaRelatorios(){
        $.ajax({
            url: urlProcessos + 'atualizaRelatorios.php',
            data: {},
            type: 'POST',
            success: function (data) {
                $("#relatoriosatu").html(data);
            },
            error: function () {
                
            }
        });
    }
    
    $("#relatoriosatu").delegate(".card #excluir", 'click', function(){
        var id = $(this).attr("iden");
        var link = $(this).attr("lang");
        $.ajax({
            url: urlProcessos + 'excluirRelatorio.php',
            data: {id : id, link : link},
            type: 'POST',
            success: function () {
                atualizaRelatorios();
            },
            error: function () {
                
            }
        });
    });
    
    //Enviar email
    $("#msg").hide();
    $("#relatoriosatu").delegate(".card #sendEmail", 'click', function(){
        var id = $(this).attr("iden");
        var link = $(this).attr("lang");
        $.ajax({
            url: urlProcessos + 'enviaEmail.php',
            data: {id : id, link : link},
            type: 'POST',
            success: function () {
                $("#msg").fadeIn(300);
                setInterval(function(){
                    $("#msg").fadeOut(300);
                },4000);
            },
            error: function () {
                
            }
        });
    });
    
    
    
    
});