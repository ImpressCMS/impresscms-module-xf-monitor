<?php 
/*
* $Id: admin/xt_monitor_limpaqry.php
* Module: xt_monitor
* Version: v1.0
* Release Date: 20/04/2007
\* Author: Claudia Antonini Vitiello Callegari
\* Analista: Gilberto G. de Oliveira (Giba)
*
*/

$temapagina='  ';

include("admin_header.php");

if(XT_MONITOR_LIMPAR != 1){
	echo "<h2> Opção  desabilitada </h2>";
	xoops_cp_footer();		
	return;
}


// listar variaveis a serem resgatadas via get ou post
// exemplo: $mat_get_vars=array('mes','dia1','dia2','loja','saida');
$mat_post_vars=array('opt','dt1','dt2','uid','ip_origem','module','tabela','script','sql_query','observacao','com_erro');
foreach ($mat_post_vars as $k => $v) {
	${$v}= $_POST[$v];
}


switch($opt) {
	case 'enviar2':
	//  exemplo de subconsulta da consulta

	break;

	case 'enviar1':

	require_once XOOPS_ROOT_PATH.'/modules/xt_monitor/includes/functions.php';


	
	$sql="delete from ".$xoopsDB->prefix('xt_monitor_qry_querys');
	$sql.=' where 1=1';

	require  'xt_monitor_consulta_where.php';


	$result=$xoopsDB->queryf($sql);
	if(!$result){
		xoops_error('Erro na sql '.$sql .' - '.$xoopsDB->error());
	}else{
		xoops_result('Registros Excluídos :'.$xoopsDB->getAffectedRows());
	}

	break;


	default:
	// solicitar dados
	xoops_error('C U I D A D O !!!  Está opção  excluirá  registros  ');
	$tema_form='EXCLUIR Registros monitorados -  Informe dados para filtrar ';
	$metodo='post';
	include 'xt_monitor_consulta_form.php';

	$sform->addElement(new XoopsFormHidden("opt",'enviar1'));
	$sform->setExtra('onsubmit=\'return confirma("Tem certeza que deseja excluir ?")\';');
	$objsubmit=new XoopsFormButton('', "post", "Limpar", "submit");
	$sform->addElement($objsubmit);
	$sform->display();


}

xoops_cp_footer();
?>
<script language='javascript'>
function confirma(men){
	if(confirm(men)){
		return true;		
	}else{
		return false;
	}

}


</script>