<?php

include_once 'banco.class.php';
class produto{
    
    private $id;
    private $nome;
    private $descricao;
    private $quantidade_minima;
    private $quantidade_ideal;

    
	public function listar(){
		$banco = new banco();
		$sql = "SELECT * FROM produto order by nome DESC;";
		$result = $banco->executa($sql);
        $banco->close();
		return $result;
    }    
	
    public function salvar($codigo, $nome, $descricao, $quantidade_minima, $quantidade_ideal){
		$banco = new banco();
		$banco->begin();
		$sql = "INSERT INTO produto (codigo, nome, descricao, quantidade_min, quantidade_ideal) VALUES
				($codigo, '$nome', '$descricao', '$quantidade_minima', '$quantidade_ideal');";
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

    public function editar($id, $codigo, $nome, $descricao, $quantidade_minima, $quantidade_ideal){
        $banco = new banco();
		$banco->begin();
		$sql = "UPDATE produto set 
			codigo = $codigo,
	    	nome = '$nome',
			descricao = '$descricao',
			quantidade_min = $quantidade_minima,
			quantidade_ideal = $quantidade_ideal
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
$produto = new produto();    
?>