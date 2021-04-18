<?php
session_start();
if(!$_SESSION['login'])
{
  header("Location:../../../index.php");
}
error_reporting(0);
include_once '../../class/banco.class.php';
include_once '../../class/bairro.class.php';
$bairros = $bairro->listar();
$banco = new banco();
if($_POST[acao] == "pesquisar"){
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
				
				$condicao .= " AND (p.dt_inicial >= '".$dt_inicial."' and p.dt_inicial <= '".$dt_final."')";
			}
			if($dt_inicial && !$dt_final){
				$condicao .= " AND p.dt_inicial >= '".$dt_inicial."'";
			}
			
			if($_POST[bairro]){
				$condicao .= " AND c.bairro = '".$_POST[bairro]."'";
			}
		}
}


$sql = 'SELECT c.nome, c.bairro, c.logradouro, c.telefone, c.celular, CONCAT(SUBSTRING(p.dt_inicial, 9), "/", SUBSTRING(p.dt_inicial, 6, 2), "/", SUBSTRING(p.dt_inicial, 1, 4)) AS dt_inicial from clientes c 
		inner join pedidos p on c.id = p.id_cliente WHERE 1 = 1 '.$condicao.'
		group by p.protocolo ';
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
            <h1 class="m-0 text-dark">QUANTIDADE DE ATENDIMENTOS POR ENDEREÇO</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Página inicial</a></li>
              <li class="breadcrumb-item active">Relatórios</li>
              <li class="breadcrumb-item active">Quantidade de atendimentos por endereço</li>
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
					<td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-pesquisa">Escolher bairro</button></td>
					<td><input type="text" class="form-control" name="bairro" id="bairro" readonly></td>
					<td>&nbsp</td>
					<td><button type="button" class="btn btn-info">Data inicial</button></td>
					<td><input type="text" class="form-control" name="dt_inicial" id="dt_inicial"></td>
					<td>&nbsp</td>
					<td><button type="button" class="btn btn-info">Data final</button></td>
					<td><input type="text" class="form-control" name="dt_final" id="dt_final"></td>
					<td><input type="hidden" class="form-control" name="acao" id="acao" value = "pesquisar"></td>
					<td>&nbsp</td>
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
                   <th>Nome</th>
                   <th>Bairro</th>
                   <th>Rua</th>
                   <th>Contatos</th>
				   <th>Data do atendimento</th>
                  </tr>
                  </thead>
                  <tbody>
				  <?php
				  for($i = 0; $i < count($rs); $i++)
				  {
					echo
					'<tr>
						<td>'.$rs[$i][nome].'</td>
						<td>'.$rs[$i][bairro].'</td>
						<td>'.$rs[$i][logradouro].'</td>';
						if($rs[$i][telefone] && $rs[$i][celular]){
							echo '<td>'.$rs[$i][telefone].' / '.$rs[$i][celular].'</td>';
						}elseif($rs[$i][telefone] && !$rs[$i][celular]){
							echo '<td>'.$rs[$i][telefone].'</td>';
						}else{
							echo '<td>'.$rs[$i][celular].'</td>';
							}
						echo '
						<td>'.$rs[$i][dt_inicial].'</td>
					</tr>';
				  }
					?>
                  </tbody>
                  <tfoot>
                  <tr>
					<th>Nome</th>
					<th>Bairro</th>
					<th>Rua</th>	
					<th>Contatos</th>
					<th>Data do atendimento</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
      <div class="modal fade" id="modal-pesquisa">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Pesquisar Bairro</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body">
          <div class="form-group">
          <label>Nome</label>
          <select class="form-control select2" name = "bairroSelecionado" id = "bairroSelecionado" style="width: 100%;">
            <option selected>Selecione</option>
            <?php
              for($i = 0; $i < count($bairros); $i++)
              {
              	if($bairros[$i]['bairro'] != "")
                echo '<option value = "'.$bairros[$i]['bairro'].'">'.$bairros[$i]['bairro'].'</option>';
              }
            ?>
          </select>
                </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button onClick="selecionarBairro();" class="btn btn-info">Selecionar</button>
                  <button type="button" id="close-modal" class="btn btn-default" data-dismiss="modal" style ="display:none"></button>
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
<script src="../../dist/js/adminlte.min.js"></script>
<!-- Toastr -->
<script src="../../plugins/toastr/toastr.min.js"></script>
<!-- SweetAlert2 -->
<script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
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

	
function selecionarBairro()
{
	var bairro_pesquisa = document.getElementById("bairro");
	var bairro_selecionado = document.getElementById("bairroSelecionado");
	
	bairro_pesquisa.value = bairro_selecionado.value;
	$('#close-modal').click();
}

function planilha(){

	<?php
	echo 'window.open("planilhas/atendimentoPorEnderecoPlanilha.php?dt_inicial='.$dt_inicial.'&dt_final='.$dt_final.'&bairro='.$_POST[bairro].'");';
	?>
}

</script>
</body>
</html>
