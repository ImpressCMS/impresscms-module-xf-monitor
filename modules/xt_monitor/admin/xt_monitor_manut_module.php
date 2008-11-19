<?php
/*
* $Id: includes/xt_monitor_manut_module.php
* Module: xt_monitor
* Version: v1.0
* Release Date: 03 de abril de 2007
\* Author: Claudia Antonini Vitiello Callegari 
\* Analista: Gilberto G. de Oliveira (Giba) 
*/

include("admin_header.php");

// ou include_once('header.php')  // do proprio módulo o qual conterá a chamada acima
include_once XOOPS_ROOT_PATH."/modules/xt_monitor/includes/xt_monitor_module.php";
include_once XOOPS_ROOT_PATH."/modules/xt_monitor/includes/class_xformgrid.php";
require_once XOOPS_ROOT_PATH.'/modules/xt_monitor/includes/functions.php';
//   Configurações individuais

$token=true;
 // fim 

$op = 'listar';
if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];
$classe = new xt_monitor_module();
//$token=true;
$classxform= new xformgrid($classe->tabela,$classe->id);
$array_serialize=array();
$array_serialize['md_33_tabelas']=1; // indica que o valor do $_POST é um array e deve ser serializado antes de gravar no banco
$classxform->array_serialize=$array_serialize;

switch($op){

	case 'salvar' :  // salvar alteração ou inclusão 
	if ($token and !$GLOBALS['xoopsSecurity']->check()) {
		redirect_header(xoops_getenv('PHP_SELF')."?op=listar", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
	}
/*	
		echo "<pre>";
		var_dump($_POST);
		echo "</pre>";
		//break;
*/

		if($classxform->atualiza_table(null,1)){
			xoops_result('Registros atualizados com sucesso ');			
		}else{
			xoops_error($classxform->getHtmlErrors());			
		}
		
		break;
		
	default:
	echo "<h5>Módulos para monitorar alterações no Banco de Dados </h5>";
	
	$module_handler =& xoops_gethandler('module');
		

	$criterio=get_criterio_mid();
	
	$modules =& $module_handler->getList($criterio ,1);

	$array_chaves=array();
	foreach($modules as $key => $value){
		$array_chaves[]=$key;
	}
	
	$array_campos=array();

	$array_campos[0]['name']='md_11_include';
	$array_campos[0]['type']='radio';
	$array_campos[0]['label']='Monitorar';
	$array_campos[0]['options']=array(0 => 'não',1 => 'sim');
	
	$array_campos[1]['label']='Tabelas p/ monitorar';
	$array_campos[1]['name']='md_33_tabelas';
	$array_campos[1]['type']='checkbox';
	$array_campos[1]['options']='$options=xt_monitor_pegatab($value);';
	$array_campos[1]['options_eval']=1; // indica que para resgatar options é necessário aplicar eval 
	$array_campos[1]['button_exibe']=1; // indica quer ocultar o conteúdo e inserir botão para clicar e visualizar
	
	$array_values=$classxform->pega_valores($array_campos);

	$classxform->array_campos=$array_campos;
	$classxform->array_chaves=$array_chaves;
	$classxform->array_values=$array_values;
	$classxform->widhform='95%';
	$classxform->op='salvar' ;
	$classxform->exibe_id='Módulo';

	$classxform->exibe_form();
	
}


xoops_cp_footer();

/**
 * para ser usada com eval, deve retornar array com lista de tabelas do módulo
 * baseada no xoops_version
 * @param unknown_type $dirname
 * @return unknown
 */
function xt_monitor_pegatab($dirname=''){
	global $xoopsConfig;
	if (empty($dirname) ){
		return array();
	}
	if($dirname=='system'){
		// tabelas do system não estão no xoops_version
		// peguei as tabelas reservadas do xoops definidas no arquivo: 
		// ../modules/system/admin/modulesadmin/modulesadmin.php  function xoops_module_install($dirname)....
		$array_system= array('avatar', 'avatar_users_link', 'block_module_link', 'xoopscomments', 'config', 'configcategory', 'configoption', 'image', 'imagebody', 'imagecategory', 'imgset', 'imgset_tplset_link', 'imgsetimg', 'groups','groups_users_link','group_permission', 'online', 'bannerclient', 'banner', 'bannerfinish', 'priv_msgs', 'ranks', 'session', 'smiles', 'users', 'newblocks', 'modules', 'tplfile', 'tplset', 'tplsource', 'xoopsnotifications', 'banner', 'bannerclient', 'bannerfinish');
		$array_system2=array();
		foreach($array_system as $value) {
			$array_system2[$value]=$value."<br>";
		}
		return $array_system2; 
		 
	}
	
	$tables_fora=array(_MI_XT_MONITOR_DIRNAME."_hf_hash_files",
					   _MI_XT_MONITOR_DIRNAME."_qry_querys");

	
	$retorno=array();
	$arqversion=XOOPS_ROOT_PATH."/modules/$dirname/xoops_version.php";
	if(file_exists($arqversion)){
        if (file_exists(XOOPS_ROOT_PATH.'/modules/'.$dirname.'/language/'.$xoopsConfig['language'].'/modinfo.php')) {
            include_once XOOPS_ROOT_PATH.'/modules/'.$dirname.'/language/'.$xoopsConfig['language'].'/modinfo.php';
        } elseif (file_exists(XOOPS_ROOT_PATH.'/modules/'.$dirname.'/language/english/modinfo.php')) {
            include_once XOOPS_ROOT_PATH.'/modules/'.$dirname.'/language/english/modinfo.php';
        }
				
		include $arqversion;
		foreach($modversion['tables'] as $key =>$value  ){
			if(! in_array($value,$tables_fora)){
			   $retorno[$value]=$value."<br>";	
			}
		}
		return $retorno;
	}
	
}


?>