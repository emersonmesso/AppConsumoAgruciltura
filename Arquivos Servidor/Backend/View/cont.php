<?php include "Backend/Config/config.php";
$mysql->buscaDados();
?>
<div class="container-fluid p-2">
    <b id="idSensor" lang=""></b>
</div>

<div class="container-fluid p-3 mt-5" style="margin-bottom: 80px;">
    <div class="bg-light rounded p-4">
        <div class="row">
            
            <!--CONSUMO-->
            <div class="col-6 text-center">
                <h5>Consumo Por Mês</h5>
                <span class="text-muted" style="font-size: 18px;"><?php echo $mysql->litrosPessoaMes($mysql->user['id']); ?> Litros Por Pessoa</span>
                <h5 class="mt-2">Consumo Por Dia</h5>
                <span class="text-muted" style="font-size: 18px;"><?php echo $mysql->consumoPessoaDia(); ?> Litros Por Pessoa</span>
            </div>
            <!--BANDEIRA-->
            <div class="col-6 text-center">
                <?php echo $mysql->bandeira(); ?>
            </div>
        </div>
        
        <div class="container-fluid text-center mt-3">
            <h5>Consumo de Hoje</h5>
            <div class="circlechart"></div>
        </div>
    </div>
    
    
    <!--SENSORES-->
    <div class="bg-light rounded p-4 mt-4">
        <h4>Meus Sensores</h4>
        <?php echo $mysql->Sensores(); ?>
    </div>
    
    
    <!--DADOS DO SENSOR-->
    <div class="bg-light rounded p-4 mt-4 text-center" id="telaSensor">
        <h2>Dados Do Sensor</h2>
        
        <h4 class="mt-3">Gráfico Mensal</h4>
        <canvas id="graficoMensal" responsive="true" height="300px"></canvas>
        <br />
        <h4 class="mt-3">Gráfico Diário</h4>
        <canvas id="graficoDiario" responsive="true" height="300px"></canvas>
        
        
        <h2 class="mt-3">Comparação Com Outro Mês</h2>
        
        <select id="compMeses" class="form-control form-control-lg">
            
        </select>
        <div id="telaComparacao">
            <h4 class="mt-3">Gráfico De Comparação</h4>
            <canvas id="graficoComp" responsive="true" height="300px"></canvas>
        </div>
        
    </div>
    
</div>
     



