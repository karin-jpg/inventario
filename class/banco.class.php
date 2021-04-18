<?php
class banco{
    var $siteconfig;
	var $dsn;
	var $con;	// resultado da conexão
	var $rs;	// result set

	function banco(){
      

		define('DB_HOST'        , 'localhost');
		define('DB_USER'        , 'root');
		define('DB_PASSWORD'    , '');
		define('DB_NAME'        , 'sagli_db');
		define('DB_DRIVER'        , 'mysql');
		
		$pdoConfig  = DB_DRIVER . ":". "host=" . DB_HOST . ";";
		$pdoConfig .= "dbname=".DB_NAME .";";
		
		$options =[
			PDO::MYSQL_ATTR_FOUND_ROWS   => TRUE,
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		];
		try {
			if(!isset($this->con)){
				$this->con =  new PDO($pdoConfig, DB_USER, DB_PASSWORD, $options);
			}
		} catch (PDOException $e) {
			$mensagem = "Drivers disponiveis: " . implode(",", PDO::getAvailableDrivers());
			$mensagem .= "\nErro: " . $e->getMessage();
			throw new Exception($mensagem);
		}
		
	}

	function proc($sql) {
    	$this->rs = $this->con->query($sql);
		$this->last_insert_id();
		$this->error();
		$this->errno();	
		if ($this->error()) {
			echo $this->error();
			
		}
	}
	
	function executaFull($sql, $autocommit = false) {
		$retorno = null;
		try {
			if($autocommit == true){
				$this->begin();
			}
			
			$this->querySQL = $sql; //Para apoio do LOG
			
			$this->rs = $this->con->query($sql); 
			$this->last_insert_id();

			$this->error();
			$this->errno();			
			
			if($this->rs)
			{
				$count  = $this->rs->rowCount();
				
				try
				{
					$retorno = $this->rs->fetchAll();
				}
				catch(Exception $x){ }
				
				//file_put_contents("test_PA_banco.txt","Query: ".print_r($this->rs,true)."\nQuery fetch: ".print_r($retorno,true)."\nCount: ".$count);
				$alo = is_array($retorno) ? count($retorno) : 0;
				if($alo==0 && $count>0){
					$retorno[] = [ "0" => $count, "count" => $count ];
					//file_put_contents("test_PA_banco-2.txt","Query: ".print_r($this->rs,true)."\nQuery fetch: ".print_r($retorno,true)."\nCount: ".$count);
				}
			}
			else if(!$this->rs)
			{
				$temp = explode('/',getcwd());
			}
		
			if($autocommit == true){
				$this->commit();
			}
			
		} catch (Exception $e){
			$temp = explode('/',getcwd());
			
			
		}

		return $retorno;
	}

	function executa() { 
		try {
			$numArgs = func_num_args();
			if($numArgs == 2) {
				$sql 		= func_get_arg(0);
				$autocommit =  func_get_arg(1);
				return $this->executaFull($sql, $autocommit);
			} else {
				$sql = func_get_arg(0);
				return $this->executaFull($sql);
			}
		} catch (Exception $e){
			var_dump($e);
		}
	}

	function linhas($sql){
    	$this->rs = $this->con->query ($sql);
		if (DB::isError ($this->rs)) {
			die(DB::errorMessage($this->rs ) ." - ". $sql);
		}
		$qtd = $this->rs->numRows ();
		$this->rs->free ();
		return $qtd;
	}
	
	function begin() {
		$this->con->beginTransaction();
	}
	
	function close() {
		$this->con = null;
	}
	
	function rollback() {
		 $this->con->rollBack();
	}
	

	
	function commit() {
		 $this->con->commit();
	}

    function filter($value, $modes = array('sql')) {
        if (!is_array($modes)) {
            $modes = array($modes);
        }
        if (is_string($value)) {
            foreach ($modes as $type) {
              $value = $this->_doFilter($value, $type);
            }
            return $value;
        }
        foreach ($value as $key => $toSanatize) {
            if (is_array($toSanatize)) {
                $value[$key]= $this->filter($toSanatize, $modes);
            } else {
                foreach ($modes as $type) {
                  $value[$key] = $this->_doFilter($toSanatize, $type);
                }
            }
        }
        return $value;
    }
	
    function _doFilter($value, $mode) {
        switch ($mode) {
            case 'html':
                $value = strip_tags($value);
                $value = addslashes($value);
                $value = htmlspecialchars($value);
                break;
        
            case 'sql':
                $value = preg_replace('/(FROM|SELECT|INSERT|SLEEP|SYSDATE|DELETE|WHERE|DROP TABLE|SHOW TABLES|from|select|insert|sleep|sysdate|delete|where|drop table|show tables|#|\*|\\\\)/','',$value);
                $value = trim($value);
                break;
        }
        return $value;
    }
	
	function limpasqlinject(){
		$_GET = $this->filter($_GET);
		$_POST = $this->filter($_POST);
	}
	
	function last_insert_id(){
		return  $this->con->lastInsertId();
	}

	function errno(){
		return $this->con->connection->errno;
	}
	
	function error(){
		return $this->con->errorInfo();
	}


}

$banco = new banco;
$banco->limpasqlinject();


?>
