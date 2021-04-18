<?php
include_once '../../class/cliente.class.php';
include_once '../../class/remedio.class.php';
include_once '../../class/pedido.class.php';
error_reporting(0);
$protocolo = $_GET[protocolo];
$clientes = $cliente->listar();
$remedios = $remedio->listar();
$pedidos = $pedido->listarPedidos($protocolo);

function dateEmMysql($dateSql){
    $ano= substr($dateSql, 0, 4);
    $mes= substr($dateSql, 5,2);
    $dia= substr($dateSql, 8);
    return $dia."-".$mes."-".$ano;
}


$result = "<table>";
$result .= '<input type="hidden" name="quantidade_pedidos" id = "quantidade_pedidos" value = "'.count($pedidos).'">';
$result .= '<input type="hidden" name="protocolo" id = "protocolo" value = "'.$protocolo.'">';
   for($i = 0; $i < count($pedidos); $i++) {

         if($pedidos[$i]['status'] == 1){
   	           	$result.= '<tr><td style="width: 90%"><select class="form-control select2" id="rem_'.($i+1).'" name = "remedios-edit[]">';
         }elseif($pedidos[$i]['status'] == 2){
                  $result.= '<tr><td style="width: 90%"><select class="form-control select2" id="rem_'.($i+1).'" name = "remedios-edit[]" disabled>';
         }
   		for($j = 0; $j < count($remedios); $j++){

   			if($pedidos[$i][id] == $remedios[$j][id]){
               if($pedidos[$i][status] == 1){
   				     $result.= '<option value = "'.$remedios[$j]['id'].'" selected>'.$remedios[$j]['nome'].' </option>';
               }elseif($pedidos[$i][status] == 2){
                     $result.= '<option value = "'.$remedios[$j]['id'].'" selected>'.$remedios[$j]['nome'].' - entregue em '.dateEmMySql($pedidos[$i][dt_final]).'</option>';
               }
   			}else{
   				$result.= '<option value = "'.$remedios[$j]['id'].'">'.$remedios[$j]['nome'].' </option>';
   			}
   		}
         if($pedidos[$i]['status'] == 1){
   		    $result.= '</select></td><td style="width: 100px"><input type="text" value = "'.$pedidos[$i]['quantidade'].'" id="qntd_'.($i+1).'" class="form-control" name="quantidade-edit[]"></td><td><input type="checkbox" id="'.($i+1)."|".$pedidos[$i]['id_pedido'].'" name="finalizado[]" onClick="confirmaPedido(this);"></td></td></tr>';
         }elseif($pedidos[$i]['status'] == 2){
               $result.= '</select></td><td style="width: 100px"><input type="text" value = "'.$pedidos[$i]['quantidade'].' " id="qntd_'.($i+1).'" class="form-control" name="quantidade-edit[]" disabled></td><td><input style="display:none" type="checkbox" id="'.($i+1)."|".$pedidos[$i]['id_pedido'].'" name="finalizado[]" onClick="confirmaPedido(this);"></td></td></tr>';
         }
   	}
	
	$result.= "</table>";
	echo $result;
?>