<?php

include_once 'banco.class.php';
class acesso{

    
	public function listar(){
		$banco = new banco();
		$sql = "SELECT * FROM permissao_usuario where id_usuario = ".$_SESSION['id-usuario'];
		$result = $banco->executa($sql);
        $banco->close();
		return $result;
    }      
}
$acesso = new acesso();    
?>