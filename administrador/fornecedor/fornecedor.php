<?php
session_start();
error_reporting(0);
if(!isset($_SESSION['id-usuario'])){
  header("location: ../../index.php");
}
include_once('../../class/fornecedor.class.php');

$acao = $_POST['acao'];
if($acao == 'novo'){

  $sucesso = $fornecedor->salvar($_POST['nome'], $_POST['cep'], $_POST['estado'], $_POST['cidade'], $_POST['bairro'], $_POST['rua'], $_POST['numero'], $_POST['telefone']);
  if($sucesso){
    $msg = "Fornecedor inserido!";
  }else{
    $msg = "Ocorreu um erro ao inserir o fornecedor, caso o mesmo persista contate o suporte";
  }
}

if($acao == 'editar'){
  $sucesso = $fornecedor->editar($_POST['id-fornecedor'], $_POST['nome'], $_POST['cep'], $_POST['estado'], $_POST['cidade'], $_POST['bairro'], $_POST['rua'], $_POST['numero'], $_POST['telefone']);
  if($sucesso){
    $msg = "Fornecedor atualizado!";
  }else{
    $msg = "Ocorreu um erro ao atualizar o fornecedor, caso o mesmo persista contate o suporte";
  }
}
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
            <h1 class="m-0 text-dark">FORNECEDORES</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Página inicial</a></li>
              <li class="breadcrumb-item active">Fornecedores</li>
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
              <div class="row mb-2">
			  <div class="card-header  col-sm-10">
              </div>
			  <div class="card-header col-sm-2">
						<button type="button" class="btn btn-block btn-outline-primary" data-toggle="modal" data-target="#modal-default" onClick="clearForm()" >Novo</button>
			</div>
			</div>
			  
              
              <label id="aviso" style="text-align:center; color:<?= ($sucesso && $acao) ? 'blue' : 'red'?>; display:<?($acao) ? 'block' : 'none'?>;"><?= $msg?></label>
              <div class="card-body">
                <table id="tableFornecedor" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Rua</th>
                        <th>Número</th>
                        <th>Bairro</th>
                        <th>Cidade</th>
                        <th>Estado</th>
                        <th>CEP</th>
                        <th>Telefone</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
				  for($i = 0; $i < count($fornecedores); $i++)
				  {
					echo
					'<tr id = "'.$fornecedores[$i]['id'].'">
						<td>'.$fornecedores[$i]['nome'].'</td>
						<td>'.$fornecedores[$i]['logradouro'].'</td>
                        <td>'.$fornecedores[$i]['numero'].'</td>
                        <td>'.$fornecedores[$i]['bairro'].'</td>
                        <td>'.$fornecedores[$i]['cidade'].'</td>
                        <td>'.$fornecedores[$i]['estado'].'</td>
                        <td>'.$fornecedores[$i]['cep'].'</td>
                        <td>'.$fornecedores[$i]['telefone'].'</td>
					</tr>';
				  }
					?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>Nome</th>
                      <th>Rua</th>
                      <th>Número</th>
                      <th>Bairro</th>
                      <th>Cidade</th>
                      <th>Estado</th>
                      <th>CEP</th>
                      <th>Telefone</th>
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
              <div id = "form-body" class="card card-primary">
              <div class="card-header">
                <h3 class="card-title" id="titulo">Novo Fornecedor</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" id = "form" method="post" action="fornecedor.php">
              <input type="hidden" name="acao" id = "acao" value = "">
              <input type="hidden" name="id-fornecedor" id = "id-fornecedor" value = "">
                <div class="card-body">
                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" class="form-control" name="nome" id = "nome">
                    </div>

                    <div class="form-group">
                        <label>CEP</label>
                        <input type="text" class="form-control" name="cep" id = "cep">
                    </div>

				            <div class="form-group">
                        <label>Rua</label>
                        <input type="text" class="form-control" name="rua" id = "rua" readonly>
                    </div>

                    <div class="form-group">
                        <label>Número</label>
                        <input type="text" class="form-control" name="numero" id = "numero">
                    </div>

                    <div class="form-group">
                        <label>Bairro</label>
                        <input type="text" class="form-control" name="bairro" id = "bairro" readonly>
                    </div>

                    <div class="form-group">
                        <label>Cidade</label>
                        <input type="text" class="form-control" name="cidade" id = "cidade" readonly>
                    </div>

                    <div class="form-group">
                        <label>Estado</label>
                        <input type="text" class="form-control" name="estado" id = "estado" readonly>
                    </div>

                    <div class="form-group">
                        <label>Telefone</label>
                        <input type="text" class="form-control" name="telefone" id = "telefone" >
                    </div>
                    <div>
                      <label style="color:red;" id="erros-form"></label>
                    </div>
                </div>
                <!-- /.card-body -->
                
                  
                <div class="card-footer">
                  <button type="submit" id="button-cad" method="post" onClick="return validaCad();" class="btn btn-primary"><?= $_SESSION['tipo']?>Cadastrar</button>
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
<script src="../../estilo/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../estilo/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="../../estilo/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../estilo/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../estilo/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../estilo/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="../../estilo/dist/js/adminlte.min.js"></script>
<!-- Toastr -->
<script src="../../estilo/plugins/toastr/toastr.min.js"></script>
<!-- SweetAlert2 -->
<script src="../../estilo/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../estilo/dist/js/demo.js"></script>
<!-- page script -->
<script>
document.querySelector("#tableFornecedor").addEventListener("dblclick", function(event){
  var botao = document.querySelector('#button-cad');
  var form = document.querySelector('#form-body');
  var titulo = document.querySelector('#titulo');
  var acao = document.querySelector('#acao');

  
  acao.value = 'editar';
  botao.classList.remove('btn-primary');
  form.classList.remove('card-primary');
  botao.classList.add('btn-info');
  botao.innerText = 'Salvar Alteração';
  form.classList.add('card-info');
  titulo.innerText = 'Editar Fornecedor';
  
  $("#modal-default").modal({
    show: true
  });
      var filhos = event.target.parentNode.children;
      var info = [];
      
      for(let i = 0; i < filhos.length; i++){
        info.push(filhos[i].innerText);
      }
      document.querySelector('#id-fornecedor').value = event.target.parentNode.id;
      document.querySelector('#nome').value = info[0];
      document.querySelector('#rua').value = info[1];
      document.querySelector('#numero').value = info[2];
      document.querySelector('#bairro').value = info[3];
      document.querySelector('#cidade').value = info[4];
      document.querySelector('#estado').value = info[5];
      document.querySelector('#cep').value = info[6];
      document.querySelector('#telefone').value = info[7];
      

  });

</script>
<script>
  $(function () {

    $("#cep" ).focusout(function() {
		
		var cep = $(this).val().replace(/[^a-z0-9\s]/gi, '');
				 
		
		$.ajax({			
		  url: 'https://viacep.com.br/ws/'+cep+'/json',		
		  dataType: 'json',
		  
		  success: function(retorno){
			  
				$("#rua").val(retorno.logradouro);
				$("#bairro").val(retorno.bairro);
				$("#cidade").val(retorno.localidade);
				$("#estado").val(retorno.uf);
				$("#numero").focus();

		  }
		});
	});
	

	  
    $("#tableFornecedor").DataTable({
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
  });


  function validaCad()
  {
    var form = document.querySelector("#form");
  	var nome = document.querySelector("#nome");
    var cep = document.querySelector("#cep");
    var numero = document.querySelector("#numero");
    var telefone = document.querySelector('#telefone');
    var regex = /[A-z]/g;
    var nomeValido = nome.value.length > 0;
    var cepValido = cep.value.length > 0;
    var numInputValido = regex.exec(numero.value) == null;
    var numTamValido = numero.value.length > 0;
    var telInputValido = regex.exec(telefone.value) == null;
    var telTamValido = telefone.value.length > 10;
    
    var labelErro = document.querySelector("#erros-form");
    
    
    var validacoes = [
        {
            nome: 'nome',
            valido: nomeValido,
            mensagem: 'Nome do fornecedor não pode ficar vazio!'
        },
        {
            nome: 'cep',
            valido: cepValido,
            mensagem: 'O cep não pode ficar vazio!'
        },
        {
            nome: 'numero',
            valido: numTamValido,
            mensagem: 'O número não pode ficar vazio!'
        },
        {
            nome: 'numero',
            valido: numInputValido,
            mensagem: 'O número só pode conter números!'
        },
        {
            nome: 'telefone',
            valido: telInputValido,
            mensagem: 'O telefone não pode conter nada além de números!'
        },
        {
            nome: 'telefone',
            valido: telTamValido,
            mensagem: 'O tamanho do telefone é inválido'
        }
    ]

    var erros = validacoes.filter(campo => !campo.valido)
    var msg = "";
    if(erros.length){
      erros.forEach((erros) => {
          msg += erros.mensagem+"\n";
      })
      labelErro.innerText = msg;
      return false;
    }else{
      form.submit();
    }
  }

  function clearForm(){
    document.querySelector('#acao').value = 'novo';
    document.querySelector('#titulo').innerText = 'Novo Fornecedor';
    document.querySelector("#button-cad").innerText = 'Cadastrar';
    document.querySelector("#form-body").classList.remove('card-info');
    document.querySelector("#button-cad").classList.remove('btn-info');
    document.querySelector("#form-body").classList.add('card-primary');
    document.querySelector("#button-cad").classList.add('btn-primary');
    document.querySelector("#nome").value = "";
    document.querySelector("#cep").value = "";
    document.querySelector("#rua").value = "";
    document.querySelector("#numero").value = "";
    document.querySelector("#bairro").value = "";
    document.querySelector("#cidade").value = "";
    document.querySelector("#estado").value = "";
    document.querySelector("#telefone").value = "";
    document.querySelector("#erros-form").innerText = "";
    document.querySelector("#aviso").innerText = "";
    document.querySelector("#aviso").style.display = "none";
  }
</script>
</body>
</html>
