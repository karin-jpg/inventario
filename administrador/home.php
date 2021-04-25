<?php
session_start();
error_reporting(0);
include("../class/acesso.class.php");
if(!isset($_SESSION['id-usuario'])){
  header("location: ../index.php");
}

$permissoes = $acesso->listar();

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SAGLI</title>
  <!-- https://fontawesome.com/icons/bars -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../estilo/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="../estilo/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../estilo/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="../estilo/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../estilo/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../estilo/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../estilo/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../estilo/plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>


    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-id-badge"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <div class="dropdown-divider"></div>
          <a onclick="confirmaLogout()" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> Sair
          </a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block"><?= ucfirst($_SESSION['usuario']) ?></a>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
      <li class="nav-item">
        <a href="#" onClick="abre_arquivo('inventario/inventario.php');" class="nav-link">
          <i class="nav-icon fas fa-archive"></i>
            <p>
              Inventário
            </p>
        </a>

      </li>
      <?php if($permissoes[0]['produtos']){?>
			<li class="nav-item">
        <a href="#" onClick="abre_arquivo('produto/produto.php');" class="nav-link">
          <i class="nav-icon fas fa-barcode"></i>
            <p>
              Produtos
            </p>
        </a>
      </li>
      <?php } 
      
      if($permissoes[0]['fornecedores']){
      ?>
      <li class="nav-item">
        <a href="#" onClick="abre_arquivo('fornecedor/fornecedor.php');" class="nav-link">
          <i class="nav-icon fas fa-dolly-flatbed"></i>
            <p>
              Fornecedores
            </p>
        </a>
      </li>
      <?php } 
      
      if($permissoes[0]['lojas']){
      ?>
      <li class="nav-item">
        <a href="#" onClick="abre_arquivo('loja/loja.php');" class="nav-link">
          <i class="nav-icon fas fa-home"></i>
            <p>
              Lojas
            </p>
        </a>
      </li>
      <?php 
        }
      
        if($permissoes[0]['locais']){?>
      <li class="nav-item">
        <a href="#" onClick="abre_arquivo('local/local.php');" class="nav-link">
          <i class="nav-icon fas fa-door-closed"></i>
            <p>
              Locais
            </p>
        </a>
      </li>
      <?php 
        }
      
        if($permissoes[0]['usuarios']){

      ?>
      <li class="nav-item">
        <a href="#" onClick="abre_arquivo('usuarios/usuario.php');" class="nav-link">
        <i class="nav-icon fas fa-chalkboard-teacher"></i>
            <p>
              Usuários
            </p>
        </a>
      </li>
      
      <?php }
      
        if($permissoes[0]['relatorios']){

      ?>
      <li class="nav-item">
        <a href="#" onClick="abre_arquivo('relatorios/relatorios.php');" class="nav-link">
          <i class="nav-icon fas fa-file-signature"></i>
            <p>
              Relatórios
            </p>
        </a>
      </li>
      <?php }?>




        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
   
	<iframe style="overflow: hidden;" src="inventario/inventario.php" width="100%" height="850px" frameborder="0" class="" id="main">&nbsp;</iframe>
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong><a href="https://github.com/karin-jpg/" target="_blank">Karín Morais</a></strong>
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1

    </div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../estilo/plugins/jquery/jquery.min.js"></script>
<script src="../estilo/build/js/funcoes.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../estilo/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="../estilo/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="../estilo/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="../estilo/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="../estilo/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../estilo/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="../estilo/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../estilo/plugins/moment/moment.min.js"></script>
<script src="../estilo/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../estilo/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="../estilo/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="../estilo/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../estilo/dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../estilo/dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../estilo/dist/js/demo.js"></script>
<script>
function confirmaLogout()
{
	if(confirm("Deseja realmente sair?"))
	{
		 window.location.href = "../logout.php";
	}
}


</script>
</body>
</html>
