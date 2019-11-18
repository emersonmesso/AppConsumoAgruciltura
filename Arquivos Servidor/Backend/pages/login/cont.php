<div class="container-fluid text-center bg-light">
    <img src="../Backend/Images/logo.png" alt="Imagem Logo" width="40%">
    <h1 class="text-info">Entre com seus dados!</h1>
</div>
<div class="container-fluid p-3">
    
    <div class="alert alert-danger" id="alertErro" role="alert">
        ok
    </div>
    
    <div class="alert alert-success" id="alertCerto" role="alert">
        ok
    </div>
    
    <form method="post" action="" id="formLogin">
        
        <div class="form_group">
            <label for="campoEmail" class="text-info font-weight-bold">Email:</label>
            <input type="email" id="campoEmail" class="form-control form-control-lg">
        </div>
        
        
        <div class="form-group">
            <label for="campoSenha" class="text-info font-weight-bold">Senha: </label>
            <input type="password" id="campoSenha" class="form-control form-control-lg">
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg btn-block" id="btnSubmit">Entrar</button>
            <!--<button type="button" class="btn btn-primary btn-lg btn-block" id="btnCadastroPag">Cadastrar-se</button>-->
            
        </div>
        
    </form>
</div>