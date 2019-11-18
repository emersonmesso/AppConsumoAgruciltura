<!--MENU BAIXO-->
<div class="container-fluid position-fixed p-2" id="menuBaixo">
    
    <div class="row">
        <div class="col-6 text-center">
            <button type="button" class="btn btn-link w-100 h-100 text-info" id="btnHome" style="font-size: 35px;"><i class="fas fa-home"></i></button>
        </div>
        
        <div class="col-6 text-center">
            <button type="button" class="btn btn-link w-100 h-100 text-info" style="font-size: 35px;"><i class="fas fa-file-export"></i></button>
        </div>
    </div>
    
</div>
<!--MENU BAIXO-->

<!--MOSTRA ERROS-->
<div class="p-2 text-center text-white bg-danger rounded" id="telaErros">
    
</div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Deseja Sair?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmSair">Sair</button>
                </div>
            </div>
        </div>
    </div>

    <!---->
    <script src="Backend/Script/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="Backend/Script/jquery.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.js"></script>
    <script src="../../Backend/Script/relatorios.js"></script>
    </body>
</html>