<?php
session_start();
if(!$_SESSION['login'])
{
  header("Location:../../../index.php");
}
error_reporting(0);
include_once '../../class/banco.class.php';
include_once '../../class/remedio.class.php';
include_once '../../class/principioAtivo.class.php';
include_once '../../class/cliente.class.php';
$remedios = $remedio->listar();
$pa = $principio_ativo->listar();
$clientes = $cliente->listar();
$banco = new banco();

if($_POST[acao] == "pesquisar")
{
    $condicao = "";
  $erro = 0;
  if(!$_POST[dt_inicial] && $_POST[dt_final]){
    
    $erro = 1;
    $msg = '"Necessário informar uma data inicial junto da data final!"';
  }elseif($_POST[dt_inicial] && $_POST[dt_final]){
    
      $dt_inicial = explode("/",$_POST[dt_inicial]);
      $dt_final = explode("/",$_POST[dt_final]);
      $diaI = $dt_inicial[0];
      $mesI = $dt_inicial[1];
      $anoI = $dt_inicial[2];
      
      $diaF = $dt_final[0];
      $mesF = $dt_final[1];
      $anoF = $dt_final[2];
      
      if(!checkdate($mesI, $diaI, $anoI))
      {
        $erro = 1;
        $msg = '"Data inicial inválida!"';
      }else{
        
        if(!checkdate($mesF, $diaF, $anoF)){
          $erro = 1;
          $msg = '"Data final inválida!"';
          
        }else{
          $dt_inicial = $anoI."-".$mesI."-".$diaI;
          $dt_final = $anoF."-".$mesF."-".$diaF;
          
          if(strtotime($dt_inicial) > strtotime($dt_final)){
            $erro = 1;
            $msg = '"Data inicial não pode ser maior que a data final!"';
            }
        }
      }
    } elseif($_POST[dt_inicial]){
      $data = explode("/",$_POST[dt_inicial]);
      $diaI = $data[0];
      $mesI = $data[1];
      $anoI = $data[2];
      if(!checkdate($mesI, $diaI, $anoI))
      {
        $erro = 1;
        $msg = '"Data inicial inválida!"';
      }else
      {
        $dt_inicial = $anoI."-".$mesI."-".$diaI;
      }
    }
    
    if(!$erro){
      if($dt_inicial && $dt_final){
        
        $condicao .= " AND (p.dt_final >= '".$dt_inicial."' and p.dt_final <= '".$dt_final."')";
      }
      if($dt_inicial && !$dt_final){
        $condicao .= " AND p.dt_final >= '".$dt_inicial."'";
      }
      
      if($_POST[remedio]){
        $condicao .= " AND r.nome = '".$_POST[remedio]."'";
      }

      if($_POST[pa]){
        $condicao .= " AND pa.nome_principio_ativo = '".$_POST[pa]."'";
      }

      if($_POST[cliente]){
        $condicao .= " AND c.nome = '".$_POST[cliente]."'";
      }

      if($_POST[protocolo]){
        $condicao .= " AND p.protocolo = '".$_POST[protocolo]."'";
      }
    }

}
	$sql = 'SELECT r.nome as remedio_nome,  pa.nome_principio_ativo as nome_pa, p.quantidade, c.nome as cliente_nome, CONCAT(SUBSTRING(p.dt_final, 9), "/", SUBSTRING(p.dt_final, 6, 2), "/", SUBSTRING(p.dt_final, 1, 4)) AS dt_final, p.protocolo FROM pedidos p 
          INNER JOIN clientes c ON (p.id_cliente = c.id)
          INNER JOIN remedios r ON (r.id = p.id_remedios)
          INNER JOIN principio_ativo pa ON (r.principio_ativo_id = pa.id)
          WHERE p.dt_final is not null'.$condicao;
$rs = $banco->executa($sql);
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
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
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
            <h1 class="m-0 text-dark">SAÍDA DE REMÉDIOS</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Página inicial</a></li>
              <li class="breadcrumb-item active">Relatórios</li>
              <li class="breadcrumb-item active">Saída de Remédios</li>
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
					<td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-pesquisa-remedio">Escolher Remédio</button></td>
					<td><input type="text" class="form-control" name="remedio" id="remedio" readonly></td>
					<td>&nbsp</td>
          <td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-pesquisa-pa">Escolher Princípio Ativo</button></td>
          <td><input type="text" class="form-control" name="pa" id="pa" readonly></td>
          <td>&nbsp</td>
          <td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-pesquisa-cliente">Cliente</button></td>
          <td><input type="text" class="form-control" name="cliente" id="cliente" readonly></td>
          <td>&nbsp</td>
          <td><button type="button" class="btn btn-info">Protocolo</button></td>
          <td><input type="text" class="form-control" name="protocolo" id="protocolo"></td>
          <td>&nbsp</td>
          <td><button type="button" type="submit" method="post" onClick="return pesquisar();" class="btn btn-info"><i class="fas fa-search"></i></button></td>
          <td>&nbsp</td>
          <td><button type="button" type="submit" method="post" onClick="planilha();" class="btn btn-info"><i class="fas fa-download"></i></button></td>
				</tr>		
        <tr>
          <td><button type="button" class="btn btn-info">Data inicial</button></td>
          <td><input type="text" class="form-control" name="dt_inicial" id="dt_inicial"></td>
          <td>&nbsp</td>
          <td><button type="button" class="btn btn-info">Data final</button></td>
          <td><input type="text" class="form-control" name="dt_final" id="dt_final"></td>
          <td><input type="hidden" class="form-control" name="acao" id="acao" value = "pesquisar"></td>
        </tr>
			</tbody>
		</table>
  </form>
  </div>
               <div class="card-body">
                <table id="tableRelatorio" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                   <th>Nome do Remédio</th>
                    <th>Princípio ativo</th>
                    <th>Quantidade da saída</th>
                    <th>Cliente</th>
                    <th>Protocolo</th>
                    <th>Data de saída</th>
                  </tr>
                  </thead>
                  <tbody>
				  <?php
				  for($i = 0; $i < count($rs); $i++)
				  {
					echo
					'<tr>
						<td>'.$rs[$i][remedio_nome].'</td>
						<td>'.$rs[$i][nome_pa].'</td>
						<td>'.$rs[$i][quantidade].'</td>
            <td>'.$rs[$i][cliente_nome].'</td>
            <td>'.$rs[$i][protocolo].'</td>
            <td>'.$rs[$i][dt_final].'</td>
					</tr>';
				  }
					?>
                  </tbody>
                  <tfoot>
                  <tr>
					         <th>Nome do Remédio</th>
                    <th>Princípio ativo</th>
                    <th>Quantidade da saída</th>
                    <th>Cliente</th>
                    <th>Protocolo</th>
                    <th>Data de saída</th>
                  </tr>
                  </tfoot>
                </table>
              </div>

      <div class="modal fade" id="modal-pesquisa-remedio">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Pesquisar Remedio</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body">
          <div class="form-group">
          <label>Nome</label>
          <select class="form-control select2" name = "remedioSelecionado" id = "remedioSelecionado" style="width: 100%;">
            <option selected>Selecione</option>
            <?php
              for($i = 0; $i < count($remedios); $i++)
              {
                echo '<option value = "'.$remedios[$i]['nome'].'">'.$remedios[$i]['nome'].'</option>';
              }
            ?>
          </select>
                </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button onClick="selecionarRemedio();" class="btn btn-info">Selecionar</button>
                  <button type="button" id="close-modal-remedio" class="btn btn-default" data-dismiss="modal" style ="display:none"></button>
                </div>
            </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>

      <div class="modal fade" id="modal-pesquisa-pa">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Pesquisar Princípio Ativo</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body">
          <div class="form-group">
          <label>Nome</label>
          <select class="form-control select2" name = "paSelecionado" id = "paSelecionado" style="width: 100%;">
            <option selected>Selecione</option>
            <?php
              for($i = 0; $i < count($pa); $i++)
              {
                echo '<option value = "'.$pa[$i]['nome'].'">'.$pa[$i]['nome'].'</option>';
              }
            ?>
          </select>
                </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button onClick="selecionarPa();" class="btn btn-info">Selecionar</button>
                  <button type="button" id="close-modal-pa" class="btn btn-default" data-dismiss="modal" style ="display:none"></button>
                </div>
            </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>

      <div class="modal fade" id="modal-pesquisa-cliente">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Pesquisar Cliente</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body">
          <div class="form-group">
          <label>Nome</label>
          <select class="form-control select2" name = "clienteSelecionado" id = "clienteSelecionado" style="width: 100%;">
            <option selected>Selecione</option>
            <?php
              for($i = 0; $i < count($clientes); $i++)
              {
                echo '<option value = "'.$clientes[$i]['nome'].'">'.$clientes[$i]['nome'].'</option>';
              }
            ?>
          </select>
                </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button onClick="selecionarCliente();" class="btn btn-info">Selecionar</button>
                  <button type="button" id="close-modal-cliente" class="btn btn-default" data-dismiss="modal" style ="display:none"></button>
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
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../../js/jquery.mask.min.js"></script>
<!-- AdminLTE App -->
<script src="../../../dist/js/adminlte.min.js"></script>
<!-- Toastr -->
<script src="../../../plugins/toastr/toastr.min.js"></script>
<!-- SweetAlert2 -->
<script src="../../../plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../../dist/js/demo.js"></script>
<script>
    $("#tableRelatorio").DataTable({
      "responsive": true,
      "autoWidth": false,
	  "bFilter": false,
		"bInfo": true,
		"pageLength": 10,
		"bLengthChange": true,
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
	
	
	$(document).ready(function () {
        $('#dt_inicial').mask('99/99/9999');
		$('#dt_final').mask('99/99/9999');
        return false;
    });
	
function pesquisar(){
	var form = document.getElementById("form");
	var dt_inicial = document.getElementById("dt_inicial");
	var dt_final = document.getElementById("dt_final");
	
	if(dt_inicial.value != "" && dt_inicial.value.length != 10){
			alert("Formato de data inicial inválido!");
			return false;
		}
	if(dt_final.value != "" && dt_final.value.length != 10){
			alert("Formato de data final inválido!");
			return false;
		}

	if(dt_final.value != "" && dt_inicial.value == ""){
		alert("Preencha a data inicial também!");
			return false;
	}
	form.submit();
	
}	

	
function selecionarRemedio(){
	var remedio_pesquisa = document.getElementById("remedio");
	var remedio_selecionado = document.getElementById("remedioSelecionado");
	
	remedio_pesquisa.value = remedio_selecionado.value;
	$('#close-modal-remedio').click();
}

function selecionarPa(){
  var pa_pesquisa = document.getElementById("pa");
  var pa_selecionado = document.getElementById("paSelecionado");
  
  pa_pesquisa.value = pa_selecionado.value;
  $('#close-modal-pa').click();
}

function selecionarCliente(){
  var cliente_pesquisa = document.getElementById("cliente");
  var cliente_selecionado = document.getElementById("clienteSelecionado");
  
  cliente_pesquisa.value = cliente_selecionado.value;
  $('#close-modal-cliente').click();
}

function planilha(){

  <?php
  echo 'window.open("planilhas/saidaDePedidosPlanilha.php?dt_inicial='.$dt_inicial.'&dt_final='.$dt_final.'&remedio='.$_POST[remedio].'&pa='.$_POST[pa].'&cliente='.$_POST[cliente].'&protocolo='.$_POST[protocolo].'");';
  ?>
}

</script>
</body>
</html>
