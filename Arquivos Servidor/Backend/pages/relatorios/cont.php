<?php include "Backend/Config/config.php";
$mysql->buscaDados();
?>
<div class="container-fluid p-2">
    <b id="idSensor" lang=""></b>
</div>

<div class="container-fluid p-3 mt-5" style="margin-bottom: 80px;">
    <div class="bg-light p-2 rounded">
        <h1 class="text-center">Relatórios</h1>
    </div>
    
    <div class="bg-light p-5 rounded mt-3">
        <form method="post" action="">
            <!---->
            <div class="form-group">
                <label for="selectSensor" >Sensores</label>
                <?php echo $mysql->Sensores(); ?>
            </div>
            
            
            <div class="form-group">
                <label for="selectTipo" >Tipo De Relatório</label>
                <select id="selectTipo" id="selectTipo" class="form-control form-control-lg">
                    <option value="0">Selecione</option>
                    <option value="1">Diário</option>
                    <option value="2">Mensal</option>
                    <option value="3">Semanal</option>
                    <option value="4">Comparativo (Dia Atual Com Outro)</option>
                    <option value="5">Comparativo (Mês Atual Com Outro)</option>
                </select>
            </div>
            
            <div class="form-group" id="telaDia">
                <label for="selectData">Selecione o dia</label>
                <input type="date" id="selectDia" class="form-control form-control-lg">
            </div>
            
            <div class="form-group" id="telaMes">
                <label for="selectData">Selecione o Mês<br /><b class="text-muted" style="font-size: 14px;">Será utilizado o mês selecionado</b></label>
                <input type="date" id="selectMes" class="form-control form-control-lg">
            </div>
            
            <div class="form-group">
                <button type="button" id="btnGerar" class="btn btn-success">Gerar Dados</button>                
            </div>
            
            <div class="form-group">
                <div class="alert alert-danger" id="alertaSelect"></div>
            </div>
        </form>
        <script src="https://unpkg.com/jspdf@latest/dist/jspdf.min.js"></script>
    </div>
    
    <div class="bg-light p-1 rounded mt-3">
        <h3 class="text-center">Relatórios Salvos</h3>
        <div class="alert alert-success" id="msg">
            Relatório enviado para seu E-mail
        </div>
        <div id="relatoriosatu">
            <?php $mysql->relatorios(); ?>
        </div>
        
    </div>
    
</div>