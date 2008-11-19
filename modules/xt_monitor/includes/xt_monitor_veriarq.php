<?php
/*
* $Id: includes/xt_monitor_veriarq.php
* Module: xt_monitor
* Version: v1.0
* Release Date: 29 de maio  de 2007
\* Author: Claudia Antonini Vitiello Callegari 
\* Analista: Gilberto G. de Oliveira (Giba) 
*/

// script para ser usado com include em 2 scripts:
// admin/xt_monitor_controle_arq_executar.php
// e  include/xt_monitor_agenda.php  ( para ser agendado e executado automaticamente)


set_time_limit(0);
require_once XOOPS_ROOT_PATH."/modules/xt_monitor/includes/xt_monitor_param.php";
require_once(XOOPS_ROOT_PATH."/modules/xt_monitor/includes/xt_monitor.class_hashes.php");

$objhashe= new xt_monitor_hashes();
$objparam= new xt_monitor_param(1);

if($objparam->getVar("par_10_id") == "") {
	xoops_error(_AM_XT_MONITOR_PARAM_NOT_CAD);
	return;
}


$objhashe->ext_valid=explode(';',$objparam->getVar('par_30_ext')) ;   // array com extensões de arquivos válidas para calcular
$objhashe->pastas_off=explode(';',$objparam->getVar('par_33_pastas_off'))  ;  // array com nomes completos de pastas que não precisam ser verificadas


$objhashe->gerarq=$objparam->getVar('par_11_gerarq');
$objhashe->nomearq=$objparam->getVar('par_30_nomearq');

$dirpath=$objparam->getVar('par_30_dirpath');

if(!empty($dirpath)){
	$objhashe->dirpath=$dirpath;	  // pasta inicial para gerar calculo	
}
$objhashe->email_notif=$objparam->getVar('par_30_emails') ;   // lista de emails a serem notificados com resultado, qdo. houver erros
	
$objhashe->compara_hashes();
echo nl2br($objhashe->getHtmlMensagens());

?>