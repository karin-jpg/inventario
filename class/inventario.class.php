<?php

include_once 'banco.class.php';
class inventario{
    
	public function listar(){
		$banco = new banco();
		$sql = "SELECT i.id, p.nome AS produto, p.quantidade_ideal as ideal, p.quantidade_min as minima, l.nome AS loja, lo.nome AS local, f.nome AS fornecedor, valor_venda, valor_custo, quantidade FROM inventario i
				INNER JOIN produto p ON (i.id_produto = p.id)
				INNER JOIN loja l ON (i.id_loja = l.id)
				INNER JOIN fornecedor f ON (i.id_fornecedor = f.id)
				INNER JOIN local lo ON (i.id_local = lo.id)
				ORDER BY (i.quantidade/p.quantidade_min) ASC;
				";
		$result = $banco->executa($sql);
        $banco->close();
		return $result;
    }    
	
    public function salvar($id_produto, $id_loja, $id_fornecedor, $id_local, $valor_venda, $valor_custo){
		$banco = new banco();
		$banco->begin();
		$sql = "INSERT INTO inventario (id_produto, id_loja, id_fornecedor, id_local, valor_venda, valor_custo, quantidade) VALUES
				('$id_produto', '$id_loja', '$id_fornecedor', '$id_local', $valor_venda, $valor_custo, 0);";
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
$inventario = new inventario();    
?>