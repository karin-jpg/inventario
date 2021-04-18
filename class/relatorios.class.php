<?php

include_once 'banco.clASs.php';
clASs relatorios{
    
	public function produtosCompra($id_produto, $id_loja, $id_fornecedor){
		$banco = new banco();
		$sql = "SELECT p.nome AS produto, l.nome AS loja, f.nome AS fornecedor, i.quantidade AS 'quantidade_atual', IF(p.quantidade_ideal >= 0, p.quantidade_ideal, 0) AS ideal, IF(p.quantidade_min >= 0, p.quantidade_min, 0) AS min,
                IF((p.quantidade_ideal - i.quantidade) >= 0, p.quantidade_ideal - i.quantidade, 0) AS 'quantidade_para_ideal', IF((p.quantidade_min - i.quantidade) >= 0, p.quantidade_min - i.quantidade, 0) AS 'quantidade_para_min',
                IF((p.quantidade_ideal - i.quantidade >= 0), (i.valor_custo * (p.quantidade_ideal - i.quantidade)), 0) AS 'preco_ideal', IF(((p.quantidade_min - i.quantidade) >= 0), (i.valor_custo * (p.quantidade_min - i.quantidade)), 0) AS 'preco_min'
                FROM inventario i
                INNER JOIN produto p ON (i.id_produto = p.id)
                INNER JOIN loja l ON (i.id_loja = l.id)
                INNER JOIN fornecedor f ON (i.id_fornecedor = f.id)
                WHERE 1 = 1";

        if($id_produto){
            $sql .= " AND p.id = ".$id_produto;
        }
        if($id_loja){
            $sql .= " AND l.id = ".$id_loja;
        }
        if($id_fornecedor){
            $sql .= " AND f.id = ".$id_produto;
        }

        $sql .= " ORDER BY (i.quantidade/p.quantidade_min) ASC";
        file_put_contents('relatorio.txt', $sql);
        $_SESSION['relatorio'] = $sql;
		$result = $banco->executa($sql);
        $banco->close();
		return $result;
    }    
}
$relatorios = new relatorios();    
?>