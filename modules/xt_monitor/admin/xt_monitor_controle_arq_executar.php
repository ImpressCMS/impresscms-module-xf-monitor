<?php
/*
* $Id: admin/xt_monitor_controle_arq_executar.php
* Module: xt_monitor
* Version: v1.0
* Release Date: 25 de maio  de 2007
* Author: Claudia Antonini Vitiello Callegari 
* Analista: Gilberto G. de Oliveira (Giba) 
*/

include("admin_header.php");
if(!$_POST['opt']=='conf'){
	xoops_confirm(array('opt' =>'conf'),xoops_getenv('PHP_SELF'),'Confirma executar verificação de arquivos  ?','');
	xoops_cp_footer();
	exit();
}	
// executa verificação
include XOOPS_ROOT_PATH."/modules/xt_monitor/includes/xt_monitor_veriarq.php";

xoops_cp_footer();
?>