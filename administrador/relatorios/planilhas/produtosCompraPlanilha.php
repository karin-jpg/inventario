<?php
session_start();
if(!isset($_SESSION['id-usuario'])){
    header("location: ../../index.php");
  }
error_reporting(0);
include_once '../../../class/banco.class.php';
$banco = new banco();

$sql = $_SESSION['relatorio'];
$rs = $banco->executa($sql);

function alteraCor($quantidade, $quantidade_min, $quantidade_ideal){
    if($quantidade >= $quantidade_ideal)
            {
              return 'white';
            }else if($quantidade < $quantidade_ideal && $quantidade > $quantidade_min){
                return 'yellow';
            }else{
                return 'red';
            }
}

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
$html .= '<td colspan="6"><b>Planilha produtos para comprar</b></td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td><b>Produto</b></td>';
$html .= '<td><b>Loja</b></td>';
$html .= '<td><b>Fornecedor</b></td>';
$html .= '<td><b>Quantidade atual</b></td>';
$html .= '<td><b>Quantidade min.</b></td>';
$html .= '<td><b>Quantidade ideal</b></td>';
$html .= '<td><b>Quantidade para atingir o min.</b></td>';
$html .= '<td><b>Quantidade para atingir o ideal</b></td>';
$html .= '<td><b>Valor de compra min.</b></td>';
$html .= '<td><b>Valor de compra ideal</b></td>';
$html .= '</tr>';
$valor_compra_min = 0;
$valor_compra_ideal = 0;

for($i = 0; $i < count($rs); $i++){
$cor = alteraCor($rs[$i]['quantidade_atual'], $rs[$i]['min'], $rs[$i]['ideal']);
$valor_compra_min += $rs[$i]['preco_min'];
$valor_compra_ideal += $rs[$i]['preco_ideal'];
$html .= '<tr>';
$html .= '<td style = "background:'.$cor.';">'.$rs[$i]['produto'].'</td>';
$html .= '<td style = "background:'.$cor.';">'.$rs[$i]['loja'].'</td>';
$html .= '<td style = "background:'.$cor.';">'.$rs[$i]['fornecedor'].'</td>';
$html .= '<td style = "background:'.$cor.';">'.$rs[$i]['quantidade_atual'].'</td>';
$html .= '<td style = "background:'.$cor.';">'.$rs[$i]['min'].'</td>';
$html .= '<td style = "background:'.$cor.';">'.$rs[$i]['ideal'].'</td>';
$html .= '<td style = "background:'.$cor.';">'.$rs[$i]['quantidade_para_min'].'</td>';
$html .= '<td style = "background:'.$cor.';">'.$rs[$i]['quantidade_para_ideal'].'</td>';
$html .= '<td style = "background:'.$cor.';">R$ '.$rs[$i]['preco_min'].'</td>';
$html .= '<td style = "background:'.$cor.';">R$ '.$rs[$i]['preco_ideal'].'</td>';
$html .= '</tr>';
}
$html .= '<tr>';
$html .= '<td></td>';
$html .= '<td></td>';
$html .= '<td></td>';
$html .= '<td></td>';
$html .= '<td></td>';
$html .= '<td></td>';
$html .= '<td></td>';
$html .= '<td><b>Total</b></td>';
$html .= '<td><b>R$ '.$valor_compra_min.'</b></td>';
$html .= '<td><b>R$ '.$valor_compra_ideal.'</b></td>';
$html .= '</tr>';

$html .= '</table>';

header("Expires: Mon, 07 Jul 2020 05:00:00 GMT");
header("Last-Modified: ". gmdate("D, d M YH:i:s") . " GMT");
header("Cache-control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: application/x-msexcel");
header("Content-Disposition: attachment; filename=produtosCompra.xls");
header("Content-Description: PHP Generated Data");

echo $html;
exit;
?>
</body>
</html>