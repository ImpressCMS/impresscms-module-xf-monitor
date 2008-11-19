<?php
/*
* $Id: admin/menu.php
* Module: xt_monitor
* Version: v1.0
* Release Date: 03 de abril de 2007
\* Author: Claudia Antonini Vitiello Callegari 
\* Analista: Gilberto G. de Oliveira (Giba) 21 Março de 2004.
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

$col=0;
$adminmenu[$col]['title'] = _MI_XT_MONITOR_HELP;
$adminmenu[$col]['link'] = "admin/xt_monitor_help.php";
$col++;
//$adminmenu[$col]['title'] = 'Parâmetros ';
//$adminmenu[$col]['link'] = "admin/xt_monitor_manut_param.php";
//$col++;

$adminmenu[$col]['title'] = _MI_XT_MONITOR_CONSULTA;
$adminmenu[$col]['link'] = "admin/xt_monitor_consulta.php";
$col++;
$adminmenu[$col]['title'] = _MI_XT_MONITOR_CONFMOD;
$adminmenu[$col]['link'] = "admin/xt_monitor_manut_module.php";
$col++;

$adminmenu[$col]['title'] = _MI_XT_MONITOR_LIMPREG;
$adminmenu[$col]['link'] = "admin/xt_monitor_limpaqry.php";
$col++;

$adminmenu[$col]['title'] = _MI_XT_MONITOR_VERERR;
$adminmenu[$col]['link'] = "admin/xt_monitor_vererros.php";
$col++;

$adminmenu[$col]['title'] = _MI_XT_MONITOR_CONTARQ;
$adminmenu[$col]['link'] = "admin/xt_monitor_controle_arq_help.php";

$adminmenu[$col]['sub'][0]['title'] = _MI_XT_MONITOR_HELP;
$adminmenu[$col]['sub'][0]['link']  = "admin/xt_monitor_controle_arq_help.php";

$adminmenu[$col]['sub'][1]['title'] = _MI_XT_MONITOR_PARAM;
$adminmenu[$col]['sub'][1]['link']  = "admin/xt_monitor_controle_arq_param.php";

$adminmenu[$col]['sub'][2]['title'] = _MI_XT_MONITOR_GERAHASH;
$adminmenu[$col]['sub'][2]['link']  = "admin/xt_monitor_controle_arq_gerahashe.php";

$adminmenu[$col]['sub'][3]['title'] = _MI_XT_MONITOR_EXECVERIF;
$adminmenu[$col]['sub'][3]['link']  = "admin/xt_monitor_controle_arq_executar.php";
?>