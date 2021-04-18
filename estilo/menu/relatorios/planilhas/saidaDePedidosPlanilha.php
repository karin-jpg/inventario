<?php
session_start();
if(!$_SESSION['login'])
{
  header("Location:../../../../index.php");
}
error_reporting(0);
include_once '../../../class/banco.class.php';
$banco = new banco();
$condicao = "";
$dt_inicial = $_GET[dt_inicial] ? $_GET[dt_inicial] : "";
$dt_final = $_GET[dt_final] ? $_GET[dt_final] : "";
$remedio = $_GET[remedio] ? $_GET[remedio] : "";
$pa = $_GET[pa] ? $_GET[pa] : "";
$cliente = $_GET[cliente] ? $_GET[cliente] : "";
$protocolo = $_GET[protocolo] ? $_GET[protocolo] : "";

if($dt_inicial && $dt_final){
				
	$condicao .= " AND (p.dt_inicial >= '".$dt_inicial."' and p.dt_inicial <= '".$dt_final."')";
	}
if($dt_inicial && !$dt_final){
		$condicao .= " AND p.dt_inicial >= '".$dt_inicial."'";
	}
if($remedio){
        $condicao .= " AND r.nome = '".$remedio."'";
    }

if($pa){
        $condicao .= " AND pa.nome_principio_ativo = '".$pa."'";
   	}

if($cliente){
        $condicao .= " AND c.nome = '".$cliente."'";
    }

if($protocolo){
        $condicao .= " AND p.protocolo = '".$protocolo."'";
    }


		$sql = 'SELECT r.nome as remedio_nome,  pa.nome_principio_ativo as nome_pa, p.quantidade, c.nome as cliente_nome, CONCAT(SUBSTRING(p.dt_final, 9), "/", SUBSTRING(p.dt_final, 6, 2), "/", SUBSTRING(p.dt_final, 1, 4)) AS dt_final, p.protocolo FROM pedidos p 
          INNER JOIN clientes c ON (p.id_cliente = c.id)
          INNER JOIN remedios r ON (r.id = p.id_remedios)
          INNER JOIN principio_ativo pa ON (r.principio_ativo_id = pa.id)
          WHERE p.dt_final is not null'.$condicao;
$rs = $banco->executa($sql);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset = "utf-8">
<title>Contato</title>
</head>
<body>
<?php
$html = '';
$html .= '<table border="1">';
$html .= '<tr>';
$html .= '<td colspan="6"><b>Planilha saída de pedidos</b></td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td><b>Nome do remédio</b></td>';
$html .= '<td><b>Princípio ativo</b></td>';
$html .= '<td><b>Quantidade de saída</b></td>';
$html .= '<td><b>Cliente</b></td>';
$html .= '<td><b>Protocolo</b></td>';
$html .= '<td><b>Data de saída</b></td>';
$html .= '</tr>';

for($i = 0; $i < count($rs); $i++){

$html .= '<tr>';
$html .= '<td>'.$rs[$i][remedio_nome].'</td>';
$html .= '<td>'.$rs[$i][nome_pa].'</td>';
$html .= '<td>'.$rs[$i][quantidade].'</td>';
$html .= '<td>'.$rs[$i][cliente_nome].'</td>';
$html .= '<td>'.$rs[$i][protocolo].'</td>';
$html .= '<td>'.$rs[$i][dt_final].'</td>';
$html .= '</tr>';
}

$html .= '</table>';

header("Expires: Mon, 07 Jul 2020 05:00:00 GMT");
header("Last-Modified: ". gmdate("D, d M YH:i:s") . " GMT");
header("Cache-control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: application/x-msexcel");
header("Content-Disposition: attachment; filename=saidaDePedidos.xls");
header("Content-Description: PHP Generated Data");

echo $html;
exit;
?>
</body>
</html>