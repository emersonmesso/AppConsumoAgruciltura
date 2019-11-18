$(document).ready(function(){
    var urlProcessos = "../Backend/Core/";
    $("#alertErro").hide();
    $("#alertCerto").hide();
    $("#formLogin").submit(function(){
        $("#btnSubmit").attr('disabled', 'disabled');
        
        //recebendo os dados
        var email = $("#campoEmail").val();
        var senha = $("#campoSenha").val();
        
        if(email == "" || senha == ""){
            $("#alertErro").html("E-mail ou senha não informada!");
            $("#alertErro").fadeIn(200);
            $("#btnSubmit").removeAttr('disabled');
        }else{
            $("#alertErro").hide();
            $.ajax({
                url: urlProcessos + "execLogin.php",
                data: {email: email, senha : senha},
                type: "POST",
                dataType: "JSON",
                success: function (data) {
                    if(!data.erro){
                        //ok
                        $("#alertCerto").html("Logado Com Sucesso!");
                        $("#alertCerto").fadeIn(200);
                        setTimeout(function(){
                            window.location = "../";
                        }, 3000);
                    }else{
                        $("#alertErro").html(data.msg);
                        $("#alertErro").fadeIn(200);
                        $("#btnSubmit").removeAttr('disabled');
                    }
                },
                error: function () {
                    $("#alertErro").html("Erro interno do sistema!");
                    $("#alertErro").fadeIn(200);
                    $("#btnSubmit").removeAttr('disabled');
                }
            });
            
        }
        
        return false;
    });
    
    //botão cadastre-se
    $("#btnCadastroPag").on("click", function(){
        window.location = "../cadastro";
    });
});