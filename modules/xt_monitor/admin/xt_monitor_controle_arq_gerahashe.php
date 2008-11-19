<?php
/*
* $Id: admin/xt_monitor_controle_arq_gerahashe.php
* Module: xt_monitor
* Version: v1.0
* Release Date: 25 de maio  de 2007
\* Author: Claudia Antonini Vitiello Callegari 
\* Analista: Gilberto G. de Oliveira (Giba) 
*/
include("admin_header.php");


if(!$_POST['opt']=='conf'){
	xoops_confirm(array('opt' =>'conf'),xoops_getenv('PHP_SELF'),'Confirma gerar os hashes dos arquivos ?','');
	xoops_cp_footer();
	exit();
}	
set_time_limit(0);
require_once XOOPS_ROOT_PATH."/modules/xt_monitor/includes/xt_monitor_param.php";
require_once(XOOPS_ROOT_PATH."/modules/xt_monitor/includes/xt_monitor.class_hashes.php");

$objhashe= new xt_monitor_hashes();
$objparam= new xt_monitor_param(1);

if($objparam->getVar("par_10_id") == "") {
	xoops_error(_AM_XT_MONITOR_PARAM_NOT_CAD);
	xoops_cp_footer();	
	exit();
}

// $objparam->getVar('par_30_ext');

$objhashe->ext_valid=explode(';',$objparam->getVar('par_30_ext')) ;   // array com extensões de arquivos válidas para calcular
$objhashe->pastas_off=explode(';',$objparam->getVar('par_33_pastas_off'))  ;  // array com nomes completos de pastas que não precisam ser verificadas
$dirpath=$objparam->getVar('par_30_dirpath');
if(!empty($dirpath)){
	$objhashe->dirpath=$dirpath;	  // pasta inicial para gerar calculo	
}
$objhashe->email_notif=$objparam->getVar('par_30_emails') ;   // lista de emails a serem notificados com resultado, qdo. houver erros
	

$objhashe->calc_files();
$objhashe->salva_hashes();
echo $objhashe->getHtmlMensagens();


xoops_cp_footer();
?>