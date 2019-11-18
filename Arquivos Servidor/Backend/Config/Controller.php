<?php
require "mysql.php";
class Controller {
    private $url;
    public $DADOS;
    public $user = array();
    
    
    function Controller (){
        
        $_SERVER['REQUEST_URI'];
        $parte1 = strrchr($_SERVER['REQUEST_URI'],"?");
        $parte2 = str_replace($parte1,"",$_SERVER['REQUEST_URI']);
        $this->url = explode("/",$parte2);
        array_shift($this->url);
        $this->buscaDados();
        
    }
    
    //HEADER
    public function header(){
        //buscando o cabeçalho do arquivo
        if($this->url[0] == ""){
            //inicial
            include "Backend/View/header.php";
        }else{
            //verifica se existe a pasta
            if(file_exists("Backend/pages/".$this->url[0]."/")){
                include "Backend/pages/".$this->url[0]."/header.php";
            }else{
                header("Location: ../");
            }
        }
    }


    //CONTEUDO
    public function conteudo (){
        if($this->url[0] == ""){
            //inicial
            include "Backend/View/cont.php";
        }else{
            include "Backend/pages/".$this->url[0]."/cont.php";
        }
    }


    //FOOTER
    public function footer(){
        if($this->url[0] == ""){
            //inicial
            include "Backend/View/footer.php";
        }else{
            include "Backend/pages/".$this->url[0]."/footer.php";
        }
    }
    
    //SELECT no banco de dados
    public function select($tabela,$todos=NULL,$where=NULL,$order=NULL){
        //inicia a classe
        $mysql = new mysql();
        //instancia a conexão
        $mysql->conecta();
        
        if($todos == NULL){
            $todos = "*";
        }
        if($where != NULL){
            $where = " WHERE ".$where;
        }
        if($order != NULL){
            $order = " ORDER BY ".$order;
        }
        $sql = "SELECT {$todos} FROM {$tabela}{$where}{$order}";
        $query = $mysql->sql->query($sql);
        //fecha a coneção
        mysqli_close($mysql->sql);
        return $query;
    }
    
    //DELETE no banco de dados
    public function delete($tabela, $where){
        //inicia a classe
        $mysql = new mysql();
        //instancia a conexão
        $mysql->conecta();
        //
        if($where != NULL){
            $where = " WHERE ".$where;
        }
        //criando a consulta
        $sql = "DELETE FROM {$tabela}{$where}";
        $query = $mysql->sql->query($sql);
        //fecha a coneção
        mysqli_close($mysql->sql);
        return $query;
    }
    
    //UPDATE no banco de dados
    public function update($tabela,$valores,$where){
        //inicia a classe
        $mysql = new mysql();
        //instancia a conexão
        $mysql->conecta();
        //
        if($where != NULL){
            $where = " WHERE ".$where;
        }
        //
        $sql = "UPDATE {$tabela} SET {$valores} {$where}";
        //executa a Query
        $query = $mysql->sql->query($sql);
        //fecha a coneção
        mysqli_close($mysql->sql);
        return $query;
    }
    
    //INSERE no banco de dados
    public function insere($tabela,$campos,$valores){
        //inicia a classe
        $mysql = new mysql();
        //instancia a conexão
        $mysql->conecta();
        //
        $sql = "INSERT INTO {$tabela}({$campos}) VALUES({$valores})";
        //executa a Query
        $query = $mysql->sql->query($sql);
        //fecha a coneção
        mysqli_close($mysql->sql);
        return $query;
    }
    
    
    //Verifica usuário
    public function verLogin(){
        if ( session_status() !== PHP_SESSION_ACTIVE ){
            session_start();
        }
        
        if(!isset($_SESSION['login']) || !isset($_SESSION['password'])){
            header("Location: ../login");
        }
        
    }
    
    //Busca os dados do usuário
    public function buscaDados(){
        if ( session_status() !== PHP_SESSION_ACTIVE ){
            session_start();
        }
        
        if(isset($_SESSION['login'])){
            $sql = $this->select("users", "*", "email = '".$_SESSION['login']."'");
            while($row = mysqli_fetch_array($sql)){

                $this->user['nome'] = $row['nome'];
                $this->user['id'] = $row['id'];
                $this->user['email'] = $row['email'];
                $this->user['pessoas'] = $row['pessoas'];

            }
        }
    }
    
    //Mostra os sensores
    public function Sensores(){
        $sql = $this->select("sensores", "*", "id_user = '".$this->user['id']."'");
        if(mysqli_num_rows($sql) > 0){
            
            echo '<select class="form-control form-control-lg" id="sensoresSelect">';
                echo '<option value="0">Selecione</option>';
                while($rows = mysqli_fetch_array($sql)){
                    echo '<option value="'.$rows['id'].'">'.$rows['nome'].'</option>';
                }
            echo '</select>';
            
        }else{
            echo 'Nenhum sensor!';
        }
    }
    
    
    
    /*Dados por sensor*/
    
    //retorna o valor de litros por mes de cada sensor
    public function litrosSensorMesAtual($id){
        
        $mes = date("m");
        $litros = 0;
        
        //buscando os dias que tem dados
        $sql = $this->select("info_sensor", "DISTINCT dia", "id_sensor = '$id' AND mes = '$mes'");
        //pega o total de dias
        $total = mysqli_num_rows($sql);
        if($total != 0){
            while($row = mysqli_fetch_array($sql)){
            
                //buscando a soma dos litros no dia
                $sqll = $this->select("info_sensor", "*", "id_sensor = '$id' AND mes = '$mes' AND dia = '".$row['dia']."'");
                //mostrando a soma
                while ($dados = mysqli_fetch_array($sqll)){
                    $litros += $dados['valor'];
                }
            }
            
        }else{
            return 0;
        }
        return $litros;
    }
    //Valor em litros do dia de cada sensor
    public function litrosDiaSensor($idSensor, $dia,$mes,$ano){
        $litros = 0;
        
        //busca os dados do dia
        $sql = $this->select("info_sensor", '*', "id_sensor = '$idSensor' AND dia = '$dia' AND mes = '$mes' AND ano = '$ano'");
        $total = mysqli_num_rows($sql);
        if($total != 0){
            while($row = mysqli_fetch_array($sql)){
                $litros += $row['valor'];
            }
            
        }else{
            return 0;
        }
        return $litros;
    }
    
    /*LITROS POR SENSOR*/
    
    
    //converte valor de litros para pagamento
    public function conveteValor($litros){
        
    }
    
    //calcula total de litros por pessoa de todos os sensores no mes atual
    public function litrosPessoaMes($idUser){
        
        $litros = 0;
        $pessoas = 0;
        $mes = date("m");
        $ano = date("Y");
        
        //buscando os sensores
        $sql1 = $this->select("sensores", "*", "id_user = '$idUser'");
        if(mysqli_num_rows($sql1) > 0){
            
            //sensores
            while($rowSensores = mysqli_fetch_array($sql1)){
                
                $sql4 = $this->select("info_sensor", "DISTINCT dia", "id_sensor = '".$rowSensores['id']."' AND ano = '$ano' AND mes = '$mes'");
                if(mysqli_num_rows($sql4) > 0){
                    while ($rowDia = mysqli_fetch_array($sql4)){
                        //busca os valores
                        $sql5 = $this->select("info_sensor", "*", "id_sensor = '".$rowSensores['id']."' AND ano = '$ano' AND mes = '$mes' AND dia = '".$rowDia['dia']."'");
                        while ($row = mysqli_fetch_array($sql5)){
                            $litros += $row['valor'];
                        }
                    }
                }else{
                    return "e";
                }    
                    
            }
        }else{
            return "e";
        }
        //busca total de pessoas
        $sqlPessoa = $this->select("users", "*", "id = '$idUser'");
        while($rowUser = mysqli_fetch_array($sqlPessoa)){
            $pessoas = $rowUser['pessoas'];
        }
        
        //calcula o total de litros por pessoa
        $total = $litros / $pessoas;
        
        
        return round( $total , 1);
        
    }
    
    //Calcula a bandeira do mes
    public function bandeira(){
        $mes = date("m");
        $ano = date("Y");
        $litrosPessoa = $this->litrosPessoaMes($this->user['id']);
        $totalDias = 0;
        $sql = $this->select("sensores", "*", "id_user = '".$this->user['id']."'");
        
        if(mysqli_num_rows($sql) > 0){
            
            while ($row = mysqli_fetch_array($sql)){
                
                $sql = $this->select("info_sensor", "DISTINCT dia", "id_sensor = '".$row['id']."' AND mes = '$mes' AND ano = '$ano'");
                if(mysqli_num_rows($sql) > 0){
                    $totalDias += mysqli_num_rows($sql);
                    while($rowDias = mysqli_fetch_array($sql)){
                       $totalDias += 1;
                    }
                    
                }else{
                    return 0;
                }
                
            }
            
        }else{
            return 0;
        }
        
        $media = ($litrosPessoa / $totalDias);
        
        if($media < 120){
            echo '<img src="../Backend/Images/verde.png" alt="Imagem ótimo" width="80%">';
        }else if($media > 120 && $media < 200){
            echo '<img src="../Backend/Images/amarelo.png" alt="Imagem razoável" width="80%">';
        }else{
            echo '<img src="../Backend/Images/vermelho.png" alt="Imagem perigo" width="80%">';
        }
    }
    
    /*DADOS DO GRÁFICO DE PORCENTAGEM*/
    public function graficoBorda(){
        $mes = date("m");
        $ano = date("Y");
        $dia = date("d");
        $dados = array();
        $r = rand(0, 255);
        $g = rand(0, 255);
        $b = rand(0, 255);
        $dados['labels'] = array("Consumo do Dia");
        
        //
        $pessoas = 0;
        //
        $totalConsumo = 0;
        //Buscando o total de pessoas
        $sqlPessoa = $this->select("users", "*", "id = '" . $this->user['id'] . "'");
        while($rowUser = mysqli_fetch_array($sqlPessoa)){
            $pessoas = $rowUser['pessoas'];
        }
        //Buscando todo o consumo do dia
        $sql = $this->select("sensores", "*", "id_user = '".$this->user['id']."'");

        if(mysqli_num_rows($sql) > 0){
            while ($row = mysqli_fetch_array($sql)){
                $sqll = $this->select("info_sensor", "*", "id_sensor = '".$row['id']."' AND mes = '$mes' AND ano = '$ano' AND dia = '$dia'");
                if(mysqli_num_rows($sql) > 0){
                    while($consumo = mysqli_fetch_array($sqll)){
                       $totalConsumo += $consumo['valor'];
                    }
                }
            }
            
            //calculando o consumo do dia por pessoa
            $consumo = ($totalConsumo / $pessoas);
            $result = ($consumo * 100) / 110;
        }
        return round($result, 1);
    }
    /*DADOS DO GRÁFICO DE PORCENTAGEM*/
    
    
    
    //Consumo por dia por pessoa
    public function consumoPessoaDia(){
        $mes = date("m");
        $ano = date("Y");
        $litrosPessoa = $this->litrosPessoaMes($this->user['id']);
        $totalDias = 0;
        $sql = $this->select("sensores", "*", "id_user = '".$this->user['id']."'");
        
        if(mysqli_num_rows($sql) > 0){
            
            while ($row = mysqli_fetch_array($sql)){
                
                $sql = $this->select("info_sensor", "DISTINCT dia", "id_sensor = '".$row['id']."' AND mes = '$mes' AND ano = '$ano'");
                if(mysqli_num_rows($sql) > 0){
                    $totalDias += mysqli_num_rows($sql);
                    while($rowDias = mysqli_fetch_array($sql)){
                       $totalDias += 1;
                    }
                    
                }else{
                    return 0;
                }
                
            }
            
        }else{
            return 0;
        }
        
        $media = ($litrosPessoa / $totalDias);
        
        return round( $media );
    }
    
    
    //Gera gráfico mes por sensor
    public function dadosGraficoMesSensor($idSensor){
        $mes = date("m");
        $dados = array();
        $ano = date("Y");
        $r = rand(0, 255);
        $g = rand(0, 255);
        $b = rand(0, 255);
        
        $sqll = $this->select("info_sensor", "DISTINCT dia", "id_sensor = '$idSensor' AND mes = '$mes' AND ano = '$ano'", "dia ASC");
        
        if(mysqli_num_rows($sqll) > 0){
            while($rowDia = mysqli_fetch_array($sqll)){
                $totalDia = 0;
                $sql = $this->select("info_sensor", "*", "id_sensor = '$idSensor' AND mes = '$mes' AND dia = '".$rowDia['dia']."' AND ano = '$ano'");
                while($row = mysqli_fetch_array($sql)){
                    
                    $totalDia += $row['valor'];
                    
                }
                
                //valores de cada dia
                $dados['labels'][] = $rowDia['dia'];
                $dados['datasets'][0]['data'][] = $totalDia;
                $dados['datasets'][0]['backgroundColor'][] = "rgba($r, $g, $b, 0.2)";
                $dados['datasets'][0]['borderColor'][] = "rgba($r, $g, $b, 1)";
                //$dados['datasets']['label'] = "";
            }
            $dados['datasets'][0]['borderWidth'] = 5;
            $dados['datasets'][0]['label'] = "Litros Por Dia";
            $dados['datasets'][0]['backgroundColor'] = "transparent";
        }else{
            $dados['datasets'][0]['borderWidth'] = 5;
            $dados['datasets'][0]['label'] = "Litros Por Dia";
            $dados['datasets'][0]['backgroundColor'] = "transparent";
            return $dados;
        }
        
        return $dados;
        
    }
    
    //Dados para gráfico do dia atual
    public function dadosGraficoDiaSensor($idSensor){
        $dia = date("d");
        $mes = date("m");
        $ano = date("Y");
        $dados = array();
        $r = rand(0, 255);
        $g = rand(0, 255);
        $b = rand(0, 255);
        
        //faz a busca pelos dados do sensor
        $sql = $this->select("info_sensor", "DISTINCT horas", "id_sensor = '$idSensor' AND dia = '$dia' AND mes = '$mes' AND ano = '$ano'", "horas ASC");
        if(mysqli_num_rows($sql) > 0){
            
            while($row = mysqli_fetch_array($sql)){
                
                //busca o consumo na hora
                $sqll = $this->select("info_sensor", "*", "id_sensor = '$idSensor' AND dia = '$dia' AND mes = '$mes' AND ano = '$ano' AND horas = '".$row['horas']."'");
                $totalConsumo = 0;
                while($rowHoras = mysqli_fetch_array($sqll)){
                    $totalConsumo += $rowHoras['valor']; 
                }
                
                //valores de cada dia
                $dados['labels'][] = $row['horas'];
                $dados['datasets'][0]['data'][] = $totalConsumo;
                $dados['datasets'][0]['backgroundColor'][] = "rgba($r, $g, $b, 0.2)";
                $dados['datasets'][0]['borderColor'][] = "rgba($r, $g, $b, 1)";
            }
            $dados['datasets'][0]['borderWidth'] = 5;
            $dados['datasets'][0]['label'] = "Litros Por Hora";
            $dados['datasets'][0]['backgroundColor'] = "transparent";
        }else{
            return null;
        }
        return $dados;
    }
    
    //gera gráfico comparar mês atual com demais do ano
    public function gerDadosCamparaMesAno($idSensor){
        $ano = date("Y");
        $cont = 0;
        
        //adicionando os labels
        for($i = 1; $i <= 31; $i++){
            $dados['labels'][] = $i;
        }
        
        $sql_1 = $this->select("info_sensor", "DISTINCT mes", "id_sensor = '$idSensor' AND ano = '$ano'");
        while($row_1 = mysqli_fetch_array($sql_1)){
            $r = rand(0, 255);
            $g = rand(0, 255);
            $b = rand(0, 255);
            //busca consumo do mês
            $sqlMes = $this->select("info_sensor", "DISTINCT dia", "id_sensor = '$idSensor' AND mes = '".$row_1['mes']."' AND ano = '$ano'", "dia ASC");
            if(mysqli_num_rows($sqlMes) > 0){
                while($rowDia = mysqli_fetch_array($sqlMes)){
                    $totalDia = 0;
                    $sql_2 = $this->select("info_sensor", "*", "id_sensor = '$idSensor' AND mes = '".$row_1['mes']."' AND dia = '".$rowDia['dia']."' AND ano = '$ano'");
                    while($row = mysqli_fetch_array($sql_2)){

                        $totalDia += $row['valor'];

                    }

                    //valores de cada dia
                    $dados['datasets'][$cont]['data'][] = $totalDia;
                    $dados['datasets'][$cont]['backgroundColor'][] = "rgba($r, $g, $b, 0.2)";
                    $dados['datasets'][$cont]['borderColor'][] = "rgba($r, $g, $b, 1)";
                    //$dados['datasets']['label'] = "";
                }
                $dados['datasets'][$cont]['borderWidth'] = 5;
                $dados['datasets'][$cont]['label'] = $this->nomeMes($row_1['mes']);
                $dados['datasets'][$cont]['backgroundColor'] = "transparent";
            }else{
                $dados['erro'] = true;
            }
            $cont++;
        }
        
        return $dados;
        
    }
    
    //gera gráfico para comparar mês atual com outro
    public function geraDadosComparaMes($data, $idSensor){
        $da = explode(":", $data);
        $mesAtual = date("m");
        $dados = array();
        $r = rand(0, 255);
        $g = rand(0, 255);
        $b = rand(0, 255);
        $ano = date("Y");
        //calculando os dias do mes a serem mosrados
        $diasMes = cal_days_in_month(CAL_GREGORIAN, $da[0], $da[1]);
        $diasMesAtual = cal_days_in_month(CAL_GREGORIAN, $mesAtual, date("Y"));
        if($diasMes > $diasMesAtual){
            for($i = 1; $i <= $diasMes; $i++){
                $dados['labels'][] = $i;
            }
        }else{
            for($i = 1; $i <= $diasMesAtual; $i++){
                $dados['labels'][] = $i;
            }
        }
        
        
        //busca consumo do mês
        $sqll = $this->select("info_sensor", "DISTINCT dia", "id_sensor = '$idSensor' AND mes = '$da[0]' AND ano = '$da[1]'", "dia ASC");
        
        if(mysqli_num_rows($sqll) > 0){
            while($rowDia = mysqli_fetch_array($sqll)){
                $totalDia = 0;
                $sql = $this->select("info_sensor", "*", "id_sensor = '$idSensor' AND mes = '$da[0]' AND dia = '".$rowDia['dia']."' AND ano = '$da[1]'");
                while($row = mysqli_fetch_array($sql)){
                    
                    $totalDia += $row['valor'];
                    
                }
                
                //valores de cada dia
                
                $dados['datasets'][0]['data'][] = $totalDia;
                $dados['datasets'][0]['backgroundColor'][] = "rgba($r, $g, $b, 0.2)";
                $dados['datasets'][0]['borderColor'][] = "rgba($r, $g, $b, 1)";
                //$dados['datasets']['label'] = "";
            }
            $dados['datasets'][0]['borderWidth'] = 5;
            $dados['datasets'][0]['label'] = $this->nomeMes($da[0]);
            $dados['datasets'][0]['backgroundColor'] = "transparent";
        }else{
            $dados['erro'] = 'Nenhum Dado para o mês';
        }
        
        //buscando os dados do mes atual
        $r = rand(0, 255);
        $g = rand(0, 255);
        $b = rand(0, 255);
        $sql_1 = $this->select("info_sensor", "DISTINCT dia", "id_sensor = '$idSensor' AND mes = '$mesAtual' AND ano = '$ano'", "dia ASC");
        
        if(mysqli_num_rows($sql_1) > 0){
            while($rowDia = mysqli_fetch_array($sql_1)){
                $totalDia = 0;
                $sql_2 = $this->select("info_sensor", "*", "id_sensor = '$idSensor' AND mes = '$mesAtual' AND dia = '".$rowDia['dia']."' AND ano = '$ano'");
                while($row = mysqli_fetch_array($sql_2)){
                    
                    $totalDia += $row['valor'];
                    
                }
                
                //valores de cada dia
                $dados['datasets'][1]['data'][] = $totalDia;
                $dados['datasets'][1]['backgroundColor'][] = "rgba($r, $g, $b, 0.2)";
                $dados['datasets'][1]['borderColor'][] = "rgba($r, $g, $b, 1)";
                //$dados['datasets']['label'] = "";
            }
            $dados['datasets'][1]['borderWidth'] = 5;
            $dados['datasets'][1]['label'] = "Mês Atual";
            $dados['datasets'][1]['backgroundColor'] = "transparent";
        }else{
            $dados['erro'] = 'Nenhum Dado para o mês';
        }
        
        
        return $dados;
    }
    
    //nome do mês
    private function nomeMes($mes){
        switch ($mes){
            case 1:
                return "Janeiro";
            case 2:
                return "Fevereiro";
            case 3:
                return "Março";
            case 4:
                return "Abril";
            case 5:
                return "Maio";
            case 6:
                return "Junho";
            case 7:
                return "Julho";
            case 8:
                return "Agosto";
            case 9:
                return "Setembro";
            case 10:
                return "Outubro";
            case 11:
                return "Novembro";
            case 12:
                return "Desembro";
            default :
                return 'Erro';
        }
        
    }
    
    //Busca os meses do sensor diferente do atual
    public function mesesDiferentes($sensor){
        $dados = array();
        $mesAtual = date("m");
        //buscando os meses
        $anoAtual = date("Y");
        $sql = $this->select("info_sensor", "DISTINCT mes", "mes != '$mesAtual' AND ano = '$anoAtual' AND id_sensor = '$sensor'");
        if(mysqli_num_rows($sql) > 0){
            $dados[] = '<option value="0">Selecione</option>';
            while($rows = mysqli_fetch_array($sql)){
               $dados[] = '<option value="'.$rows['mes'].':'.$anoAtual.'">'. $this->nomeMes($rows['mes']).'</option>';
            }
            $dados[] = '<option value="13">Todos Os Meses Do Ano</option>';
        }else{
            //laço de repetição em busca de dados
            $segue = true;
            $limite = 0;
            while($segue){
                $anoAtual -= 1;
                $sql1 = $this->select("info_sensor", "DISTINCT mes", "ano = '$anoAtual' AND id_sensor = '$sensor'");
                if(mysqli_num_rows($sql1) > 0){
                    $dados[] = '<option value="0">Selecione</option>';
                    while($rows = mysqli_fetch_array($sql1)){
                       $dados[] = '<option value="'.$rows['mes'].':'.$anoAtual.'">'. $this->nomeMes($rows['mes']).'/'.$anoAtual.'</option>';
                    }
                }else{
                    $segue = false;
                }
                if($limite > 6){
                    $segue = false;
                }
                $limite++;
            }
        }
        
        
        return $dados;
    }
    
    
    /*RELATÓRIOS*/
        //gera os dados do dia do sensor
        public function dadosDiaSensor($sensor, $data){
            $data = explode("-", $data);
            $maior = 0;
            $dataMaior;
            $dataMenor;
            $menor = 9999999;
            $total = 0;
            $horaMenor = 30;
            $horaMaior = 0;
            
            $sql = $this->select("info_sensor", "*", "dia = ".intval($data[2])." AND mes = ".intval($data[1])." AND ano = ".intval($data[0])."");
            if(mysqli_num_rows($sql) > 0){
                while ($dados = mysqli_fetch_array($sql)){
                    if($dados['valor'] > $maior){
                        $maior = $dados['valor'];
                        $dataMaior = $dados['horas'];
                    }
                    if($dados['horas'] < $horaMenor){
                        $horaMenor = $dados['horas'];
                    }
                    if($dados['horas'] > $horaMaior){
                        $horaMaior = $dados['horas'];
                    }
                    
                    if($dados['valor'] < $menor){
                        $menor = $dados['valor'];
                        $dataMenor = $dados['horas'];
                    }
                    $total += $dados['valor'];
                }
                $retorno = array(
                    'total' => $total,
                    'maior' => $maior,
                    'menor' => $menor,
                    'dataMaior' => $dataMaior,
                    'dataMenor' => $dataMenor,
                    'horaMaior' => $horaMaior,
                    'horaMenor' => $horaMenor
                );
                return $retorno;
            }else{
                return 0;
            }
        }
        
        //Dados para gráfico do dia atual
        public function dadosGraficoDiaSensorRelatorio($idSensor, $dia){
            $mes = date("m");
            $ano = date("Y");
            $dados = array();
            
            //faz a busca pelos dados do sensor
            $sql = $this->select("info_sensor", "DISTINCT horas", "id_sensor = '$idSensor' AND dia = '$dia' AND mes = '$mes' AND ano = '$ano'", "horas ASC");
            if(mysqli_num_rows($sql) > 0){
                while($row = mysqli_fetch_array($sql)){

                    //busca o consumo na hora
                    $sqll = $this->select("info_sensor", "*", "id_sensor = '$idSensor' AND dia = '$dia' AND mes = '$mes' AND ano = '$ano' AND horas = '".$row['horas']."'");
                    $totalConsumo = 0;
                    while($rowHoras = mysqli_fetch_array($sqll)){
                        $totalConsumo += $rowHoras['valor']; 
                    }

                    //valores de cada dia
                    $dados[] = $totalConsumo;
                }
            }else{
                return;
            }
            return $dados;
        }
        
        public function tabelaDados($dados){
            $i = 1;
            $a = 0;
            $string = array();
            foreach ($dados as $valor){
                $string[] = array("De $a h ate $i h","$valor Litros");
                $i++;
                $a++;
            }
            
            
            return $string;
        }
        
        public function tabelaDadosDias($dados){
            $i = 1;
            $string = array();
            foreach ($dados as $valor){
                $string[] = array("Dia $i","$valor Litros");
                $i++;
            }
            
            
            return $string;
        }

                //relatório diários
        public function relatorioDiario($sensor, $data){
            ini_set('display_errors',1);
            ini_set('display_startup_erros',1);
            error_reporting(E_ALL);
            
            require_once 'dompdf/lib/html5lib/Parser.php';
            require_once 'dompdf/src/Autoloader.php';
            require_once '../../Backend/Config/jpgraph/src/jpgraph.php';
            require_once '../../Backend/Config/jpgraph/src/jpgraph_line.php';
            require_once '../../Backend/Config/jpgraph/src/jpgraph_canvas.php';
            require_once '../../Backend/Config/jpgraph/src/jpgraph_table.php';
            $datatime = time();
            Dompdf\Autoloader::register();
            
            $dompdf = new Dompdf\Dompdf();
            
            //
            $dados = $this->dadosDiaSensor($sensor, $data);
            
            if($dados == 0){
                echo 'Não encontramos dados para esse dia';
                exit;
            }
            
            $horas = $dados['horaMaior'] - $dados['horaMenor'];
            $litrosHora = $dados['total'] / intval($horas);
            $litrosPessoa = $dados['total'] / $this->user['pessoas'];
            $litrosPessoa = round($litrosPessoa, 2);
            $situacao = "";
            $porcentagem = ($litrosPessoa * 100) / 200;
            
            if( $porcentagem <= 60 ){
                $situacao = "Ótimo";
            }else if( $porcentagem > 60 && $porcentagem <= 100){
                $situacao = "Razoável";
            }else{
                $situacao = "Perigo";
            }
            
            $litrosHora = round($litrosHora, 2);
            //datas
            $time = strtotime($data);
            $dat = new DateTime();
            $dat->setTimestamp($time);
            
            
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->set_option('defaultFont', 'Courier');
            
            //Gráficos
            $grafico = $this->dadosGraficoDiaSensorRelatorio($sensor, $dat->format("d"));
 
            // Create the graph. These two calls are always required
            $graph = new Graph(600,400);
            $graph->SetScale('textlin');
            $graph->title->Set("Gráfico Do Dia " . $dat->format("d/m/Y"));
            $graph->xaxis->SetTitle("Horas", "center");
            $graph->yaxis->SetTitle("Litros", "center");

            // Create the linear plot
            $lineplot=new LinePlot($grafico);
            $lineplot->SetColor('blue');

            // Add the plot to the graph
            $graph->Add($lineplot);
            
            $gdImgHandler = $graph->Stroke(_IMG_HANDLER);
            $fileName = "/tmp/imagefile.png";
            $graph->img->Stream($fileName);
            
            
            //Tabela
            $table = new CanvasGraph(800,550);
            $tabela = new GTextTable();
            $tabela->Set($this->tabelaDados($grafico));
            $tabela->SetMinColWidth(200);
            $tabela->SetAlign("center");
            
            $table->Add($tabela);
            $gdImgHandlerTable = $table->Stroke(_IMG_HANDLER);
            $tableName = "/tmp/table.jpg";
            $table->img->Stream($tableName);
            

            $dompdf->load_html('<!DOCTYPE html>
                <html>
                    <head>
                        <link rel="stylesheet" href="../../Backend/Styles/bootstrap.css" type="text/css">
                    </head>
                    <body>
                        <div class="container bg-dark p-2">
                            <h1 class="text-center text-white">Relatorio Diário</h1>
                            <h3 class="text-center text-white">Dia '.$dat->format("d/m/Y").'</h3>
                        </div>

                        <div class="container rounded mt-4 p-2">
                            <b>Total Consumido No Dia: '.$dados['total'].' Litros 
                            <br />
                            <b>Litros Por Hora: <b/> '.$litrosHora.' L/H
                            <br />
                            <b>Litros Por Pessoa: <b/> '.$litrosPessoa.' L/P
                            <br />
                            <b>Situação: <b/> '.$situacao.'
                        </div>
                        <div class="container mt-4 p-2 text-center">
                            <h3 class="text-center bg-dark p-2 text-white">Dados Detalhados Do Consumo</h3>
                            <img src="'.$tableName.'">
                        </div>
                        
                        <div class="container mt-4 p-2 text-center">
                            <h3 class="text-center text-white bg-dark p-2">Gráficos</h3>
                            <div class="container-fluid w-100">
                                <img src="'.$fileName.'">
                            </div>
                        </div>

                    </body>
                </html>
            ');
            //renderizar o html
            $dompdf->render();
            
            // pega o código fonte do novo arquivo PDF gerado
            $output = $dompdf->output();
            $caminho = "../../Backend/pdf/" . md5(time()).".pdf";
            // defina aqui o nome do arquivo que você quer que seja salvo
            file_put_contents($caminho, $output);
            //$dompdf->stream();
            
            /*Adicionando os dado no banco de dados*/
            $info = "Diário de " . $dat->format("d/m/Y");
            $sqlAdd = $this->insere("relatorios", "id_sensor, id_user, link, info, data", "'$sensor', '".$this->user['id']."', '$caminho', '$info', '$datatime'");
            if(!$sqlAdd){
                echo "Houve algum erro: ".mysqli_error($sqlAdd);
            }
            
        }
        
        public function dadosGraficoMesRelatorio($data, $idSensor) {
            $dat = new DateTime($data);
            $mes = $dat->format("m");
            $ano = $dat->format("Y");
            $dados = array();
            
            //busca os dias do mês e os valores
            //faz a busca pelos dados do sensor
            $sql = $this->select("info_sensor", "DISTINCT dia", "id_sensor = '$idSensor' AND mes = '$mes' AND ano = '$ano'", "dia ASC");
            if(mysqli_num_rows($sql) > 0){
                while($row = mysqli_fetch_array($sql)){

                    //busca o consumo na hora
                    $sqll = $this->select("info_sensor", "*", "id_sensor = '$idSensor' AND dia = '".$row['dia']."' AND mes = '$mes' AND ano = '$ano'");
                    $totalConsumo = 0;
                    while($rowHoras = mysqli_fetch_array($sqll)){
                        $totalConsumo += $rowHoras['valor']; 
                    }

                    //valores de cada dia
                    $dados[] = $totalConsumo;
                }
            }else{
                return 0;
            }
            
            return $dados;
        }
        
        
        public function relatorioMensal($mes, $sensor) {
            ini_set('display_errors',1);
            ini_set('display_startup_erros',1);
            error_reporting(E_ALL);
            
            require_once 'dompdf/lib/html5lib/Parser.php';
            require_once 'dompdf/src/Autoloader.php';
            require_once '../../Backend/Config/jpgraph/src/jpgraph.php';
            require_once '../../Backend/Config/jpgraph/src/jpgraph_line.php';
            require_once '../../Backend/Config/jpgraph/src/jpgraph_canvas.php';
            require_once '../../Backend/Config/jpgraph/src/jpgraph_table.php';
            
            Dompdf\Autoloader::register();
            $dompdf = new Dompdf\Dompdf();
            
            $data = new DateTime($mes);
            //gerando os dados do mes
            $dadosGrafico = $this->dadosGraficoMesRelatorio($mes, $sensor);
            if($dadosGrafico == 0){
                echo 'Não encontramos dados para o mês';
                exit;
            }
            
            $datatime = time();
            
            $totalConsumo = 0;
            
            foreach ($dadosGrafico as $consumo){
                $totalConsumo += $consumo;
            }
            
            // Criando o gráfico
            $graph = new Graph(700,500);
            $graph->SetScale('textlin');
            $graph->title->Set("Gráfico " . $this->nomeMes( $data->format("m") ). " de " . $data->format("Y") );
            $graph->xaxis->SetTitle("Dias", "center");
            $graph->yaxis->SetTitle("Litros", "center");

            // Create the linear plot
            $lineplot=new LinePlot($dadosGrafico);
            $lineplot->SetColor('blue');

            // Add the plot to the graph
            $graph->Add($lineplot);
            
            $gdImgHandler = $graph->Stroke(_IMG_HANDLER);
            $fileName = "/tmp/imagefile.png";
            $graph->img->Stream($fileName);
            
            
            //Criando o PDF
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->set_option('defaultFont', 'Courier');
            
            //Tabela
            $table = new CanvasGraph(800,700);
            $tabela = new GTextTable();
            $tabela->Set($this->tabelaDadosDias($dadosGrafico));
            $tabela->SetMinColWidth(200);
            $tabela->SetAlign("center");
            
            $table->Add($tabela);
            $gdImgHandlerTable = $table->Stroke(_IMG_HANDLER);
            $tableName = "/tmp/table.jpg";
            $table->img->Stream($tableName);
            
            
            //Criando a estrutura do PDF
            $dompdf->load_html('<!DOCTYPE html>
                <html>
                    <head>
                        <link rel="stylesheet" href="../../Backend/Styles/bootstrap.css" type="text/css">
                    </head>
                    <body>
                        <div class="container bg-dark p-2">
                            <h1 class="text-center text-white">Relatorio Mensal</h1>
                            <h3 class="text-center text-white">'. $this->nomeMes( $data->format("m") ).' de '.$data->format("Y").'</h3>
                        </div>
                        
                        <div class="container rounded mt-4 p-2">
                            <b>Total Consumido No Mês: '.$totalConsumo.' Litros
                            <br />
                            <b>Litros Por Dia: <b/>  L/D
                            <br />
                            <b>Litros Por Pessoa: <b/>  L/P
                        </div>
                        
                        <div class="container rounded mt-4 p-2">
                            <h3 class="text-center bg-dark p-2 text-white">Dados Detalhados Do Consumo</h3>
                            <img src="'.$tableName.'">
                        </div>
                        
                        <div class="container mt-4 p-2 text-center">
                            <h3 class="text-center text-white bg-dark p-2">Gráficos</h3>
                            <div class="container-fluid w-100">
                                <img src="'.$fileName.'" class="img-responsive">
                            </div>
                        </div>

                    </body>
                </html>
            ');
            
            //renderizar o html
            $dompdf->render();
            
            // pega o código fonte do novo arquivo PDF gerado
            $output = $dompdf->output();
            $caminho = "../../Backend/pdf/" . md5(time()).".pdf";
            // defina aqui o nome do arquivo que você quer que seja salvo
            file_put_contents($caminho, $output);
            
            /*Adicionando os dado no banco de dados*/
            $info = "Mensal " . $this->nomeMes( $data->format("m")) . " de " . $data->format("Y");
            $sqlAdd = $this->insere("relatorios", "id_sensor, id_user, link, info, data", "'$sensor', '".$this->user['id']."', '$caminho', '$info', '$datatime'");
            if(!$sqlAdd){
                echo "Houve algum erro: ".mysqli_error($sqlAdd);
            }
            
        }
        
        public function relatorios(){
            
            $sql = $this->select("relatorios", "*", "id_user = '".$this->user['id']."'", "id DESC");
            if(mysqli_num_rows($sql) > 0){
                while ($relatorio = mysqli_fetch_array($sql)){
                    $datas = new DateTime();
                    $datas->setTimestamp($relatorio['data']);
                    ?>
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="card-text">
                                <div class="row">
                                    
                                    <div class="col-2">
                                        <a href="<?php echo $relatorio['link'] ?>" target="_blank">
                                            <img src="../../Backend/Images/iconpdf.png" class="img-thumbnail">
                                        </a>
                                    </div>
                                    
                                    <div class="col-5">
                                        <b style="font-size: 15px;"><?php echo $relatorio['info']; ?></b>
                                        <i class="text-muted" style="font-size: 12px;"><?php  echo $datas->format("d/m/Y H:i:s") ?></i>
                                    </div>
                                    <div class="col-2" id="sendEmail" iden="<?php echo $relatorio['id'] ?>" lang="<?php echo $relatorio['link'] ?>"><img src="../../Backend/Images/send.png" class="img-thumbnail"></div>
                                    <div class="col-3" id="excluir" iden="<?php echo $relatorio['id'] ?>" lang="<?php echo $relatorio['link'] ?>"><button class="btn btn-danger btn-block">Excluir</button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }else{
                echo "Nenhum Relatório Gerado!";
            }
        }
        /*RELATÓRIOS*/
        
    
    /*APLICATIVO REACT NATIVE*/
    
    /*Pega o ID do usuário*/
    public function getIDUser($sql){
        $id = 0;
        while ($user = mysqli_fetch_array($sql)){
            $id = $user['id'];
        }
        return $id;
    }
    
    /*Todos os sensores do usuário*/
    public function getSensores($id){
        $dados = array();
        $sql = $this->select("sensores", "*", "id_user = '$id'");
        while($row = mysqli_fetch_array($sql)){
            $consumo = array($this->consumoMes($row['id']));
            $consumoDia = array($this->consumoDia($row['id']));
            $dados[] = array(
                'nome' => $row['nome'],
                'id' => $row['id'],
                'consumo_mes' => $consumo,
                'consumo_dia' => $consumoDia
            );
        }
        return $dados;
    }
    
    public function mediaMes($idUser){
        $mes = date("m");
        $ano = date("Y");
        $litrosPessoa = $this->litrosPessoaMes($idUser);
        $totalDias = 0;
        $sql = $this->select("sensores", "*", "id_user = $idUser");
        
        if(mysqli_num_rows($sql) > 0){
            
            while ($row = mysqli_fetch_array($sql)){
                
                $sql = $this->select("info_sensor", "DISTINCT dia", "id_sensor = '".$row['id']."' AND mes = '$mes' AND ano = '$ano'");
                if(mysqli_num_rows($sql) > 0){
                    $totalDias += mysqli_num_rows($sql);
                    while($rowDias = mysqli_fetch_array($sql)){
                       $totalDias += 1;
                    }
                    
                }else{
                    return 0;
                }
                
            }
            
        }else{
            return 0;
        }
        
        $media = ($litrosPessoa / $totalDias);
        return floatval($media);
    }
    
    //Consumo do mês
    private function consumoMes($idSensor){
        $mes = date("m");
        $dados = array();
        $ano = date("Y");
        
        $sqll = $this->select("info_sensor", "DISTINCT dia", "id_sensor = '$idSensor' AND mes = '$mes' AND ano = '$ano'", "dia ASC");
        
        if(mysqli_num_rows($sqll) > 0){
            while($rowDia = mysqli_fetch_array($sqll)){
                $totalDia = 0;
                $sql = $this->select("info_sensor", "*", "id_sensor = '$idSensor' AND mes = '$mes' AND dia = '".$rowDia['dia']."' AND ano = '$ano'");
                while($row = mysqli_fetch_array($sql)){
                    
                    $totalDia += $row['valor'];
                    
                }
                
                //valores de cada dia
                $dados['seriesName'] = "Consumo Mês";
                $dados['data'][] = array(
                    'x' => $rowDia['dia'],
                    'y' => round($totalDia, 2)
                );
            }
        }else{
            $dados['seriesName'] = "Consumo Mês";
            $dados['data'][] = array(
                'x' => "0",
                'y' => 0
            );
        }
        return $dados;
    }
    
    public function consumoDia($idSensor){
        $dia = date("d");
        $mes = date("m");
        $ano = date("Y");
        $dados = array();
        $r = rand(0, 255);
        $g = rand(0, 255);
        $b = rand(0, 255);
        
        //faz a busca pelos dados do sensor
        $sql = $this->select("info_sensor", "DISTINCT horas", "id_sensor = '$idSensor' AND dia = '$dia' AND mes = '$mes' AND ano = '$ano'", "horas ASC");
        if(mysqli_num_rows($sql) > 0){
            
            while($row = mysqli_fetch_array($sql)){
                
                //busca o consumo na hora
                $sqll = $this->select("info_sensor", "*", "id_sensor = '$idSensor' AND dia = '$dia' AND mes = '$mes' AND ano = '$ano' AND horas = '".$row['horas']."'");
                $totalConsumo = 0;
                while($rowHoras = mysqli_fetch_array($sqll)){
                    $totalConsumo += $rowHoras['valor']; 
                }
                
                //valores de cada dia
                $dados['seriesName'] = "Consumo Dia";
                $dados['data'][] = array(
                    'x' => $row['horas'],
                    'y' => round($totalConsumo, 2)
                );
            }
        }else{
            $dados['seriesName'] = "Consumo Mês";
            $dados['data'][] = array(
                'x' => "0",
                'y' => 0
            );
        }
        return $dados;
    }
    
    //Cria os dados do consumo do dia
    public function geraGraficoDia($idSensor){
        $dia = date("d");
        $mes = date("m");
        $ano = date("Y");
        $dados = array();
        $r = rand(0, 255);
        $g = rand(0, 255);
        $b = rand(0, 255);
        
        //faz a busca pelos dados do sensor
        $sql = $this->select("info_sensor", "DISTINCT horas", "id_sensor = '$idSensor' AND dia = '$dia' AND mes = '$mes' AND ano = '$ano'", "horas ASC");
        if(mysqli_num_rows($sql) > 0){
            
            while($row = mysqli_fetch_array($sql)){
                
                //busca o consumo na hora
                $sqll = $this->select("info_sensor", "*", "id_sensor = '$idSensor' AND dia = '$dia' AND mes = '$mes' AND ano = '$ano' AND horas = '".$row['horas']."'");
                $totalConsumo = 0;
                while($rowHoras = mysqli_fetch_array($sqll)){
                    $totalConsumo += $rowHoras['valor']; 
                }
                
                //valores de cada dia
                $dados['labels'][] = $row['horas'];
                $dados['datasets'][0]['data'][] = $totalConsumo;
            }
        }else{
            return;
        }
        return $dados;
    }
}