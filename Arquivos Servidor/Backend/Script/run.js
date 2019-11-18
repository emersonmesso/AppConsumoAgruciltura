$(document).ready(function(){
    $("#btnModalSair").hide();
    var urlProcessos = "../Backend/Core/";
    $("#telaErros").hide();
    $("#imgLoader").hide();
    $("#telaSensor").hide();
    $("#telaComparacao").hide();
    
    
    
    /*Formulário de cadastro*/
    $("#formCdastro").submit(function(){
        $("#alertErro").hide();
        $("#alertCerto").hide();
        $("#btnSubmit").attr('disabled', 'disabled');
        
        //recebendo os dados
        var email = $("#campoEmail").val();
        var senha = $("#campoSenha").val();
        var pessoas = $("#campoPessoas").val();
        var nome = $("#campoNome").val();
        
        if(email == "" || nome == "" || senha == "" || pessoas == ""){
            $("#alertErro").html("Dados incompletos!<br />Verifique suas informações");
            $("#alertErro").fadeIn(200);
            $("#btnSubmit").removeAttr('disabled');
        }else{
            
            //envia os dados
            $.ajax({
                url : urlProcessos + "execCadastro.php",
                data : {nome : nome, email : email, pessoas : pessoas, senha : senha},
                type: "POST",
                success: function (data) {
                    if(data == ""){
                        $("#btnSubmit").attr('disabled', 'disabled');
                        $("#alertCerto").html("Cadastrado Com Sucesso!");
                        $("#alertCerto").fadeIn(200);
                        setTimeout(function(){
                            window.location = "../login";
                        }, 3000);
                    }else{
                        //erro
                        $("#alertErro").html("Email já cadastrado!");
                        $("#alertErro").fadeIn(200);
                        $("#btnSubmit").removeAttr('disabled');
                    }
                },
                error: function () {
                    $("#alertErro").html("Erro no Script! ;)");
                    $("#alertErro").fadeIn(200);
                    $("#btnSubmit").removeAttr('disabled');
                }
            });
            
            
        }
        
        return false;
    });
    
    
    
    //Botão Login
    $("#btnLoginPag").on('click', function(){
        window.location = "../../login";
    });
    
    $("#btnHome").on('click', function(){
        window.location = "../../";
    });
    
    $("#btnRelatorios").on('click', function(){
        window.location = "../../relatorios";
    });
    
    
    
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
    
    
    
   
});