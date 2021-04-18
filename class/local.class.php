<?php

include_once 'banco.class.php';
class local{
    
    private $id;
    private $nome;


    
	public function listar(){
		$banco = new banco();
		$sql = "SELECT * FROM local order by nome DESC;";
		$result = $banco->executa($sql);
        $banco->close();
		return $result;
    }    
	
    public function salvar($nome){
		$banco = new banco();
		$banco->begin();
		$sql = "INSERT INTO local (nome) VALUES
				('$nome');";
		$banco->executa($sql);
		
		if($banco->error()[0] != '00000'){
			$banco->rollback();
			$banco->close();
			return 0;
		}else{
			$banco->commit();
			$banco->close();
			return 1;
		}
    }    

    public function editar($id, $nome){
        $banco = new banco();
		$banco->begin();
		$sql = "UPDATE local set 
	    	nome = '$nome'
		  where id = $id";

		  $banco->executa($sql);
           if($banco->error()[0] != '00000'){
		  	$banco->rollback();
		  	$banco->close();
		  	return 0;
		  }else{
		  	$banco->commit();
		  	$banco->close();
		  	return 1;
		  }
    }    
}
$local = new local();    
?>