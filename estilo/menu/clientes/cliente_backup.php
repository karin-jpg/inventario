<?php
include_once '../../class/cliente.class.php';
include_once '../../class/bairro.class.php';
error_reporting(0);
$clientes = $cliente->listar();
$bairros = $bairro->listar();
	
	
	if($_POST['acao'] == "novo"){
		if($cliente->salvar($_POST['nome'], $_POST['rg'], $_POST['bairro'], $_POST['endereco'], $_POST['numero'], $_POST['telefone'], $_POST['email']))
		{
			$s = 1;
		}else {
			$s = 0;
		}
}

	if($_POST['acao'] == "editar"){
		if($cliente->editar($_POST['id'], $_POST['nome'], $_POST['rg'], $_POST['bairro'], $_POST['endereco'], $_POST['numero'], $_POST['telefone'], $_POST['email']))
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
            <h1 class="m-0 text-dark">CLIENTES</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Página inicial</a></li>
              <li class="breadcrumb-item active">Clientes</li>
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
			  			<button type="button" class="btn btn-block btn-outline-warning"  onclick = "alterarCad();">Editar</button>
			</div>
			  <div class="card-header col-sm-2">
						<button type="button" class="btn btn-block btn-outline-primary" data-toggle="modal" data-target="#modal-default" >Novo</button>
			</div>
			</div>
			  
              <!-- /.card-header data-toggle="modal" data-target="#modal-edit"-->
              <div class="card-body">
                <table id="tableCliente" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Nome</th>
                    <th>RG</th>
                    <th>Bairro</th>
                    <th>Endereço</th>
                    <th>Número</th>
					<th>Telefone</th>
					<th>Email</th>
					<th>Selecione</th>
                  </tr>
                  </thead>
                  <tbody>
				  <?php
				  for($i = 0; $i < count($clientes); $i++)
				  {
					echo
					'<tr>
						<td id = "nome_'.$clientes[$i]['id'].'">'.$clientes[$i]['nome'].'</td>
						<td id = "rg_'.$clientes[$i]['id'].'">'.$clientes[$i]['rg'].'</td>
						<td id = "bairro_'.$clientes[$i]['id'].'">'.$clientes[$i]['bairro_nome'].'</td>
						<td id = "endereco_'.$clientes[$i]['id'].'">'.$clientes[$i]['endereco'].'</td>
						<td id = "numero_'.$clientes[$i]['id'].'">'.$clientes[$i]['numero'].'</td>
						<td id = "telefone_'.$clientes[$i]['id'].'">'.(($clientes[$i]['telefone'] != "") ? $clientes[$i]['telefone'] : "-").'</td>
						<td id = "email_'.$clientes[$i]['id'].'">'.(($clientes[$i]['email'] != "") ? $clientes[$i]['email'] : "-").'</td>
						<td><input type="checkbox" name="selecionados[]" id = "'.$clientes[$i]['id'].'"></td>
					</tr>';
				  }
					?>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Nome</th>
                    <th>RG</th>
                    <th>Bairro</th>
                    <th>Endereço</th>
                    <th>Número</th>
					<th>Telefone</th>
					<th>Email</th>
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
                <h3 class="card-title">Novo Cliente</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" id = "form" method="post" action="clientes.php">
                <div class="card-body">
                  <div class="form-group">
                    <label>Nome</label>
                    <input type="text" class="form-control" name="nome" id = "nome">
                  </div>
                  <div class="form-group">
                    <label>RG</label>
                    <input type="text" class="form-control" name="rg" id = "rg">
                  </div>
				  <input type="hidden" name="acao" id = "acao" value = "novo">
				  <div class="form-group">
					<label>Bairro</label>
					<select class="form-control select2" name = "bairro" id = "bairro" style="width: 100%;">
						<option selected="selected">Selecione</option>
						<?php
							for($i = 0; $i < count($bairros); $i++)
							{
								echo '<option value = "'.$bairros[$i]['id'].'">'.$bairros[$i]['nome'].'</option>';
							}
						?>
					</select>
                </div>
				<div class="form-group">
                    <label>Endereço</label>
                    <input type="text" class="form-control" name = "endereco" id = "endereco">
                  </div>
				  <div class="form-group">
                    <label>Número</label>
                    <input type="text" class="form-control" name = "numero" id = "numero">
                  </div>
				  <div class="form-group">
                    <label>Telefone</label>
                    <input type="text" class="form-control" name = "telefone" id = "telefone">
                  </div>
				  <div class="form-group">
                    <label>E-mail</label>
                    <input type="text" class="form-control" name = "email" id = "email">
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
              <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Editar Cliente</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" id = "form1" method="post" action="clientes.php">
                <div class="card-body">
                  <div class="form-group">
                    <label>Nome</label>
                    <input type="text" class="form-control" name="nome" id = "nome-edit">
                  </div>
                  <div class="form-group">
                    <label>RG</label>
                    <input type="text" class="form-control" name="rg" id = "rg-edit">
                  </div>
				  <input type="hidden" name="acao" id = "acao" value = "editar">
				  <input type="hidden" name="id" id = "id-edit">
				  <div class="form-group">
					<label>Bairro</label>
					<select class="form-control select2" name = "bairro" id = "bairro-edit" style="width: 100%;">
						<option selected="selected">Selecione</option>
						<?php
							for($i = 0; $i < count($bairros); $i++)
							{
								echo '<option value = "'.$bairros[$i]['id'].'">'.$bairros[$i]['nome'].'</option>';
							}
						?>
					</select>
                </div>
				<div class="form-group">
                    <label>Endereço</label>
                    <input type="text" class="form-control" name = "endereco" id = "endereco-edit">
                  </div>
				  <div class="form-group">
                    <label>Número</label>
                    <input type="text" class="form-control" name = "numero" id = "numero-edit">
                  </div>
				  <div class="form-group">
                    <label>Telefone</label>
                    <input type="text" class="form-control" name = "telefone" id = "telefone-edit">
                  </div>
				  <div class="form-group">
                    <label>E-mail</label>
                    <input type="text" class="form-control" name = "email" id = "email-edit">
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" method="post" onClick="return validaCad(2);" class="btn btn-warning">Salvar</button>
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
  	
	$('.toastrDefaultSuccess').click(function() {
      toastr.success('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
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
	   var nome = document.getElementById("nome").value;
	   var rg = document.getElementById("rg").value;
	   var bairro = document.getElementById("bairro").value;
	   var endereco = document.getElementById("endereco").value;
	   var numero = document.getElementById("numero").value;
	   var telefone = document.getElementById("telefone").value;
	   var email = document.getElementById("email").value;
	   var form = document.getElementById("form");
	  }else{
	   var nome = document.getElementById("nome-edit").value;
	   var rg = document.getElementById("rg-edit").value;
	   var bairro = document.getElementById("bairro-edit").value;
	   var endereco = document.getElementById("endereco-edit").value;
	   var numero = document.getElementById("numero-edit").value;
	   var telefone = document.getElementById("telefone-edit").value;
	   var email = document.getElementById("email-edit").value;	
	   var form = document.getElementById("form1");
	  }
	  	

		
		var msg = "";
		if(nome == "")
			msg = "Preencha o nome";
		if(rg == "")
			msg = msg + "\nPreencha o RG";
		if(bairro == "Selecione")
			msg = msg + "\nSelecione o Bairro";
		if(endereco == "")
			msg = msg + "\nPreencha o endereco";
		if(numero == "")
			msg = msg + "\nPreencha o número do endereço";
		if(rg.length != 9)
			msg = msg + "\nFormato de RG inválido";
		if(telefone.length <  10 && telefone != "")
			msg = msg + "\nFormato de telefone inválido";
		if(msg == "")
		{
			document.form.submit();
		}
		else
			alert(msg);
      return false;
	  
  }

  function alterarCad()
  {	
  	var arrayBoletos = new Array();
	var checkboxes = document.getElementsByName("selecionados[]");
	var count = 0;
	var cadastro = 0;
	for(var i = 0; i < checkboxes.length; i++) {
		if(checkboxes[i].checked == true){
			count++;
			cadastro = checkboxes[i].id;
		}
	}
	if(count == 0){
		alert("Selecione um cadastro!");

	}else if(count > 1){
		alert("Selecione somente um cadastro por vez!");

	}else{
	var nome = "nome_"+cadastro;
	var rg = "rg_"+cadastro;
	var endereco = "endereco_"+cadastro;
	var numero = "numero_"+cadastro;
	var telefone = "telefone_"+cadastro;
	var email = "email_"+cadastro;
	var id = cadastro;
	var valor_nome = document.getElementById(nome).innerHTML;
	var valor_rg = document.getElementById(rg).innerHTML;
	var valor_endereco = document.getElementById(endereco).innerHTML;
	var valor_numero = document.getElementById(numero).innerHTML;
	var valor_telefone = document.getElementById(telefone).innerHTML;
	var valor_email = document.getElementById(email).innerHTML;
  	$("#modal-edit").modal({
    show: true
  });
  	  	$("#nome-edit").val(valor_nome);
  	  	$("#rg-edit").val(valor_rg);
  	  	$("#endereco-edit").val(valor_endereco);
  	  	$("#numero-edit").val(valor_numero);
  	  	valor_telefone == "-" ? $("#telefone-edit").val("") : $("#telefone-edit").val(valor_telefone);
  	  	valor_email == "-" ? $("#email-edit").val("") : $("#email-edit").val(valor_email);
  	  	$("#id-edit").val(cadastro);
  }
}
  
</script>
</body>
</html>
