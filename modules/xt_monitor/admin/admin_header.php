<?php
/*
* $Id: admin/admin_header.php
* Module: xt_monitor
* Version: v1.0
* Release Date:  03/04/2007
\* Author: Claudia Antonini Vitiello Callegari 
\* Analista: Gilberto G. de Oliveira (Giba)
*/

//include_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/include/functions.php";

include_once ("../../../mainfile.php");
include_once XOOPS_ROOT_PATH.'/include/cp_header.php';
$admin_mydirname = basename( dirname( dirname( __FILE__ ) ) ) ; // Generalizando o nome do diretório do módulo. - GibaPhp

include XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
############################################################################################################
## Função para tratamento de menu especial                                                                ##
############################################################################################################

require_once(XOOPS_ROOT_PATH."/modules/xt_monitor/includes/func_menus.php");
xoops_cp_header();
define('_CARREGOU_HEADER',1);

########################by rplima - submenu#########################################
$xmenu = (isset($_GET['xmenu']))?$_GET['xmenu']:'';
$xsubmenu = (isset($_GET['xsubmenu']))?$_GET['xsubmenu']:'';
adminMenu($xmenu,$xsubmenu,$admin_mydirname);
########################by rplima - submenu#########################################

if(!defined('_XT_MONITOR_INC')){
	xoops_error(_AM_XT_MONITOR_NOTCONF);
	
}else{
	//echo 'Configurado Corretamente';
	
}

// CONSTANTE PARA INDICAR SE PERMITIRÁ LIMPAR REGISTROS OU NÃO 
define('XT_MONITOR_LIMPAR',1); // 0- NÃO  1-  SIM 


?>