<?php
/*
* $Id: admin/xt_monitor_controle_arq_param.php
* Module: xt_monitor
* Version: v1.0
* Release Date: 25 de maio de 2007
* Author: Claudia Antonini Vitiello Callegari 
* Analista: Gilberto G. de Oliveira (Giba) 
*/

include("admin_header.php");

include_once XOOPS_ROOT_PATH."/modules/xt_monitor/includes/xt_monitor_param.php";

//   Configurações individuais
 $token=true;
 // fim 

$op = 'editar';
if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];
$classe = new xt_monitor_param();
//$token=true;

switch($op){
	case 'salvar' :  // salvar alteração ou inclusão 
	if ($token and !$GLOBALS['xoopsSecurity']->check()) {
		redirect_header(xoops_getenv('PHP_SELF')."?op=listar", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
	}
	
	if($_POST){
		if(isset($_POST['par_10_id'])){
			$par_10_id = intval($_POST['par_10_id']);
			$classe->load($par_10_id);
		}else {
//			$par_10_id=0;		
			break;
		}

		$mat_post_vars=array('par_10_id','par_30_ext','par_33_pastas_off','par_30_emails','par_30_dirpath','par_11_gerarq','par_30_nomearq');
		// pegar variaveis do $_POST 
		foreach ($mat_post_vars as $k => $v) {
   			${$v}= $_POST[$v];
		}
		
		// setar variáveis para gravar....
		foreach ($mat_post_vars as $k => $v) {
			$classe->setVar($v, ${$v});
		}
		
		$novoid=$classe->store();
		if(!$novoid){
			xoops_error('Erro na atualização dos dados' .$classe->getHtmlErrors() );
			// resgatar as variáveis, pois quando pegas do post pode ter \ (barra de escape inserida pelo browser)
			foreach ($classe->cleanVars as $k => $v) {
				${$k} = $v;
			}
			
			
			$form['titulo'] = "Alterar Parâmetros : $par_10_id";
			require  XOOPS_ROOT_PATH.'/modules/xt_monitor/includes/xt_monitor_param_form.php';
			$sform->display();
			break;
		}else{
			//redirect_header(xoops_getenv('PHP_SELF')."?op=listar", 4, "Registro Atualizado com sucesso!");
			xoops_result( "Parâmetros Atualizados com sucesso!");
			$op='listar';
		}
	}else{
		die("Acesso Negado.");
	}
	
	
	break;	

	case 'editar':
	
	$par_10_id = 1;
	if (!isset($par_10_id) || empty($par_10_id)) {
		die("Acesso Não permitido !");
	}
	$classe->load($par_10_id);
	if ($classe->getVar("par_10_id") == "") {
		//xoops_error('Registro não encontrado');
		//break;
		// incluir registro
		$classe->setVar('par_30_ext','php;inc');
		$classe->setVar('par_33_pastas_off',XOOPS_ROOT_PATH."/cache/;   ".XOOPS_ROOT_PATH.'/templates_c/');
		$classe->setVar('par_30_emails',$xoopsConfig['adminmail']);
		$classe->setVar('par_30_ext','php;inc');
		$classe->setVar('par_11_gerarq',0);
		$classe->setVar('par_30_nomearq','.htaccess');
	}
	$classant=$classe;
	 foreach ($classe->vars as $k => $v) {
		${$k}=$classe->getVar($k);	 	
	 }	
	 // converter os campos tipo data 
	 //** trocar conv_data2_3  para  convdata após monta-la
	 
	 $par_10_dtultiemail= date('Y-m-d',$par_10_dtultiemail);
 
	 $form['titulo'] = "Alterar Parâmetros : $par_10_id";
	 $op='salvar';
	require  XOOPS_ROOT_PATH.'/modules/xt_monitor/includes/xt_monitor_param_form.php';
	 
	$sform->display();
	break;
		
}









xoops_cp_footer();
?>