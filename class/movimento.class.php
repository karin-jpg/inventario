<?php
error_reporting(0);
include_once 'banco.class.php';
class movimento{

    
	public function listarEntrada(){
		$banco = new banco();
		$sql = "SELECT e.id, p.nome, e.data_entrada, e.quantidade_entrada FROM entrada e
        INNER JOIN inventario i ON (e.id_inventario = i.id)
        INNER JOIN produto p ON (i.id_produto = p.id );";
		$result = $banco->executa($sql);
        $banco->close();
		return $result;
    }    

    public function salvarEntrada($id_inventario, $quantidade_entrada, $id_status){
		
		$banco = new banco();
		$banco->begin();
        $id_usuario = $_SESSION['id-usuario'];
		$sql = "INSERT INTO entrada (id_inventario, data_entrada, quantidade_entrada, id_usuario) VALUES
				('$id_inventario', now(), '$quantidade_entrada', '$id_usuario')";
		$banco->executa($sql);
		
		if($banco->error()[0] != '00000'){
			$banco->rollback();
			$banco->close();
			return 0;
		}else{
			
			$sql = "SELECT id_loja from inventario where id = $id_inventario";
			$rs = $banco->executa($sql);
			 

			$id_loja = $rs[0]['id_loja'];

			$sql = "SELECT * from detalha_item where id_item = $id_inventario and id_status = $id_status and id_loja = $id_loja";
			$rs = $banco->executa($sql);

			if(count($rs)){
				$sql = "UPDATE detalha_item set quantidade = quantidade + $quantidade_entrada where id_item = $id_inventario and id_status = $id_status and id_loja = $id_loja";
				$banco->executa($sql);
			}else{
				$sql = "INSERT INTO detalha_item(id_item, id_status, id_loja, quantidade) VALUES
						($id_inventario, $id_status, $id_loja, $quantidade_entrada)";
				$banco->executa($sql);
			}
            if($banco->error()[0] != '00000'){
                $banco->rollback();
			    $banco->close();
			    return 0;
            }else{
				$sql = "UPDATE inventario set quantidade = quantidade + $quantidade_entrada where id = $id_inventario";
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
    }

	public function validaQuantidade($id_inventario, $quantidade_saida){
		$banco = new banco();
		$sql = "SELECT quantidade from inventario where id = $id_inventario";
		$rs = $banco->executa($sql);

		return ($rs[0]['quantidade'] >= $quantidade_saida);
	}

	public function salvarSaida($id_inventario, $ordem_saida, $quantidade_saida, $id_status){
		$banco = new banco();
		$banco->begin();
        $id_usuario = $_SESSION['id-usuario'];
		$sql = "INSERT INTO saida (id_inventario, ordem_saida, data_saida, quantidade_saida, id_usuario) VALUES
				('$id_inventario', $ordem_saida, now(), '$quantidade_saida', '$id_usuario')";
				
		$banco->executa($sql);
		
		if($banco->error()[0] != '00000'){
			echo $sql;
			die;
			$banco->rollback();
			$banco->close();
			return 0;
		}else{
			
			$sql = "SELECT id_loja from inventario where id = $id_inventario";
			$rs = $banco->executa($sql);
			 

			$id_loja = $rs[0]['id_loja'];

			$sql = "SELECT * from detalha_item where id_item = $id_inventario and id_status = $id_status and id_loja = $id_loja";
			$rs = $banco->executa($sql);

			if(count($rs)){
				$sql = "UPDATE detalha_item set quantidade = quantidade - $quantidade_saida where id_item = $id_inventario and id_status = $id_status and id_loja = $id_loja";
				$banco->executa($sql);
			}else{
				$sql = "INSERT INTO detalha_item(id_item, id_status, id_loja, quantidade) VALUES
						($id_inventario, $id_status, $id_loja, $quantidade_saida)";
				$banco->executa($sql);
			}
			
            if($banco->error()[0] != '00000'){
                $banco->rollback();
			    $banco->close();
			    return 0;
            }else{
				$sql = "SELECT quantidade from inventario where id = $id_inventario";
				$rs = $banco->executa($sql);
				
				$sql = "UPDATE inventario set quantidade = quantidade - $quantidade_saida where id = $id_inventario";
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
    } 
}
$movimento = new movimento();    
?>