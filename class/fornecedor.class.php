<?php

include_once 'banco.class.php';
class fornecedor{
    
    private $id;
    private $nome;
    private $cep;
    private $estado;
    private $cidade;
    private $bairro;
    private $logradouro;
    private $numero;
    private $telefone;

    
	public function listar(){
		$banco = new banco();
		$sql = "SELECT * FROM fornecedor order by nome;";
		$result = $banco->executa($sql);
        $banco->close();
		return $result;
    }    
	
    public function salvar($nome, $cep, $estado, $cidade, $bairro, $logradouro, $numero, $telefone){
		$banco = new banco();
		$banco->begin();
		$sql = "INSERT INTO fornecedor (nome, cep, estado, cidade, bairro, logradouro, numero, telefone) VALUES
				('$nome', '$cep', '$estado', '$cidade', '$bairro', '$logradouro', '$numero', '$telefone');";
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

    public function editar($id, $nome, $cep, $estado, $cidade, $bairro, $logradouro, $numero, $telefone){
        $banco = new banco();
		$banco->begin();
		$sql = "UPDATE fornecedor set 
	    	nome = '$nome',
			cep = '$cep',
            estado = '$estado',
            cidade = '$cidade',
            bairro = '$bairro',
            logradouro = '$logradouro',
            numero = '$numero',
			telefone = '$telefone'
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
$fornecedor = new fornecedor();    
?>