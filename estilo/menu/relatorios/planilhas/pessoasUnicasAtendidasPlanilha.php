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
$bairro = $_GET[bairro] ? $_GET[bairro] : "";

if($dt_inicial && $dt_final){
				
	$condicao .= " AND (p.dt_inicial >= '".$dt_inicial."' and p.dt_inicial <= '".$dt_final."')";
	}
if($dt_inicial && !$dt_final){
		$condicao .= " AND p.dt_inicial >= '".$dt_inicial."'";
	}
if($bairro){
				$condicao .= " AND c.bairro = '".$bairro."'";
			}


		$sql = 'SELECT
	c.nome,
	p.protocolo,
	c.bairro,
	p.dt_inicial, 
	CONCAT(SUBSTRING(p.dt_inicial, 9), "/", SUBSTRING(p.dt_inicial, 6, 2), "/", SUBSTRING(p.dt_inicial, 1, 4)) as dt_inicial
from
	clientes c
inner join pedidos p on (c.id = p.id_cliente) WHERE 1 = 1 '.$condicao.'
	group by p.protocolo ';
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
$html .= '<td colspan="4"><b>Planilha pesoas unicas atendidas</b></td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td><b>Cliente</b></td>';
$html .= '<td><b>Protocolo criado</b></td>';
$html .= '<td><b>Bairro</b></td>';
$html .= '<td><b>Data do pedido</b></td>';
$html .= '</tr>';

for($i = 0; $i < count($rs); $i++){

$html .= '<tr>';
$html .= '<td>'.$rs[$i][nome].'</td>';
$html .= '<td>'.$rs[$i][protocolo].'</td>';
$html .= '<td>'.$rs[$i][bairro].'</td>';
$html .= '<td>'.$rs[$i][dt_inicial].'</td>';
$html .= '</tr>';
}

$html .= '</table>';

header("Expires: Mon, 07 Jul 2020 05:00:00 GMT");
header("Last-Modified: ". gmdate("D, d M YH:i:s") . " GMT");
header("Cache-control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: application/x-msexcel");
header("Content-Disposition: attachment; filename=pessoasUnicasAtendidas.xls");
header("Content-Description: PHP Generated Data");

echo $html;
exit;
?>
</body>
</html>