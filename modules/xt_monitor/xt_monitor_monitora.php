<?php
/*
* $Id: xt_monitor_monitora.php
* Module: xt_monitor
* Version: v1.0
* Release Date: 04 de abril de 2007
* Author: Claudia Antonini Vitiello Callegari
* Analista: Gilberto Galdino  (Giba)
*/
// Objetivo: Monitora sqls que alteram o banco de dados .Deve ser chamado com  include  e não include_once
// Local da chamada: <XOOPS_ROOT_PATH>/class/logger.php  no final da function addQuery(...)
// Como fazer a chamada:  
//     Se não estiver usando a versão XOOPS  do XT:  
//			include(XOOPS_ROOT_PATH.'/modules/xt_monitor/xt_monitor_monitora.php');	
//
//	   Se estiver usando versão xoops do XT, não é necessário fazer nada, pois ele ja 
//     será chamado pelo  xt_logger.php,  incluso no mesmo local, o qual usará método de plugins
//    ( ...modules/xt_monitor/plugins/xt_monitor_pluginlogger.php)
//  
// Devido ser chamado de dentro da function addQuery , ja está definido as variáveis:
//$sql (sql executada),$error (mensagem de erro,se ocorrer), $errno (nro. do erro se ocorrer)
// deve ser usado outra classe para acesso ao Banco de Dados, para não dar conflito  e entrar em loop
//echo  "<br>  estou no monitor_monitora ";

if(!defined('_XT_MONITOR_INC')){
	define('_XT_MONITOR_INC','1');
}
// carregar arquivos de tradução
//global $xoopsConfig;
//if ( file_exists(XOOPS_ROOT_PATH."/modules/xt_monitor/language/".$xoopsConfig['language']."/monitora.php") ) {
//		include_once XOOPS_ROOT_PATH."/modules/xt_monitor/language/".$xoopsConfig['language']."/monitora.php";
		//echo XOOPS_ROOT_PATH."/modules/xt_monitor/language/".$xoopsConfig['language']."/monitora.php"."<br />";
//} else {
//	if ( file_exists(XOOPS_ROOT_PATH."/modules/xt_monitor/language/english/monitora.php") ) {
//		include_once XOOPS_ROOT_PATH."/modules/xt_monitor/language/english/monitora.php";
//	}
//}


$sql2=strtoupper(trim($sql));
$oper = substr($sql2,0,6);
if(ereg('^SELECT',$sql2)){
	return;	
}

global $xoopsUser,$xoopsDB,$xoopsModule;
//error_reporting(E_ALL);

if (!(isset($xoopsModule) && is_object($xoopsModule))) {
	return;
}

// verificar na tabela  md_module   se o modulo em questão é para ser monitorado
// ver se md_33_tabelas não estiver vazio, dar unserialize  e checar se a tabela
// que será atualizada estará no array

//  não necessário, será registrado todos debugs no extra do debug e se ativado irá mostrar...
//$xt_monitor_debugar= ($xoopsConfig['debug_mode']==2 ? 1 :0 ) ; 

$xt_monitor_dirname=$xoopsModule->getVar('dirname');
$_SESSION['xt_monitor']=null;
$xt_monitor_arqerror=XOOPS_ROOT_PATH.'/modules/xt_monitor/uploads/xt_monitor_erros.php'; // extensão php para não ser visualizado pelo browser.

include_once XOOPS_ROOT_PATH.'/modules/xt_monitor/grvlog.php';

if(!isset($_SESSION['xt_monitor'])){
	// montar matriz....
	include_once XOOPS_ROOT_PATH.'/modules/xt_monitor/includes/classdb.inc.php';
	$con_log = new bd3("mysql");
	$con_log->conecta(XOOPS_DB_NAME,XOOPS_DB_HOST,"",XOOPS_DB_USER,XOOPS_DB_PASS,1);

	$qry = new consulta3($con_log);
	$sqlmonitor=' select * from '.$xoopsDB->prefix('xt_monitor_md_module').
	' where md_11_include=1 ';

	$qry->executa($sqlmonitor);
	if(!$qry->res ) {
		// erro na sqlmonitor, verificar como avisar
		xt_monitor_grvlog($xt_monitor_arqerror,'<?php '."\n".' '.date('d/m/y H:i:s').XT_MONITOR_ERRSQL. addslashes($sqlmonitor)." Error: ".mysql_error(). '?>') ;
		return;
	} else{
		if($qry->nrw<=0){
			$_SESSION['xt_monitor']='';		
			 echo XT_MONITOR_NOTTABLE;
			return;
		}else {
			for($i=0;$i<$qry->nrw;$i++) {
				// converter os nomes das tabelas para maiúscula, para serem encontradas
				$_SESSION['xt_monitor'][$qry->data["md_30_dirname"]]["md_33_tabelas"]=array_map('strtoupper', unserialize($qry->data['md_33_tabelas']));
				$qry->proximo();
			}
		}
	}
}

if(!isset($_SESSION['xt_monitor'][$xt_monitor_dirname])){
	$xoopsDB->logger->addExtra('Debug xt_monitor ',XT_MONITOR_MODULONOT .$xt_monitor_dirname );
	return;  // não precisa monitorar esse módulo
}


	$file = "";
	switch($oper) {
		case "INSERT":
		$file = substr($sql2,12,strpos($sql,"VALUES")-12);
		$file = substr($file,0,strpos($file,"("));  // prevendo sql que não deu espaço após o insert
		$file=str_replace(strtoupper($xoopsDB->prefix()).'_','',$file);
		break;
		case "UPDATE":
		$array_sql2=explode(' ',$sql2);
		$file=$array_sql2[1];
		//$file = substr($sql2,7,strpos($sql,"SET")-7);
		$file=str_replace(strtoupper($xoopsDB->prefix()).'_','',$file);
		break;
		case "DELETE":
		$file = substr($sql2,12,strlen($sql));
		$file=str_replace(strtoupper($xoopsDB->prefix()).'_','',$file);
		break;
		default: 
		   if($oper<>"SELECT") 
		      ;//echo "<br> XT_MONITOR: operação  não prevista ".$oper;
		
	}

if(!empty($_SESSION['xt_monitor'][$xt_monitor_dirname]['md_33_tabelas'])){  // verificar qual tabela deve ser monitorada	
	if(!in_array($file,array_map('strtoupper',$_SESSION['xt_monitor'][$xt_monitor_dirname]["md_33_tabelas"] ))){
		$xoopsDB->logger->addExtra('Debug xt_monitor ',XT_MONITOR_TABLENOT.$file );
		return;
	}
}


	// registrar a sql
	include_once XOOPS_ROOT_PATH.'/modules/xt_monitor/includes/classdb.inc.php';
	$con_log = new bd3("mysql");
	$con_log->conecta(XOOPS_DB_NAME,XOOPS_DB_HOST,"",XOOPS_DB_USER,XOOPS_DB_PASS,1);
	
	$qry2 = new consulta3($con_log);
	if(!$errno  or  is_null($errno)) $errno=0;
//	if(!empty($_SESSION['xt_monitor_obs'])){
//		$xt_monitor_obs=addslashes($_SESSION['xt_monitor_obs']);
//		$_SESSION['xt_monitor_obs']='';		
//	}
	
	if(!empty($GLOBALS['xt_monitor_obs'])){
		$xt_monitor_obs=addslashes($GLOBALS['xt_monitor_obs']);
		$GLOBALS['xt_monitor_obs']='';		
	}
	
	// verificar se deve comparar objetos antes e depois para registrar alterações
	if( is_string(get_class($GLOBALS['xt_monitor_objantes'])) and  get_class($GLOBALS['xt_monitor_objantes']) == get_class($GLOBALS['xt_monitor_objdepois'])  ){
		// comparar os dois objetos e ver o quais propriedades alteraram
		$antes=get_object_vars($GLOBALS['xt_monitor_objantes']);
		$depois=get_object_vars($GLOBALS['xt_monitor_objdepois']);

		foreach($antes as $key =>$value){
			$vr_antes=($antes[$key]);
			$vr_depois=(($depois[$key]));
			if(get_magic_quotes_gpc()){ 
				$vr_depois=stripslashes($vr_depois); // normalmente o $vr_depois veio do $_POST OU $_GET
			}
			
			//echo 'veja antes ',var_dump($vr_antes);
			//echo "<br> veja depois ",var_dump($vr_depois);	
			
			
			if($vr_antes!=$vr_depois){
			 $xt_monitor_obs.="\n ".$key.XT_MONITOR_ALTEROU.($vr_antes).XT_MONITOR_PARA.($vr_depois);
			 $xt_monitor_obs=addslashes($xt_monitor_obs);
			}
		}
		$GLOBALS['xt_monitor_objantes']='';
		$GLOBALS['xt_monitor_objdepois']='';
		
	}
	

	$sqlquery=' insert into '.$xoopsDB->prefix('xt_monitor_qry_querys').
	' (qry_10_dt,qry_14_uid,qry_30_ip,qry_30_script,qry_33_sql,qry_33_obs,qry_14_error,qry_33_menerro,qry_30_module,qry_30_table,qry_30_referer,qry_30_user_agent)'.
	" values (".time().",".
	( !empty($xoopsUser) ? $xoopsUser->getVar('uid') :0 )     .",
	'".$_SERVER['REMOTE_ADDR']."',
	'".$_SERVER['REQUEST_URI']. "',
	'".addslashes($sql)."',
	'$xt_monitor_obs',
	$errno,
	'".addslashes($error)."',
	'".$xt_monitor_dirname."',
	'".$file."',  
	'".$_SERVER['HTTP_REFERER']."',  
	'".addslashes(getenv("HTTP_USER_AGENT"))."' ) ";


	$qry2->executa($sqlquery);
	if(!$qry2->res ) {
		xt_monitor_grvlog($xt_monitor_arqerror,'<?php '."\n".''.date('d/m/y H:i:s').XT_MONITOR_ERRSQL.addslashes($sqlquery)." Erro retornado: ".mysql_error(). '?>') ;
		return;
	}else{ 
		$xoopsDB->logger->addExtra('Debug xt_monitor ', XT_MONITOR_SQLGRV. " $xt_monitor_dirname - ".XT_MONITOR_TABLE .$file );
	}
?>