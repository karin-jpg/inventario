<?php
session_start();
if(!$_SESSION['login'])
{
  header("Location:../../../index.php");
}

include_once '../../class/remedio.class.php';
include_once '../../class/principioAtivo.class.php';
error_reporting(0);
$remedios = $remedio->listar();
$pa = $principio_ativo->listar();

	if($_POST['acao'] == "novo"){
		if($remedio->salvar($_POST['remedio'], $_POST['pa'], $_POST['quantidade']))
		{
			$s = 1;
		}else {
			$s = 0;
		}
	}

	if($_POST['acao'] == "editar"){
		if($remedio->editar($_POST['id'], $_POST['remedio'], $_POST['pa'], $_POST['quantidade']))
			{
				$s = 1;
			}else {
				$s = 0;
			}
		}
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
            <h1 class="m-0 text-dark">REMÉDIOS</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Página inicial</a></li>
              <li class="breadcrumb-item active">Remédios</li>
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
            <div class="card">
              <!-- /.card-header -->
            <!-- /.card -->
            <div class="card">
  			<div class="row mb-2">
			  <div class="card-header  col-sm-8">
              </div>
                <div class="card-header col-sm-2">
			  			<button type="button" class="btn btn-block btn-outline-info"  onclick = "alterarCad();">Editar</button>
			</div>
			  <div class="card-header col-sm-2">
						<button type="button" class="btn btn-block btn-outline-primary" data-toggle="modal" data-target="#modal-default" >Novo</button>

			</div>
			</div>
			  
              <!-- /.card-header -->
              <div class="card-body">
                <table id="tableCliente" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Nome do Remédio</th>
                    <th>Princípio ativo</th>
                    <th>Quantidade</th>
                    <th>Selecione</th>
                  </tr>
                  </thead>
                  <tbody>
				  <?php
				  for($i = 0; $i < count($remedios); $i++)
				  {
					echo
					'<tr>
						<td id = "remedio_'.$remedios[$i]['id'].'">'.$remedios[$i]['nome'].'</td>
						<td id = "pa_'.$remedios[$i]['id'].'">'.$remedios[$i]['pa_nome'].'</td>
						<td id = "quantidade_'.$remedios[$i]['id'].'">'.$remedios[$i]['quantidade'].'</td>
						<td><input type="checkbox" name="selecionados[]" id = "'.$remedios[$i]['id'].'|'.$remedios[$i]['pa_id'].'"></td>
					</tr>';
				  }
					?>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Nome do Remédio</th>
                    <th>Princípio ativo</th>
                    <th>Quantidade</th>
                    <th>Selecione</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
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
  <!-- /.content-wrapper -->
 <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Novo Remédio</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" id = "form" method="post" action="remedios.php">
                <div class="card-body">
                  <div class="form-group">
                    <label>Remédio</label>
                    <input type="text" class="form-control" name="remedio" id = "remedio">
                  </div>
				  <input type="hidden" name="acao" id = "acao" value = "novo">
				  <div class="form-group">
					<label>Principio Ativo</label>
					<select class="form-control select2" name = "pa" id = "pa" style="width: 100%;">
						<option selected="selected">Selecione</option>
						<?php
							for($i = 0; $i < count($pa); $i++)
							{
								echo '<option value = "'.$pa[$i]['id'].'">'.$pa[$i]['nome'].'</option>';
							}
						?>
					</select>
                </div>
				<div class="form-group">
                    <label>Quantidade</label>
                    <input type="text" class="form-control" name = "quantidade" id = "quantidade">
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" method="post" onClick="return validaCad(1);" class="btn btn-primary">Cadastrar</button>
                </div>
              </form>
            </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>




<div class="modal fade" id="modal-edit">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Editar remédio</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" id = "form" method="post" action="remedios.php">
                <div class="card-body">
                  <div class="form-group">
                    <label>Nome</label>
                    <input type="text" class="form-control" name="remedio" id = "remedio-edit">
                  </div>
				  <input type="hidden" name="acao" id = "acao" value = "editar">
				  <input type="hidden" name="id" id = "id-edit">
				  <div class="form-group">
					<label>Princípio ativo</label>
					<select class="form-control select2" name = "pa" id = "pa-edit" style="width: 100%;">
						<option selected="selected">Selecione</option>
						<?php
							for($i = 0; $i < count($pa); $i++)
							{
								echo '<option value = "'.$pa[$i]['id'].'">'.$pa[$i]['nome'].'</option>';
							}
						?>
					</select>
                </div>
				<div class="form-group">
                    <label>Quantidade</label>
                    <input type="text" class="form-control" name = "quantidade" id = "quantidade-edit">
                  </div>

                <div class="card-footer">
                  <button type="submit" method="post" onClick="return validaCad(2);" class="btn btn-info">Salvar</button>
                </div>
              </form>
            </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
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
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- Toastr -->
<script src="../../plugins/toastr/toastr.min.js"></script>
<!-- SweetAlert2 -->
<script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<!-- page script -->
<script>
  $(function () {
	
	const Toast = Swal.mixin({
      toast: true,
      showConfirmButton: false,
      timer: 3000
    });
  

	  
    $("#tableCliente").DataTable({
      "responsive": true,
      "autoWidth": false,
	  "bFilter": true,
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
	
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });

  <?php
 if($_POST['acao'] == "novo")
 {
	if($s == 1){
 ?>
	alert("Cadastro realizado com sucesso!");
 <?php
}else if ($s == 0){
?>
	alert("Houve um problema com o cadastro!");
<?php
	}
 }

 if($_POST['acao'] == "editar")
 {
	if($s == 1){
 ?>
	alert("Cadastro alterado com sucesso!");
 <?php
}else if ($s == 0){
?>
	alert("Houve um problema com o cadastro!");
<?php
	}
}
 ?>

function validaCad(acao)
  {
  	if(acao == 1){
  		var remedio = document.getElementById("remedio").value;
  		var pa = document.getElementById("pa").value;
  		var quantidade = document.getElementById("quantidade").value;
  	}else{
  		var remedio = document.getElementById("remedio-edit").value;
  		var pa = document.getElementById("pa-edit").value;
  		var quantidade = document.getElementById("quantidade-edit").value;
  	}
  	var msg = "";
  	if(remedio == "")
  		msg += "Informe o nome do remédio";
    if(pa == "Selecione")
		msg += "\nSelecione o principio ativo";
	if(quantidade == "")
		msg += "\nInforme a quantidade";
    if(isNaN(quantidade))
    	msg += "\nInforme somente numero";


    if(msg == "")
	{
		document.form.submit();
	}
	else
	alert(msg);
     return false;
  }
  
function alterarCad(){

  var arrayBoletos = new Array();
	var checkboxes = document.getElementsByName("selecionados[]");
	var count = 0;
	var cadastro = 0;
  var pa = 0;
	for(var i = 0; i < checkboxes.length; i++) {
		if(checkboxes[i].checked == true){
			count++;
			cadastro = checkboxes[i].id.split("|")[0];
      pa = checkboxes[i].id.split("|")[1];
		}
	}
	if(count == 0){
		alert("Selecione um cadastro!");

	}else if(count > 1){
		alert("Selecione somente um cadastro por vez!");

	}else{
	var remedio = "remedio_"+cadastro;
	var pa_id = pa;
	var quantidade = "quantidade_"+cadastro;
	var id = cadastro;
	var valor_remedio = document.getElementById(remedio).innerHTML;
	var valor_quantidade = document.getElementById(quantidade).innerHTML;
  	$("#modal-edit").modal({
    show: true
  });
  	  	$("#remedio-edit").val(valor_remedio);
        $("#pa-edit").val(pa_id);
  	  	$("#quantidade-edit").val(valor_quantidade);
  	  	$("#id-edit").val(cadastro);
  }
}

</script>
</body>
</html>


