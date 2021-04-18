<?php

include_once 'banco.class.php';
class status{
    
    private $id;
    private $nome;


    
	public function listar(){
		$banco = new banco();
		$sql = "SELECT * FROM status";
		$result = $banco->executa($sql);
        $banco->close();
		return $result;
    }      
}
$status = new status();    
?>