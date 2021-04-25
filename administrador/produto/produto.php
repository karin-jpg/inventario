<?php
session_start();
error_reporting(0);
if(!isset($_SESSION['id-usuario'])){
  header("location: ../../index.php");
}
include_once('../../class/produto.class.php');

$acao = $_POST['acao'];
if($acao == 'novo'){

  $sucesso = $produto->salvar($_POST['codigo'], $_POST['nome'], $_POST['descricao'], $_POST['quantidade-min'], $_POST['quantidade-ideal']);
  if($sucesso){
    $msg = "Produto inserido!";
  }else{
    $msg = "Ocorreu um erro ao inserir o produto, caso o mesmo persista contate o suporte";
  }
}

if($acao == 'editar'){
  $sucesso = $produto->editar($_POST['id-produto'], $_POST['codigo'], $_POST['nome'], $_POST['descricao'], $_POST['quantidade-min'], $_POST['quantidade-ideal']);
  if($sucesso){
    $msg = "Produto atualizado!";
  }else{
    $msg = "Ocorreu um erro ao atualizar o produto, caso o mesmo persista contate o suporte";
  }
}
$produtos = $produto->listar();
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
            <h1 class="m-0 text-dark">PRODUTOS</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Página inicial</a></li>
              <li class="breadcrumb-item active">Produtos</li>
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
                <table id="tableProduto" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Código</th>
                      <th>Nome</th>
                      <th>Descrição</th>
                      <th>Quantidade mínima</th>
                      <th>Quantidade ideal</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
				  for($i = 0; $i < count($produtos); $i++)
				  {
					echo
					'<tr id = "'.$produtos[$i]['id'].'">
            <td>'.$produtos[$i]['codigo'].'</td>
						<td>'.$produtos[$i]['nome'].'</td>
						<td>'.$produtos[$i]['descricao'].'</td>
            <td>'.$produtos[$i]['quantidade_min'].'</td>
            <td>'.$produtos[$i]['quantidade_ideal'].'</td>
					</tr>';
				  }
					?>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Quantidade mínima</th>
                    <th>Quantidade ideal</th>
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
                <h3 class="card-title" id="titulo">Novo Produto</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" id = "form" method="post" action="produto.php">
              <input type="hidden" name="acao" id = "acao" value = "">
              <input type="hidden" name="id-produto" id = "id-produto" value = "">
                <div class="card-body">
                <div class="form-group">
                        <label>Código</label>
                        <input type="text" class="form-control" name="codigo" id = "codigo">
                    </div>

                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" class="form-control" name="nome" id = "nome">
                    </div>

                    <div class="form-group">
                        <label>Descricao</label>
                        <input type="text" class="form-control" name="descricao" id = "descricao">
                    </div>

				            <div class="form-group">
                        <label>Quantidade Mínima</label>
                        <input type="text" class="form-control" name="quantidade-min" id = "quantidade-min">
                    </div>

                    <div class="form-group">
                        <label>Quantidade Ideal</label>
                        <input type="text" class="form-control" name="quantidade-ideal" id = "quantidade-ideal">
                    </div>
                    <div>
                      <label style="color:red;" id="erros-form"></label>
                    </div>
                </div>
                <!-- /.card-body -->
                
                  
                <div class="card-footer">
                  <button type="submit" id="button-cad" method="post" onClick="return validaCad();" class="btn btn-primary">Cadastrar</button>
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
document.querySelector("#tableProduto").addEventListener("dblclick", function(event){
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
  titulo.innerText = 'Editar Produto';
  
  $("#modal-default").modal({
    show: true
  });
      var filhos = event.target.parentNode.children;
      var info = [];
      
      for(let i = 0; i < filhos.length; i++){
        info.push(filhos[i].innerText);
      }
      document.querySelector('#id-produto').value = event.target.parentNode.id;
      document.querySelector('#codigo').value = info[0];
      document.querySelector('#nome').value = info[1];
      document.querySelector('#descricao').value = info[2];
      document.querySelector('#quantidade-min').value = info[3];
      document.querySelector('#quantidade-ideal').value = info[4];

  });

</script>
<script>
  $(function () {
	

	  
    $("#tableProduto").DataTable({
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
    var codigo = document.querySelector("#codigo");
    var desc = document.querySelector("#descricao");
    var quantidade_min = document.querySelector("#quantidade-min");
    var quantidade_ideal = document.querySelector("#quantidade-ideal");
    var regex = /[A-z]/g;
    var nomeValido = nome.value.length > 5;
    var descValido = desc.value.length > 10;
    var codigoValido = (regex.exec(codigo.value) == null && codigo.value.length > 0);
    var quantidadeMinValido = (regex.exec(quantidade_min.value) == null && quantidade_min.value.length > 0);
    var quantidadeIdealValido = (regex.exec(quantidade_ideal.value) == null && quantidade_ideal.value.length > 0);
    var labelErro = document.querySelector("#erros-form");
    
    
    var validacoes = [
        {
          nome: 'codigo',
          valido: codigoValido,
          mensagem: 'Código do produto inválido'
        },
        {
            nome: 'nome',
            valido: nomeValido,
            mensagem: 'Nome do produto deve ter no mínimo 5 caracteres'
        },
        {
            nome: 'descrição',
            valido: descValido,
            mensagem: 'Descrição do produto deve ter no mínimo 10 caracteres'
        },
        {
            nome: 'quantidade mínima',
            valido: quantidadeMinValido,
            mensagem: 'O campo de quantidade mínima só aceita números!'
        },
        {
            nome: 'quantidade ideal',
            valido: quantidadeIdealValido,
            mensagem: 'O campo de quantidade ideal só aceita números!'
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
    document.querySelector('#titulo').innerText = 'Novo Produto';
    document.querySelector("#button-cad").innerText = 'Cadastrar';
    document.querySelector("#form-body").classList.remove('card-info');
    document.querySelector("#button-cad").classList.remove('btn-info');
    document.querySelector("#form-body").classList.add('card-primary');
    document.querySelector("#button-cad").classList.add('btn-primary');
    document.querySelector("#nome").value = "";
    document.querySelector("#erros-form").innerText = "";
    document.querySelector("#aviso").innerText = "";
    document.querySelector("#aviso").style.display = "none";
  }
</script>
</body>
</html>
