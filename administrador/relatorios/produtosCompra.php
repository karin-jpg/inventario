<?php
session_start();
error_reporting(0);
if(!isset($_SESSION['id-usuario'])){
  header("location: ../../index.php");
}

function verificaQuantidade($quantidade, $quantidade_min, $quantidade_ideal){
  if($quantidade >= $quantidade_ideal)
            {
              return "table-default";
            }else if($quantidade < $quantidade_ideal && $quantidade > $quantidade_min){
              return "table-warning";
            }else{
              return "table-danger";
            }
}

include_once('../../class/relatorios.class.php');
include_once('../../class/produto.class.php');
include_once('../../class/loja.class.php');
include_once('../../class/fornecedor.class.php');
$relatorio = $relatorios->produtosCompra($_POST['id-produto'], $_POST['id-loja'], $_POST['id-fornecedor']);
$produtos = $produto->listar();
$lojas = $loja->listar();
$fornecedores = $fornecedor->listar();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 3 | DataTables</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
	
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../estilo/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../estilo/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../estilo/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../estilo/dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
	 <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Produtos para compra</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Página inicial</a></li>
              <li class="breadcrumb-item active">Relatórios</li>
              <li class="breadcrumb-item active">Produtos para compra</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- /.card -->
            <div class="card">
             
			  <div class="card-header  col-sm-12">
             
			</div>
			  
              <!-- /.card-header data-toggle="modal" data-target="#modal-edit"-->
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <div class="card-body">
  <form role="form" id = "form" method="post" action="">
		<table>
			<tbody>
				<tr>
					<td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-pesquisa-produto">Produto</button></td>
					<td><input type="text" class="form-control" name="produto" id="produto" readonly></td>
          <td><input type="hidden" class="form-control" name="id-produto" id="id-produto"></td>
					<td>&nbsp</td>
          <td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-pesquisa-loja">Loja</button></td>
          <td><input type="text" class="form-control" name="loja" id="loja" readonly></td>
          <td><input type="hidden" class="form-control" name="id-loja" id="id-loja"></td>
          <td>&nbsp</td>
          <td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-pesquisa-fornecedor">Fornecedor</button></td>
          <td><input type="text" class="form-control" name="fornecedor" id="fornecedor" readonly></td>
          <td><input type="hidden" class="form-control" name="id-fornecedor" id="id-fornecedor"></td>
          <td><button type="button" type="submit" method="post" onClick="return pesquisar();" class="btn btn-info"><i class="fas fa-search"></i></button></td>
          <td>&nbsp</td>
          <td><button type="button" type="submit" method="post" onClick="planilha();" class="btn btn-info"><i class="fas fa-download"></i></button></td>
				</tr>		
			</tbody>
		</table>
  </form>
  </div>
               <div class="card-body">
                <table id="tableRelatorio" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Produto</th>
                    <th>Loja</th>
                    <th>Fornecedor</th>
                    <th>Quantidade atual</th>
                    <th>Quantidade min.</th>
                    <th>Quantidade ideal</th>
                    <th>Quantidade para atingir o min.</th>
                    <th>Quantidade para atingir o ideal</th>
                    <th>Valor de compra min.</th>
                    <th>Valor de compra ideal.</th>
                  </tr>
                  </thead>
                  <tbody>
				  <?php
				  for($i = 0; $i < count($relatorio); $i++)
				  {
					echo
					'<tr class = "'.verificaQuantidade($relatorio[$i]['quantidade_atual'], $relatorio[$i]['min'], $relatorio[$i]['ideal']).'">
						<td>'.$relatorio[$i]['produto'].'</td>
            <td>'.$relatorio[$i]['loja'].'</td>
            <td>'.$relatorio[$i]['fornecedor'].'</td>
            <td>'.$relatorio[$i]['quantidade_atual'].'</td>
            <td>'.$relatorio[$i]['ideal'].'</td>
            <td>'.$relatorio[$i]['min'].'</td>
            <td>'.$relatorio[$i]['quantidade_para_ideal'].'</td>
            <td>'.$relatorio[$i]['quantidade_para_min'].'</td>
            <td>'.$relatorio[$i]['preco_ideal'].'</td>
            <td>'.$relatorio[$i]['preco_min'].'</td>
					</tr>';
				  }
					?>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Produto</th>
                    <th>Loja</th>
                    <th>Fornecedor</th>
                    <th>Quantidade atual</th>
                    <th>Quantidade min.</th>
                    <th>Quantidade ideal</th>
                    <th>Quantidade para atingir o min.</th>
                    <th>Quantidade para atingir o ideal</th>
                    <th>Valor de compra para quantidade min.</th>
                    <th>Valor de compra para quantidade ideal</th>
                  </tr>
                  </tfoot>
                </table>
              </div>

      <div class="modal fade" id="modal-pesquisa-produto">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Produto</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body">
          <div class="form-group">
          <label>Nome</label>
          <select class="form-control select2" name = "produto-selecionado" id = "produto-selecionado" style="width: 100%;">
            <option selected>Selecione</option>
            <?php
              for($i = 0; $i < count($produtos); $i++)
              {
                echo '<option value = "'.$produtos[$i]['id'].'">'.$produtos[$i]['nome'].'</option>';
              }
            ?>
          </select>
                </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button onClick="selecionarProduto();" class="btn btn-info">Selecionar</button>
                  <button type="button" id="close-modal-produto" class="btn btn-default" data-dismiss="modal" style ="display:none"></button>
                </div>
            </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>

      <div class="modal fade" id="modal-pesquisa-loja">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Loja</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body">
          <div class="form-group">
          <label>Nome</label>
          <select class="form-control select2" name = "loja-selecionada" id = "loja-selecionada" style="width: 100%;">
            <option selected>Selecione</option>
            <?php
              for($i = 0; $i < count($lojas); $i++)
              {
                echo '<option value = "'.$lojas[$i]['id'].'">'.$lojas[$i]['nome'].'</option>';
              }
            ?>
          </select>
                </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button onClick="selecionarLoja();" class="btn btn-info">Selecionar</button>
                  <button type="button" id="close-modal-loja" class="btn btn-default" data-dismiss="modal" style ="display:none"></button>
                </div>
            </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>

      <div class="modal fade" id="modal-pesquisa-fornecedor">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Fornecedor</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body">
          <div class="form-group">
          <label>Nome</label>
          <select class="form-control select2" name = "fornecedor-selecionado" id = "fornecedor-selecionado" style="width: 100%;">
            <option selected>Selecione</option>
            <?php
              for($i = 0; $i < count($fornecedores); $i++)
              {
                echo '<option value = "'.$fornecedores[$i]['id'].'">'.$fornecedores[$i]['nome'].'</option>';
              }
            ?>
          </select>
                </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button onClick="selecionarFornecedor();" class="btn btn-info">Selecionar</button>
                  <button type="button" id="close-modal-fornecedor" class="btn btn-default" data-dismiss="modal" style ="display:none"></button>
                </div>
            </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      
  <!-- /.content-wrapper -->
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
<!-- jQuery -->
<script src="../../estilo/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../estilo/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="../../estilo/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../estilo/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../estilo/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../estilo/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script>
    $("#tableRelatorio").DataTable({
      "responsive": true,
      "autoWidth": false,
	  "bFilter": false,
		"bInfo": true,
		"pageLength": 10,
		"bLengthChange": true,
    "aaSorting": false,
		"language": {		
				 
				"sEmptyTable": "Nenhum registro encontrado",
				"sInfo": "Mostrando de _START_ at&eacute; _END_ de _TOTAL_ registros",
				"sInfoEmpty": "Mostrando 0 at&eacute; 0 de 0 registros",
				"sInfoFiltered": "(Filtrados de MAX registros)",
				"sInfoPostFix": "",
				"sInfoThousands": "",
				"sLengthMenu": "_MENU_ resultados por p&aacute;gina",
				"sLoadingRecords": "Carregando...",
				"sProcessing": "Processando...",
				"sZeroRecords": "Nenhum registro encontrado",
				"sSearch": "Pesquisar",
				"oPaginate": {
					"sNext": "Pr&oacute;ximo",
					"sPrevious": "Anterior",
					"sFirst": "Primeiro",
					"sLast": "&Uacute;ltimo"
				},
				"oAria": {
					"sSortAscending": ": Ordenar colunas de forma ascendente",
					"sSortDescending": ": Ordenar colunas de forma descendente"
				}
		}
    });

 <?php
 if($_POST['acao'] == "pesquisar")
 {
	if($erro){

	echo 'alert('.$msg.');';
}
 }
?>
	
function pesquisar(){
	form.submit();
}	

	
function selecionarProduto(){

  
	var produto_pesquisa = document.querySelector("#produto");
  var id_produto_selecionado= document.querySelector("#id-produto")
	var produto_selecionado= document.querySelector("#produto-selecionado");

	produto_pesquisa.value = document.querySelector('#produto-selecionado').options[document.querySelector('#produto-selecionado').selectedIndex].text;
	id_produto_selecionado.value = produto_selecionado.value;
	$('#close-modal-produto').click();
}


function selecionarLoja(){

  
var loja_pesquisa = document.querySelector("#loja");
var id_loja_selecionado= document.querySelector("#id-loja")
var loja_selecionado= document.querySelector("#loja-selecionada");

loja_pesquisa.value = document.querySelector('#loja-selecionada').options[document.querySelector('#loja-selecionada').selectedIndex].text;
id_loja_selecionado.value = loja_selecionado.value;
$('#close-modal-loja').click();
}

function selecionarFornecedor(){

  
var fornecedor_pesquisa = document.querySelector("#fornecedor");
var id_fornecedor_selecionado= document.querySelector("#id-fornecedor")
var fornecedor_selecionado= document.querySelector("#fornecedor-selecionado");

fornecedor_pesquisa.value = document.querySelector('#fornecedor-selecionado').options[document.querySelector('#fornecedor-selecionado').selectedIndex].text;
id_fornecedor_selecionado.value = fornecedor_selecionado.value;
$('#close-modal-fornecedor').click();
}


function planilha(){

  <?php
  echo 'window.open("planilhas/produtosCompraPlanilha.php");';
  ?>
}

</script>
</body>
</html>
