<?php
session_start();
if(!$_SESSION['login'])
{
  header("Location:../../../index.php");
}


include_once '../../class/cliente.class.php';
include_once '../../class/remedio.class.php';
include_once '../../class/pedido.class.php';
include_once '../../class/banco.class.php';
error_reporting(0);
$clientes = $cliente->listar();
$remedios = $remedio->listar();
$pedidos = $pedido->listarProtocolos();

if($_POST['acao'] == "entregar")
{
  //error_reporting(E_ALL);
  $id_pedido_edit = $_POST['pedidos'];
  $id_remedios_edit = $_POST['remedios-array'];
  $quantidade_edit = $_POST['quantidade-array'];
  $protocolo =  $_POST['protocolo'];
  $count = count(explode("|", $id_pedido_edit));
  $resultado = $pedido->entregarRemedio($count, $id_pedido_edit, $id_remedios_edit, $quantidade_edit);
  if(is_numeric($resultado)){
      if($resultado == 0){
           $s = 0; 
        }else{
            $s = 1;

        }
  }else{
    $s = 3;
  }
}


if($_POST['acao'] == "editar")
{
  //error_reporting(E_ALL);
	$id_pedido_edit = $_POST['pedidos'];
  $id_remedios_edit = $_POST['remedios-array'];
  $quantidade_edit = $_POST['quantidade-array'];
  $erro = 0;
  $count = count(explode("|", $id_pedido_edit));
  $resultado = $pedido->editarPedido($count, $id_pedido_edit, $id_remedios_edit, $quantidade_edit);
  if(is_numeric($resultado)){
      if($resultado == 0){
           $s = 0; 
        }else{
            $s = 1; 
        }
  }else{
    $s = 3;
  }
}


if($_POST['acao'] == "novo")
{
  $erro = 0;
  $id_cliente = $_POST['cliente-id'];
  $id_remedio = $_POST['remedios'];
  $quantidade = $_POST['quantidade'];

  $sql = "select now() as data";
  $data = $banco->executa($sql);
  $data = $data[0][data];

  $sql = "select protocolo from pedidos order by 1 desc limit 1";
  $protocolo = $banco->executa($sql);
  $protocolo = $protocolo[0][protocolo] + 1;
  for($i = 0; $i < count($id_remedio); $i++)
  {
      if($pedido->salvar($id_remedio[$i], $quantidade[$i], $id_cliente, $protocolo,$data)){
          $s = 1;
        }else {
          $s = 0;
        }
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
            <h1 class="m-0 text-dark">PEDIDOS</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Página inicial</a></li>
              <li class="breadcrumb-item active">Pedidos</li>
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
              <!-- /.card-header -->
            <!-- /.card -->
            <div class="card">
              <div class="row mb-2">
			  <div class="card-header  col-sm-8">
              </div>
              <div class="card-header col-sm-2">
			  <button type="button" class="btn btn-block btn-outline-info" onclick = "alterarCad();" >Editar Pedido</button>
				</div>
			  <div class="card-header col-sm-2">
						<button type="button" class="btn btn-block btn-outline-primary" data-toggle="modal" data-target="#modal-xl" >Novo Pedido</button>
				</div>
			</div>
			  
              <!-- /.card-header -->
              <div class="card-body">
                <table id="tablePedido" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Protocolo</th>
                    <th>Cliente</th>
                    <th>Status</th>
                    <th>Selecione</th>
                  </tr>
                  </thead>
                  <tbody>
				  <?php
				  for($i = 0; $i < count($pedidos); $i++)
				  {
            $status = $pedido->encerrarPedido($pedidos[$i]['protocolo']);
					echo
					'<tr>
						<td>'.$pedidos[$i]['protocolo'].'</td>
						<td>'.$pedidos[$i]['nome'].'</td>
            <td>'.$status.'</td>
						<td><input type="checkbox" name="selecionados[]" id = "'.$pedidos[$i]['protocolo'].'|'.$pedidos[$i]['id'].'|'.$pedidos[$i]['nome'].'"></td>
					</tr>';
				  }
					?>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Protocolo</th>
                    <th>Cliente</th>
                    <th>Status</th>
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
<div class="modal fade" id="modal-edit">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-body">
              <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Editar pedido</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" id = "form1" method="post" action="">
                <div class="card-body">
                  <div class="form-group">
                    <label>Cliente</label>
                    <input type="text" class="form-control" name="nome-edit" id = "nome-edit" disabled>
                    <input type="hidden" class="form-control" name="cliente-id-edit" id = "cliente-id-edit">
                  </div>
				  <input type="hidden" name="acao" id = "acao-edit">
				  <input type="hidden" name="pedidos" id ="pedidos" value = "">
          <input type="hidden" name="remedios-array" id ="remedios-array" value = "">
          <input type="hidden" name="quantidade-array" id ="quantidade-array" value = "">

                    <label>Remédios</label>
              <div class="form-group">  
              	<table  id="remedios-form-edit">
              	</table>

              </div>
          </div>
        </div>
                <div class="card-footer">
                  <button onClick="return editarPedido();" class="btn btn-info">Salvar alteracões</button>
                  <button onClick="return entregarRemedio();" class="btn btn-info">Marcar como entregue</button>
                </div>
              </form>
            </div>
            </div>
          </div>
</div>  
  
  
<div class="modal fade" id="modal-xl">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-body">
              <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Novo pedido</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" id = "form" method="post" action="pedidos.php">
                <div class="card-body">
                  <div class="form-group">
                    <label>Cliente</label>
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-pesquisa">+</button>
                    <input type="text" class="form-control" name="nome" id = "nome" disabled>
                    <input type="hidden" class="form-control" name="cliente-id" id = "cliente-id">
                  </div>
				  <input type="hidden" name="acao" value = "novo">

                    <label>Remédios</label>
                    <button id="add-campo" type="button" class="btn btn-default">+</button>
            <table>
              <tbody>
              <div class="form-group" id="remedios-form"> 
			  
              </div>
            </tbody>
            </table>
          </div>
        </div>
                <div class="card-footer">
                  <button onClick="return validaCad();" class="btn btn-primary">Cadastrar</button>
                </div>
              </form>
            </div>
            </div>
          </div>
        </div>
		
		
      <div class="modal fade" id="modal-pesquisa">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <div class="card card-primary">
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
                echo '<option value = "'.$clientes[$i]['id'].'|'.$clientes[$i]['nome'].'">'.$clientes[$i]['nome'].'</option>';
              }
            ?>
          </select>
                </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button onClick="selecionarCliente();" class="btn btn-primary">Selecionar</button>
                  <button type="button" id="close-modal" class="btn btn-default" data-dismiss="modal" style ="display:none"></button>
                </div>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
    $("#tablePedido").DataTable({
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


  $("#add-campo").click(function() {
    $("#remedios-form").append('<tr><td style="width: 90%"><select class="form-control select2" name = "remedios[]"><option selected="selected">Selecione</option><?php for($i = 0; $i < count($remedios); $i++) {echo '<option value = "'.$remedios[$i]['id'].'">'.$remedios[$i]['nome'].'</option>';}?></select></td><td style="width: 100px"><input type="text" class="form-control" name="quantidade[]"></td></tr>');
  });


  function selecionarCliente()
  {
    var info = document.getElementById("clienteSelecionado");
    var cliente = info.value.split("|");
    $("#nome").val(cliente[1]);
    $("#cliente-id").val(cliente[0]);
    $('#close-modal').click();
  }


 function alterarCad()
  {	
	var checkboxes = document.getElementsByName("selecionados[]");
	var count = 0;
	var protocolo = 0;
	var cliente_id;
	var nome;
	for(var i = 0; i < checkboxes.length; i++) {
		if(checkboxes[i].checked == true){
			count++;
			protocolo = checkboxes[i].id.split("|")[0];
			cliente_id = checkboxes[i].id.split("|")[1];
			nome = checkboxes[i].id.split("|")[2];
		}
	}
	if(count == 0){
		alert("Selecione um cadastro!");

	}else if(count > 1){
		alert("Selecione somente um cadastro por vez!");

	}else{
	$
	var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("remedios-form-edit").innerHTML = this.responseText;
      }
    };
    xmlhttp.open("GET","buscapedidos.php?protocolo="+protocolo,true);
    xmlhttp.send();
    	$("#nome-edit").val(nome);
    	$("#cliente-id-edit").val(cliente_id);
		$("#modal-edit").modal({
    show: true
  });
	}
}

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
  echo 'alert("Alteracões realizadas com sucesso!");';
}else if ($s == 0){
  echo 'alert("Houve um problema com as alterações!");';
  }
 }
 if($_POST['acao'] == "entregar")
 {
  if($s == 1){
  echo 'alert("Entregas realizadas com sucesso!");';
}else if ($s == 0){
  echo 'alert("Houve um problema com as entregas!");';
  } else{
    echo 'alert('.$resultado.');';
  }
 }
 ?>

function validaCad(){

  var nome = document.getElementById("nome").value;
  var erro = 0;
  var msg = "";
  var remedios = document.getElementsByName("remedios[]");
  var quantidade =  document.getElementsByName("quantidade[]");
  var form = document.getElementById("form");
  if(nome == "")
  {
    erro = 1
    msg = "Selecione o Cliente!\n";
  }
  if(remedios.length == 0)
  {
    erro = 1
    msg += "Adicione pelo menos 1 remédio!";
  }
  for(var i = 0; i < remedios.length; i++) {
  if(remedios[i].value == "Selecione"){
      erro = 1;
      msg += "Remédio não selecionado na linha "+(i+1)+", verifique as informações!\n";
      break;
    }
  if(quantidade[i].value == ""){
      erro = 1;
      msg += "Quantidade não informada na linha "+(i+1)+", verifique as informações!";
      break;
    }

  }
    if(erro == 1)
    {
      alert(msg);
      return false;
    }else{
      document.form.submit();
    }
}

function confirmaPedido(data)
{
  var name = document.getElementById("rem_"+data.id.split("|")[0]);
  var quantidade = document.getElementById("qntd_"+data.id.split("|")[0]);
  if(data.checked == true){
    name.setAttribute("disabled", "disabled");
    quantidade.setAttribute("disabled", "disabled");
}else{  
  name.disabled = false;
  quantidade.disabled = false;
}
  
}

function entregarRemedio(){
  var form = document.getElementById("form1");
	var pedidos = "";
  var remedios = "";
  var quantidade = "";
  var tam = 0;
	var pedido_input = document.getElementById("pedidos");
  var remedios_input = document.getElementById("remedios-array");
  var quantidade_input = document.getElementById("quantidade-array");
	var checkboxes = document.getElementsByName("finalizado[]");
  var acao = document.getElementById("acao-edit");
  var contador = 0;
  var quantidade_pedido = document.getElementsByName("quantidade_pedidos");
  acao.value = "entregar";
	for(var i = 0; i < checkboxes.length; i++) {
    if(checkboxes[i].checked == true)
    {
      contador++;
        pedidos += checkboxes[i].id.split("|")[1]+"|";
        remedios += document.getElementsByName("remedios-edit[]")[i].value+"|";
        quantidade +=  document.getElementsByName("quantidade-edit[]")[i].value+"|";

    }
  }
  if(contador == 0){
    alert("Selecione no mínimo um remédio para marcar como entregue!");
    return false;
  }else{
	   if(confirm("Deseja realmente marcar como entregue os remedios selecionados?")){
	     	quantidade_pedido.value = contador;
        tam = pedidos.length - 1;
        pedido_input.value = pedidos.substring(0, tam);
        tam = remedios.length - 1;
        remedios_input.value = remedios.substring(0, tam);
        tam = quantidade.length - 1;
        quantidade_input.value = quantidade.substring(0, tam);
	   	  document.form.submit();
	   }else
	   	return false;
  }
}

function editarPedido(){
  var form = document.getElementById("form1");
  var pedidos = "";
  var remedios = "";
  var quantidade = "";
  var pedido_input = document.getElementById("pedidos");
  var remedios_input = document.getElementById("remedios-array");
  var quantidade_input = document.getElementById("quantidade-array");
  var checkboxes = document.getElementsByName("finalizado[]");
  var acao = document.getElementById("acao-edit");

  acao.value = "editar";

  for(var i = 0; i < checkboxes.length; i++) {
      if(i == (checkboxes.length - 1)){
        pedidos += checkboxes[i].id.split("|")[1];
        remedios += document.getElementsByName("remedios-edit[]")[i].value;
        quantidade += document.getElementsByName("quantidade-edit[]")[i].value;
      }else{
        pedidos += checkboxes[i].id.split("|")[1]+"|";
        remedios += document.getElementsByName("remedios-edit[]")[i].value+"|";
        quantidade +=  document.getElementsByName("quantidade-edit[]")[i].value+"|";
      }
  }
     if(confirm("Deseja realmente alterar os dados do pedido selecionado?"))
     {
        pedido_input.value = pedidos;
        remedios_input.value = remedios;
        quantidade_input.value = quantidade;
        document.form.submit();
     }else
      return false;
  
}

  
</script>
</body>
</html>
