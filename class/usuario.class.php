<?php

include_once 'banco.class.php';
class usuario{
    
    private $id;
    private $nome;
    
	public function listar(){
		$banco = new banco();
		$sql = "SELECT u.id, u.nome, u.tipo, p.produtos, p.fornecedores, p.lojas, p.locais, p.relatorios, p.usuarios FROM usuario u
                INNER JOIN permissao_usuario p
                ON (u.id = p.id_usuario)";
		$result = $banco->executa($sql);
        $banco->close();
		return $result;
    }

    public function salvar($nome, $senha, $tipo, $acesso_produto, $acesso_fornecedor, $acesso_loja, $acesso_local, $acesso_relatorio){
        $banco = new banco();
        $banco->begin();

        $sql = "INSERT INTO usuario (nome, senha, tipo) VALUES 
                ('". strtolower($nome). "', '" .password_hash($senha, PASSWORD_DEFAULT)."', $tipo)";
                file_put_contents("sql1.txt", $sql);
        $banco->executa($sql);

        
        if($banco->error()[0] != '00000'){
		  	$banco->rollback();
		  	$banco->close();
		  	return 0;
		}else{
            $id = $banco->last_insert_id();
            $sql = "INSERT INTO permissao_usuario (id_usuario, produtos, fornecedores, lojas, locais, relatorios) VALUES
                    ($id, $acesso_produto, $acesso_fornecedor, $acesso_loja, $acesso_local, $acesso_relatorio)";
                    file_put_contents("sql2.txt", $sql);
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

    public function editar($id_usuario, $senha, $tipo, $acesso_produto, $acesso_fornecedor, $acesso_loja, $acesso_local, $acesso_relatorio){
        $banco = new banco();
        $banco->begin();
        if($senha){
            $alter = " ,senha = '".password_hash($senha, PASSWORD_DEFAULT)."'";
        }else{
            $alter = "";
        }
        $sql = "UPDATE usuario set
                tipo = $tipo.$alter 
                where id = $id_usuario";
        $banco->executa($sql);
        if($banco->error()[0] != '00000'){
            $banco->rollback();
            $banco->close();
            return 0;
        }else{
            $sql = "UPDATE permissao_usuario SET
                   produtos = $acesso_produto,
                   fornecedores = $acesso_fornecedor,
                   lojas = $acesso_loja,
                   locais = $acesso_local,
                   relatorios = $acesso_local
                   WHERE id_usuario = $id_usuario";
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
$usuario = new usuario();    
?>