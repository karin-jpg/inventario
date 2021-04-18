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

include_once('../../class/inventario.class.php');
include_once('../../class/produto.class.php');
include_once('../../class/fornecedor.class.php');
include_once('../../class/loja.class.php');
include_once('../../class/local.class.php');
include_once('../../class/status.class.php');
include_once('../../class/movimento.class.php');
$produtos = $produto->listar();
$fornecedores = $fornecedor->listar();
$lojas = $loja->listar();
$locais = $local->listar();
$sts = $status->listar();

$acao = $_POST['acao'];
$acao_escolha = $_POST['acao-escolha'];

if($acao == 'novo'){
  $valor_venda = str_replace(',', '.', str_replace('.', '', $_POST['valor-venda']));
  $valor_custo = str_replace(',', '.', str_replace('.', '', $_POST['valor-custo']));
  
  $sucesso = $inventario->salvar($_POST['produto'], $_POST['loja'], $_POST['fornecedor'], $_POST['local'], $valor_venda, $valor_custo);
  if($sucesso){
    $msg = "item inserido!";
  }else{
    $msg = "Ocorreu um erro ao inserir o item, caso o mesmo persista contate o suporte";
  }
}

if($acao == 'editar'){
  $sucesso = $inventario->editar($_POST['id-inventario'], $_POST['nome']);
  if($sucesso){
    $msg = "item atualizado!";
  }else{
    $msg = "Ocorreu um erro ao atualizar o item, caso o mesmo persista contate o suporte";
  }
}

if($acao_escolha == 'entrada'){
  
  $sucesso = $movimento->salvarEntrada($_POST['id-item-escolha'], $_POST['quantidade-escolha'], $_POST['status-escolha']);
  if($sucesso){
    $msg = "entrada salva e item atualizado!";
  }else{
    $msg = "Ocorreu um erro ao realizar a entrada, caso o mesmo persista contate o suporte";
  }
}


if($acao_escolha == 'saida'){
  if($movimento->validaQuantidade($_POST['id-item-escolha'], $_POST['quantidade-escolha'])){
    $sucesso = $movimento->salvarSaida($_POST['id-item-escolha'], $_POST['ordem-saida-escolha'], $_POST['quantidade-escolha'], $_POST['status-escolha']);
    if($sucesso){
      $msg = "entrada salva e item atualizado!";
    }else{
      $msg = "Ocorreu um erro ao realizar a saida, caso o mesmo persista contate o suporte";
    }
  }else{
    $sucesso = 0;
    $msg = "A quantidade removida não pode ser maior que a quantidade disponível!";
  }

}


$itens = $inventario->listar();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

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
            <h1 class="m-0 text-dark">INVENTÁRIO</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Página inicial</a></li>
              <li class="breadcrumb-item active">Inventário </li>
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
			  <div class="card-header col-sm-10">
              </div>
			  <div class="card-header col-sm-2">
						<button type="button" class="btn btn-block btn-outline-primary" data-toggle="modal" data-target="#modal-default" onClick="clearForm()" >Novo</button>
			</div>
			</div>
			  
              
              <label id="aviso" style="text-align:center; color:<?= ($sucesso && ($acao || $acao_escolha)) ? 'blue' : 'red'?>; display:<?($acao || $acao_escolha) ? 'block' : 'none'?>;"><?= $msg?></label>
              <div class="card-body">
                <table id="tableInventario" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Produto</th>
                      <th>Loja</th>
                      <th>Local</th>
                      <th>Fornecedor</th>
                      <th>Valor de venda</th>
                      <th>Valor de custo</th>
                      <th>Quantidade</th>
                      <th>Quantidade Ideal</th>
                      <th>Quantidade Min.</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
				  for($i = 0; $i < count($itens); $i++)
				  {
            
					echo
					'<tr class = "'.verificaQuantidade($itens[$i]['quantidade'], $itens[$i]['minima'], $itens[$i]['ideal']).'" id = "'.$itens[$i]['id'].'">
						<td>'.$itens[$i]['produto'].'</td>
            <td>'.$itens[$i]['loja'].'</td>
            <td>'.$itens[$i]['local'].'</td>
            <td>'.$itens[$i]['fornecedor'].'</td>
            <td>'.$itens[$i]['valor_venda'].'</td>
            <td>'.$itens[$i]['valor_custo'].'</td>
            <td>'.$itens[$i]['quantidade'].'</td>
            <td>'.$itens[$i]['ideal'].'</td>
            <td>'.$itens[$i]['minima'].'</td>
            <td>
              <button type="submit" method="post" class="btn btn-primary btn-entrada">Dar Entrada</button>
              <button type="submit" method="post" class="btn btn-danger btn-saida">Dar Saída</button>
            </td>
					</tr>';
				  }
					?>
                  </tbody>
                  <tfoot>
                  <tr>
                      <th>Produto</th>
                      <th>Loja</th>
                      <th>Local</th>
                      <th>Fornecedor</th>
                      <th>Valor de venda</th>
                      <th>Valor de custo</th>
                      <th>Quantidade</th>
                      <th>Quantidade Ideal</th>
                      <th>Quantidade Min.</th>
                      <th></th>
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
  <div class="modal fade" id="modal-escolha">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <div id = "form-body-escolha" class="card card-primary">
              <div class="card-header">
                <h3 class="card-title" id="titulo-escolha"></h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" id = "form-escolha" method="post" action="">
              <input type="hidden" name="acao-escolha" id = "acao-escolha" value = "">
              <input type="hidden" name="id-item-escolha" id = "id-item-escolha" value = "">
                <div class="card-body">
                    <div class="form-group">
                        <label>Produto</label>
                        <input type="text" class="form-control" name="produto-escolha" id = "produto-escolha" readonly>
                    </div>
                    <div class="form-group">
                        <label>Loja</label>
                        <input type="text" class="form-control" name="loja-escolha" id = "loja-escolha" readonly>
                    </div>
                    <div class="form-group">
                        <label>Local</label>
                        <input type="text" class="form-control" name="local-escolha" id = "local-escolha" readonly>
                    </div>
                    <div class="form-group">
                        <label>Fornecedor</label>
                        <input type="text" class="form-control" name="fornecedor-escolha" id = "fornecedor-escolha" readonly>
                    </div>
                    <div class="form-group" id = "ordem-saida-div">
                        <label>Ordem de saída</label>
                        <input type="text" class="form-control" name="ordem-saida-escolha" id = "ordem-saida-escolha">
                    </div>
                    <div class="form-group">
                        <label>Quantidade</label>
                        <input type="text" class="form-control" name="quantidade-escolha" id = "quantidade-escolha">
                    </div>
                    <div class="form-group">
					        <label>Status</label>
					            <select class="form-control select2" name = "status-escolha" id = "status-escolha" style="width: 100%;">
					            	<option selected="selected">Selecione</option>
					            	<?php
					            		for($i = 0; $i < count($sts); $i++)
					            		{
					            			echo '<option value = "'.$sts[$i]['id'].'">'.$sts[$i]['nome'].'</option>';
					            		}
					            	?>
					            </select>
                </div>
                    <div>
                      <label style="color:red;" id="erros-form-escolha"></label>
                    </div>
                </div>
                <!-- /.card-body -->
                
                  
                <div class="card-footer">
                  <button type="submit" id="button-escolha" method="post" onClick = 'return validaEscolha();' class="btn btn-primary"></button>
                </div>
              </form>
            </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>





<div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <div id = "form-body" class="card card-primary">
              <div class="card-header">
                <h3 class="card-title" id="titulo">Novo item</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" id = "form" method="post" action="">
              <input type="hidden" name="acao" id = "acao" value = "">
              <input type="hidden" name="id-item" id = "id-item" value = "">
                <div class="card-body">
                <div class="form-group">
					        <label>Produto</label>
					            <select class="form-control select2" name = "produto" id = "produto" style="width: 100%;">
					            	<option selected="selected">Selecione</option>
					            	<?php
					            		for($i = 0; $i < count($produtos); $i++)
					            		{
					            			echo '<option value = "'.$produtos[$i]['id'].'">'.$produtos[$i]['nome'].'</option>';
					            		}
					            	?>
					            </select>
                </div>
                <div class="form-group">
					        <label>Loja</label>
					            <select class="form-control select2" name = "loja" id = "loja" style="width: 100%;">
					            	<option selected="selected">Selecione</option>
					            	<?php
					            		for($i = 0; $i < count($lojas); $i++)
					            		{
					            			echo '<option value = "'.$lojas[$i]['id'].'">'.$lojas[$i]['nome'].'</option>';
					            		}
					            	?>
					            </select>
                </div>
                <div class="form-group">
					        <label>Local</label>
					            <select class="form-control select2" name = "local" id = "local" style="width: 100%;">
					            	<option selected="selected">Selecione</option>
					            	<?php
					            		for($i = 0; $i < count($locais); $i++)
					            		{
					            			echo '<option value = "'.$locais[$i]['id'].'">'.$locais[$i]['nome'].'</option>';
					            		}
					            	?>
					            </select>
                </div>
                <div class="form-group">
					        <label>Fornecedor</label>
					            <select class="form-control select2" name = "fornecedor" id = "fornecedor" style="width: 100%;">
					            	<option selected="selected">Selecione</option>
					            	<?php
					            		for($i = 0; $i < count($fornecedores); $i++)
					            		{
					            			echo '<option value = "'.$fornecedores[$i]['id'].'">'.$fornecedores[$i]['nome'].'</option>';
					            		}
					            	?>
					            </select>
                </div>
                <div class="form-group" id="div-quantidade-total" style= "display:none;">
                    <label>Quantidade</label>
                    <input type="text" class="form-control" name="quantidade" id = "quantidade">
                </div>
                <div class="form-group" id="div-quantidade-novo" style= "display:none;">
                    <label>Novos</label>
                    <input type="text" class="form-control" name="quantidade-novo" id = "quantidade-novo" readonly>
                </div>
                <div class="form-group" id="div-quantidade-retirado" style= "display:none;">
                    <label>Retirados</label>
                    <input type="text" class="form-control" name="quantidade-retirado" id = "quantidade-retirado" readonly>
                </div>
                
                <div class="form-group">
                    <label>Valor de venda</label>
                    <input type="text" class="form-control" name="valor-venda" id = "valor-venda">
                </div>
                <div class="form-group">
                    <label>Valor de custo</label>
                    <input type="text" class="form-control" name="valor-custo" id = "valor-custo">
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
document.querySelector("#valor-custo").addEventListener("blur", function(event){
  var valor = document.querySelector('#valor-custo');
  var nValor = new Intl.NumberFormat('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(valor.value.replace(',','.'));
  valor.value = nValor;
  
});

document.querySelector("#valor-venda").addEventListener("blur", function(event){
  var valor = document.querySelector('#valor-venda');
  var nValor = new Intl.NumberFormat('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(valor.value.replace(',','.'));
  valor.value = nValor;
  
});

document.querySelectorAll(".btn-entrada").forEach(item => {

  item.addEventListener("click", function(event){


        var filhos = event.target.parentNode.parentNode.children;
        var info = [];

        for(let i = 0; i < filhos.length; i++){
          info.push(filhos[i].innerText);
        }
        
        document.querySelector('#titulo-escolha').innerText = 'Entrada';
        document.querySelector('#form-body-escolha').classList.remove('card-danger');
        document.querySelector('#form-body-escolha').classList.add('card-primary');
        document.querySelector('#button-escolha').innerText = 'Confirmar entrada';
        document.querySelector('#button-escolha').classList.remove('btn-danger');
        document.querySelector('#button-escolha').classList.add('btn-primary');
        document.querySelector('#ordem-saida-div').style.display = 'none';
        document.querySelector('#acao-escolha').value = 'entrada';
        document.querySelector('#status-escolha').value = 'Selecione';
        document.querySelector('#erros-form-escolha').innerText = "";
        document.querySelector('#id-item-escolha').value = event.target.parentNode.parentNode.id;
        document.querySelector('#produto-escolha').value = info[0];
        document.querySelector('#loja-escolha').value = info[1];
        document.querySelector('#local-escolha').value = info[2];
        document.querySelector('#fornecedor-escolha').value = info[3];
      
        $("#modal-escolha").modal({
          show: true
        });

  });
});

document.querySelectorAll(".btn-saida").forEach(item => {

item.addEventListener("click", function(event){


      var filhos = event.target.parentNode.parentNode.children;
      var info = [];

      for(let i = 0; i < filhos.length; i++){
        info.push(filhos[i].innerText);
      }
      document.querySelector('#titulo-escolha').innerText = 'Saída';
      document.querySelector('#form-body-escolha').classList.remove('card-primary');
      document.querySelector('#form-body-escolha').classList.add('card-danger');
      document.querySelector('#button-escolha').innerText = 'Confirmar saída';
      document.querySelector('#button-escolha').classList.remove('btn-primary');
      document.querySelector('#button-escolha').classList.add('btn-danger');
      document.querySelector('#ordem-saida-div').style.display = 'block';
      document.querySelector('#acao-escolha').value = 'saida';
      document.querySelector('#status-escolha').value = 'Selecione';
      document.querySelector('#erros-form-escolha').innerText = "";
      document.querySelector('#id-item-escolha').value = event.target.parentNode.parentNode.id;
      document.querySelector('#produto-escolha').value = info[0];
      document.querySelector('#loja-escolha').value = info[1];
      document.querySelector('#local-escolha').value = info[2];
      document.querySelector('#fornecedor-escolha').value = info[3];
    
      $("#modal-escolha").modal({
        show: true
      });

});
});

</script>
<script>
  $(function () {
	

	  
    $("#tableInventario").DataTable({
      "responsive": true,
      "autoWidth": false,
	  "bFilter": true,
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
  });


  function validaEscolha(){

    var form = document.querySelector('#form-escolha');
    var acao = document.querySelector('#acao-escolha');
    var labelErro = document.querySelector("#erros-form-escolha");
    var quantidade = document.querySelector('#quantidade-escolha');
    var ordemSaida = document.querySelector('#ordem-saida-escolha');
    var status = document.querySelector('#status-escolha');
    var regex = /[A-z]/g;
    var validaQuantidade = (quantidade.value.length > 0 && regex.exec(quantidade.value) == null)
    var validaStatus = !(status.value == 'Selecione')
    var validaSaida = (acao.value == 'saida') ? (regex.exec(ordemSaida.value) == null) : true;

    var validacoes = [
        {
            nome: 'quantidade',
            valido: validaQuantidade,
            mensagem: 'Quantidade inválida!'
        },
        {
          nome: 'status',
          valido: validaStatus,
          mensagem: 'Selecione um status!'
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

  function validaCad()
  {
    var form = document.querySelector("#form");
  	var produto = document.querySelector("#produto");
    var loja = document.querySelector("#loja");
    var local = document.querySelector("#local");
    var fornecedor = document.querySelector("#fornecedor");
    var valorVenda = document.querySelector("#valor-venda");
    var valorCusto = document.querySelector("#valor-custo");
    var labelErro = document.querySelector("#erros-form");
    
    var validaProduto = !(produto.value == 'Selecione');
    var validaLoja = !(loja.value == 'Selecione');
    var validaLocal = !(local.value == 'Selecione');
    var validaFornecedor = !(fornecedor.value == 'Selecione');
    var regex = /[A-z]/g;
    var validaVenda = (valorVenda.value.length > 0 && regex.exec(valorVenda.value) == null);
    var validaCusto = (valorCusto.value.length > 0 && regex.exec(valorCusto.value) == null);

    var validacoes = [
        {
            nome: 'produto',
            valido: validaProduto,
            mensagem: 'Escolha o produto!'
        },
        {
          nome: 'loja',
          valido: validaLoja,
          mensagem: 'Escolha a loja!'
        },
        {
          nome: 'local',
          valido: validaLocal,
          mensagem: 'Escolha o local!'
        },
        {
          nome: 'fornecedor',
          valido: validaFornecedor,
          mensagem: 'Escolha o fornecedor!'
        },
        {
          nome: 'venda',
          valido: validaVenda,
          mensagem: 'Valor de venda inválido!'
        },
        {
          nome: 'custo',
          valido: validaCusto,
          mensagem: 'Valor de custo inválido!'
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
    document.querySelector('#titulo').innerText = 'Novo item';
    document.querySelector("#button-cad").innerText = 'Cadastrar';
    document.querySelector("#form-body").classList.remove('card-info');
    document.querySelector("#button-cad").classList.remove('btn-info');
    document.querySelector("#form-body").classList.add('card-primary');
    document.querySelector("#button-cad").classList.add('btn-primary');
    document.querySelector("#erros-form").innerText = "";
    document.querySelector("#aviso").innerText = "";
    document.querySelector("#aviso").style.display = "none";
    document.querySelector("#produto").value = "Selecione";
    document.querySelector("#loja").value = "Selecione";
    document.querySelector("#local").value = "Selecione";
    document.querySelector("#fornecedor").value = "Selecione";
    document.querySelector("#valor-venda").value = "";
    document.querySelector("#valor-custo").value = "";
  }
</script>
</body>
</html>
