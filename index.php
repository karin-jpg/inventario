<?php
session_start();
error_reporting(0);
include_once 'class/banco.class.php';

$msg = "";
if($_POST[acao] == login){
  $login = $_POST[login];
  $senha = $_POST[senha];
  $sql = "SELECT * from usuario where nome = '".$login."'";
    

    $rs = $banco->executa($sql);
    
    if(password_verify($senha, $rs[0]['senha'])){
        
        $_SESSION['id-usuario'] = $rs[0]['id'];
        $_SESSION['usuario'] = $rs[0]['nome'];
        $_SESSION['tipo-usuario'] = $rs[0]['tipo'];
        header("location: administrador/home.php");
    }else{
        $msg = "Usuário ou senha incorreta!";
        session_destroy();
    }
  }

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAGLI</title>
    <link rel="stylesheet" type="text/css" href="estilo/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="estilo/css/fontawesome-all.min.css">
    <link rel="stylesheet" type="text/css" href="estilo/css/style.css">
    <link rel="stylesheet" type="text/css" href="estilo/css/temaInventario.css">
</head>
<body>

    <div class="form-body">
        <div class="row">
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">
                        <div class="website-logo-inside">
                                <div class="logo">
                                    <img class="logo-size" src="images/logo-light.svg" alt="">
                                </div>
                            </a>
                        </div>
                        <h3>SAGLI</h3>
                        <h5 style="color:red; display:<?=($msg) ? 'block' : 'none'?>;"><?=$msg?></h5>
                        <div class="page-links">
                        </div>
                        <form method='POST' action =''>
                            <input class="form-control" type="hidden" name="acao" value = "login">
                            <input class="form-control" type="text" name="login" placeholder="Usuário" required>
                            <input class="form-control" type="password" name="senha" placeholder="Senha" required>
                            
                            <div class="form-button">
                                <button id="submit" type="submit" class="ibtn">Entrar</button> <a href="">Esqueceu a senha?</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>