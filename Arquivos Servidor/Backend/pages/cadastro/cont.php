<div class="container-fluid text-center bg-light">
    <img src="../Backend/Images/logo.png" alt="Imagem Logo" width="40%">
    <h1 class="text-info">Cadastre-se!</h1>
</div>
<div class="container-fluid p-3">
    
    <div class="alert alert-danger" id="alertErro" role="alert">
        ok
    </div>
    
    <div class="alert alert-success" id="alertCerto" role="alert">
        Cadastrado Com Sucesso!
    </div>
    
    <form method="post" action="" id="formCdastro">
        
        <b class="text-danger">Todos os campos são obrigatórios*</b>
        
        <div class="form_group">
            <label for="campoNome" class="text-info font-weight-bold">Seu Nome:</label>
            <input type="text" id="campoNome" class="form-control form-control-lg">
        </div>
        
        <div class="form_group">
            <label for="campoPessoas" class="text-info font-weight-bold">Pessoas na casa:</label>
            <input type="number" id="campoPessoas" class="form-control form-control-lg">
        </div>
        
        <div class="form_group">
            <label for="campoEmail" class="text-info font-weight-bold">Email:</label>
            <input type="email" id="campoEmail" class="form-control form-control-lg">
        </div>
        
        
        <div class="form-group">
            <label for="campoSenha" class="text-info font-weight-bold">Senha: </label>
            <input type="password" id="campoSenha" class="form-control form-control-lg">
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg btn-block" id="btnSubmit">Cadastrar</button>
            <button type="button" class="btn btn-primary btn-lg btn-block" id="btnLoginPag">Entrar</button>
            
        </div>
        
    </form>
</div>