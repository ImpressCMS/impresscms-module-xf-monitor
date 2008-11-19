<?php
/*
* $Id: include/xt_monitor_agenda.php
* Module: xt_monitor
* Version: v1.0
* Release Date: 29 de maio  de 2007
\* Author: Claudia Antonini Vitiello Callegari 
\* Analista: Gilberto G. de Oliveira (Giba) 
*/

//  Para ser executado a partir de agendador de tarefas
// Montar de forma que não precise autenticar o usuário, mas deverá autenticar o ip
// que solicitou a tarefa.

// $_SERVER["REMOTE_ADDR"]	127.0.0.1

//  Este script não deve ser colocado no diretório raiz do modulo ,caso contrário não será executado sem user logado


//    $xoopsOption['pagetype'] = "user";
    require_once('../../../mainfile.php');
    include_once( XOOPS_ROOT_PATH . "/header.php" );
	ob_end_flush();
    
    $config_handler =& xoops_gethandler('config');
    //$xoopsConfigUser =& $config_handler->getConfigsByCat(XOOPS_CONF_USER);

    // carregar arquivos de tradução
   	if ( file_exists(XOOPS_ROOT_PATH."/modules/xt_monitor/language/".$xoopsConfig['language']."/main.php") ) {
		include_once XOOPS_ROOT_PATH."/modules/xt_monitor/language/".$xoopsConfig['language']."/main.php";
	} else {
		if ( file_exists(XOOPS_ROOT_PATH."/modules/xt_monitor/language/english/main.php") ) {
			include_once XOOPS_ROOT_PATH."/modules/xt_monitor/language/english/main.php";
		}
	}

include XOOPS_ROOT_PATH."/modules/xt_monitor/includes/xt_monitor_veriarq.php";

//include(XOOPS_ROOT_PATH."/footer.php");

?>