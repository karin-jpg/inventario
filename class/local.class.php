<?php

include_once 'banco.class.php';
class local{
    
    private $id;
    private $nome;
	private $loja;


    
	public function listar(){
		$banco = new banco();
		$sql = "SELECT l.id, l.nome, lo.nome AS nome_loja FROM local l
				INNER JOIN loja lo ON (l.id_loja = lo.id )
				ORDER BY l.nome DESC";
		$result = $banco->executa($sql);
        $banco->close();
		return $result;
    }    
	
    public function salvar($nome, $id_loja){
		$banco = new banco();
		$banco->begin();
		$sql = "INSERT INTO local (nome, id_loja) VALUES
				('$nome', '$id_loja');";
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

    public function editar($id, $nome, $id_loja){
        $banco = new banco();
		$banco->begin();
		$sql = "UPDATE local SET
	    	nome = '$nome',
			id_loja = '$id_loja'
		  	WHERE id = $id";

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