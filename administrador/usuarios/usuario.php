<?php
session_start();
error_reporting(0);
if(!isset($_SESSION['id-usuario'])){
  header("location: ../../index.php");
}
include_once('../../class/usuario.class.php');

$acao = $_POST['acao'];
if($acao == 'novo'){

  $sucesso = $usuario->salvar($_POST['nome'], $_POST['senha'], $_POST['tipo-usuario'], $_POST['acesso-produto'], $_POST['acesso-fornecedor'], $_POST['acesso-loja'], $_POST['acesso-local'], $_POST['acesso-relatorio']);
  if($sucesso){
    $msg = "Usuário Cadastrado!";
  }else{
    $msg = "Ocorreu um erro ao cadastrar o usuário, caso o mesmo persista contate o suporte";
  }
}

if($acao == 'editar'){
  $sucesso = $usuario->editar($_POST['id-usuario'], $_POST['senha'], $_POST['tipo-usuario'], $_POST['acesso-produto'], $_POST['acesso-fornecedor'], $_POST['acesso-loja'], $_POST['acesso-local'], $_POST['acesso-relatorio']);
  if($sucesso){
    $msg = "Usuário atualizado!";
  }else{
    $msg = "Ocorreu um erro ao atualizar o usuário, caso o mesmo persista contate o suporte";
  }
}


function tipoUsuario($tipo){
  if($tipo == 1){
    return 'Administrador';
  }else if($tipo == 2){
    return 'Usuário';
  }
}

$usuarios = $usuario->listar();
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
            <h1 class="m-0 text-dark">USUÁRIOS</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Página inicial</a></li>
              <li class="breadcrumb-item active">Usuários</li>
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
						<button type="button" class="btn btn-block btn-outline-primary" data-toggle="modal" data-target="#modal-default" onClick="clearForm()">Novo</button>
			</div>
			</div>
			  
              
              <label id="aviso" style="text-align:center; color:<?= ($sucesso && $acao) ? 'blue' : 'red'?>; display:<?($acao) ? 'block' : 'none'?>;"><?= $msg?></label>
              <div class="card-body">
                 <table id="tableUsuario" class="table table-bordered table-striped">
                     <thead>
                       <tr>
                         <th>Nome</th>
                         <th>Tipo</th>
                         <th>Acesso produtos</th>
                         <th>Acesso fornecedores</th>
                         <th>Acesso lojas</th>
                         <th>Acesso locais</th>
                         <th>Acesso relatórios</th>
                       </tr>
                     </thead>
                     <tbody>
		    		            <?php
		    		            for($i = 0; $i < count($usuarios); $i++)
		    		            {
		    			          echo
		    			          '<tr id = "'.$usuarios[$i]['id'].'">
		    			            <td>'.$usuarios[$i]['nome'].'</td>
                          <td>'.tipoUsuario($usuarios[$i]['tipo']).'</td>
                          <td>'.($usuarios[$i]['produtos'] ? 'Sim' : 'Não').'</td>
                          <td>'.($usuarios[$i]['fornecedores'] ? 'Sim' : 'Não').'</td>
                          <td>'.($usuarios[$i]['lojas'] ? 'Sim' : 'Não').'</td>
                          <td>'.($usuarios[$i]['locais'] ? 'Sim' : 'Não').'</td>
                          <td>'.($usuarios[$i]['relatorios'] ? 'Sim' : 'Não').'</td>
		    			          </tr>';
		    		            }
		    			          ?>
                     </tbody>
                     <tfoot>
                     <tr>
                      <th>Nome</th>
                      <th>Tipo</th>
                      <th>Acesso produtos</th>
                      <th>Acesso fornecedores</th>
                      <th>Acesso lojas</th>
                      <th>Acesso locais</th>
                      <th>Acesso relatórios</th>
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
                <h3 class="card-title" id="titulo"></h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" id = "form" method="post" action="usuario.php">
              <input type="hidden" name="acao" id = "acao" value = "">
              <input type="hidden" name="id-usuario" id = "id-usuario" value = "">
                <div class="card-body">
                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" class="form-control" name="nome" id = "nome">
                    </div>

                    <div class="form-group">
                        <label id="label-senha">Senha</label>
                        <input type="password" class="form-control" name="senha" id = "senha">
                    </div>

                    <div class="form-group" id="div-confirma-senha">
                        <label>Confirmar senha</label>
                        <input type="password" class="form-control" name="confirma-senha" id = "confirma-senha">
                    </div>

                    <div class="form-group">
					            <label>Tipo</label>
					              <select class="form-control select2" name = "tipo-usuario" id = "tipo-usuario" style="width: 100%;">
                        <option value="2">Usuário</option>
                        <option value="1">Administrador</option>
                        
					              </select>
                    </div>

                    <div class="form-group">
					            <label>Acesso produtos</label>
					              <select class="form-control select2" name = "acesso-produto" id = "acesso-produto" style="width: 100%;">
                        <option value="0">Não</option>
                        <option value="1">Sim</option>
                        
					              </select>
                    </div>
                    
                    <div class="form-group">
                      <label>Acesso fornecedores</label>
					              <select class="form-control select2" name = "acesso-fornecedor" id = "acesso-fornecedor" style="width: 100%;">
                        <option value="0">Não</option>
                        <option value="1">Sim</option>
                        
					              </select>
                    </div>

                    <div class="form-group">
                      <label>Acesso lojas</label>
					              <select class="form-control select2" name = "acesso-loja" id = "acesso-loja" style="width: 100%;">
                        <option value="0">Não</option>
                        <option value="1">Sim</option>
                        
					              </select>
                    </div>

                    <div class="form-group">
                      <label>Acesso locais</label>
					              <select class="form-control select2" name = "acesso-local" id = "acesso-local" style="width: 100%;">
                        <option value="0">Não</option>
                        <option value="1">Sim</option>
                        
					              </select>
                    </div>

                    <div class="form-group">
                      <label>Acesso relatórios</label>
					              <select class="form-control select2" name = "acesso-relatorio" id = "acesso-relatorio" style="width: 100%;">
                        <option value="0">Não</option>
                        <option value="1">Sim</option>
                        
					              </select>
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
document.querySelector("#tableUsuario").addEventListener("dblclick", function(event){
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
  titulo.innerText = 'Editar Usuário';
  
      var nome = document.querySelector('#nome');
      var labelSenha = document.querySelector('#label-senha');
      var filhos = event.target.parentNode.children;
      var tipo = document.querySelector('#tipo-usuario')
      var produtos = document.querySelector('#acesso-produto');
      var fornecedor = document.querySelector('#acesso-fornecedor');
      var loja = document.querySelector('#acesso-loja');
      var local = document.querySelector('#acesso-local');
      var relatorio = document.querySelector('#acesso-relatorio');
      

      nome.disabled = true;
      labelSenha.innerText = "Alterar Senha"
      document.querySelector('#id-usuario').value = event.target.parentNode.id;
      document.querySelector('#nome').value = filhos[0].innerText;
      setValor(tipo, filhos[1]);
      setValor(produtos, filhos[2]);
      setValor(fornecedor, filhos[3]);
      setValor(loja, filhos[4]);
      setValor(local, filhos[5]);
      setValor(relatorio, filhos[6]);

  $("#modal-default").modal({
    show: true
  });    

  });

</script>
<script>


$(function () {
	

	  
  $("#tableUsuario").DataTable({
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


  function setValor(campo, valor){
    Array.prototype.map.call(campo.options, (campo) => {
        if(campo.innerText == valor.innerText){
          campo.selected = true;
        }
      });
  }


  function validaCad()
  {
    var form = document.querySelector("#form");
    var acao = document.querySelector("#acao");
  	var nome = document.querySelector("#nome");
    var senha = document.querySelector('#senha');
    var confirmaSenha = document.querySelector('#confirma-senha');
    var validaSenha = false;
    var mensagemErroSenha = "";
    if(acao.value == "editar"){
      validaSenha = ((senha.value.length == 0 && confirmaSenha.value.length == 0) || (senha.value == confirmaSenha.value));
      mensagemErroSenha = "As senhas informadas devem ser iguais ou vazias!";
    }else{
      validaSenha = ((senha.value == confirmaSenha.value) && senha.value.length >= 8);
      mensagemErroSenha = "As senhas informadas tem que ser iguais e com pelo menos 8 caracteres!";
    }
    
    var nomeValido = nome.value.length >= 5;
    
    var labelErro = document.querySelector("#erros-form");
    
    var validacoes = [
        {
            nome: 'nome',
            valido: nomeValido,
            mensagem: 'Nome do usuário deve ter no mínimo 5 caracteres!'
        },
        {
          nome: 'senha',
          valido: validaSenha,
          mensagem: mensagemErroSenha
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
    document.querySelector('#label-senha').innerText = "Senha";
    document.querySelector('#nome').disabled = false;
    document.querySelector("#nome").value = "";
    document.querySelector('#titulo').innerText = 'Novo Usuário';
    document.querySelector("#button-cad").innerText = 'Cadastrar';
    document.querySelector("#form-body").classList.remove('card-info');
    document.querySelector("#button-cad").classList.remove('btn-info');
    document.querySelector("#form-body").classList.add('card-primary');
    document.querySelector("#button-cad").classList.add('btn-primary');
    document.querySelector("#erros-form").innerText = "";
    document.querySelector("#aviso").innerText = "";
    document.querySelector("#aviso").style.display = "none";

    document.querySelector('#senha').value = "";
    document.querySelector('#confirma-senha').value = "";
    document.querySelector('#acesso-produto').value = "2";
    document.querySelector('#acesso-produto').value = "0";
    document.querySelector('#acesso-fornecedor').value = "0";
    document.querySelector('#acesso-loja').value = "0";
    document.querySelector('#acesso-local').value = "0";
    document.querySelector('#acesso-relatorio').value = "0";
  }
</script>
</body>
</html>
