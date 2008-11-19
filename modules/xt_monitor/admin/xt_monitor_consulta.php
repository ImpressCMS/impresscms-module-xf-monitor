<?php 
/*
* $Id: admin/xt_monitor_consulta.php
* Module: xt_monitor
* Version: v1.0
* Release Date: 12/04/2007
\* Author: Claudia Antonini Vitiello Callegari
\* Analista: Gilberto G. de Oliveira (Giba)
*
*/

$temapagina='  ';
if($_GET['saida']<>'T') {
	ob_start();
}

include("admin_header.php");
include_once(XOOPS_ROOT_PATH.'/modules/xt_monitor/includes/formcheckbox_id.php');

// listar variaveis a serem resgatadas via get ou post
// exemplo: $mat_get_vars=array('mes','dia1','dia2','loja','saida');
$mat_get_vars=array('opt','saida','dt1','dt2','uid','ip_origem','module','tabela','script','sql_query','observacao','com_erro','campos_sel','orientation','paper','men_p_page');
foreach ($mat_get_vars as $k => $v) {
	${$v}= $_GET[$v];
}

//    $mat_post_vars=array();
//    foreach ($mat_post_vars as $k => $v) {
//       ${$v}= $HTTP_POST_VARS[$v];
//    }

//  Definir matriz com todos campos possíveis


$array_campos=array();
$array_campos_extra_td=array();
$array_campos_larg=array();

$array_campos['($cat_data["qry_10_id"])']='Id';
$array_campos_larg['($cat_data["qry_10_id"])']=8;

$array_campos['(date("d/m/Y H:i:s",$cat_data["qry_10_dt"]))']='Data';
$array_campos_larg['(date("d/m/Y H:i:s",$cat_data["qry_10_dt"]))']=20;

$array_campos['($cat_data["uname"])']='User';
$array_campos_larg['($cat_data["uname"])']=20;

$array_campos['($cat_data["qry_30_ip"])']='IP Origem';
$array_campos_larg['($cat_data["qry_30_ip"])']=25;

$array_campos['$cat_data["qry_30_script"]']='Script (php)';
$array_campos_larg['$cat_data["qry_30_script"]']=30;

$array_campos['($cat_data["qry_33_sql"])']="Sql query";
$array_campos_larg['($cat_data["qry_33_sql"])']=130;
$array_campos_textarea['($cat_data["qry_33_sql"])']='5#50# style="border:1"'; // 4 linhas e 50 colunas

$array_campos['($cat_data["qry_33_obs"])']="Observação";
$array_campos_larg['($cat_data["qry_33_obs"])']=80;

$array_campos['($cat_data["qry_14_error"])']="Erro";
$array_campos_larg['($cat_data["qry_14_error"])']=10;

$array_campos['($cat_data["qry_33_menerro"])']="Mensagem de Erro";
$array_campos_larg['($cat_data["qry_33_menerro"])']=35;


$array_campos['($cat_data["qry_30_module"])']="Módulo";
$array_campos_larg['($cat_data["qry_30_module"])']=30;

$array_campos['($cat_data["qry_30_table"])']="Tabela";
$array_campos_larg['($cat_data["qry_30_table"])']=30;

$array_campos['($cat_data["qry_30_referer"])']="Referência";
$array_campos_larg['($cat_data["qry_30_referer"])']=30;

$array_campos['($cat_data["qry_30_user_agent"])']="Browser do Usuário";
$array_campos_larg['($cat_data["qry_30_user_agent"])']=30;




switch($opt) {
	case 'enviar2':
	//  exemplo de subconsulta da consulta

	break;

	case 'enviar1':

	require_once XOOPS_ROOT_PATH.'/modules/xt_monitor/includes/functions.php';

	$sql="select qry.*, users.uname from ".$xoopsDB->prefix('xt_monitor_qry_querys').'  as qry ';
	$sql.=" left join ".$xoopsDB->prefix('users').' as users on users.uid=qry.qry_14_uid  ';
	$sql.=' where 1=1';

	include 'xt_monitor_consulta_where.php';
	$sql.=" order by qry_10_dt desc ";

	$linkar='';
	$tema=' Consulta de alterações em tabelas  ';

	$i=0;
	if(!empty($campos_sel)) {
		foreach ($campos_sel as $nome_campo) {
			$nome_campo=stripslashes($nome_campo);
			$mat_campos[$i]['titulo']=$array_campos[$nome_campo];
			$mat_campos[$i]['campo']=$nome_campo;
			$mat_campos[$i]['larg'] = $array_campos_larg[$nome_campo];
			$mat_campos[$i]['textarea'] =$array_campos_textarea[$nome_campo];
			$i++ ;
		}
	}


	$col_grupo1=99;  // indica que não haverá subgrupo
	$totcol_imp=0;
	$tot_grupo1=0;
	$salta_grupo1=0;

	$sintetico=0;
	$tiporet=$saida;
	$setfont=array("family"=>"arial", "style"=>"", "size"=>"9");
	$multicell=1;

	$width_table='100%';
	$men_p_page=$men_p_page;
	$orientation=$orientation;
	$paper=$paper;

	browserx($sql,$mat_campos,$tema,$linkar,$tiporet,$sintetico,$col_grupo1,$totcol_imp,$tot_grupo1,$salta_grupo1,
	$setfont ,$multicell,$totcol_cab, $width_table,$men_p_page,$orientation,$paper) ;


	break;


	default:
	// solicitar dados
	$tema_form=_AM_XT_MONITOR_TEMACONSULTA;
	$metodo='get';
	include 'xt_monitor_consulta_form.php';

	// exibir os campos para o user marcar quais desejas listar...

	$objcampos= new XoopsFormCheckBoxCla(_AM_XT_MONITOR_SELCAMPOS,'campos_sel','($cat_data["qry_10_id"])');
	$objcampos->addOptionArray($array_campos);
	$sform->addElement($objcampos);

	for($i=0;$i<count($array_campos);$i++){
		$array_options[]="'campos_sel[][$i]'";
	}
	$option_ids_str = implode(', ', $array_options);

	$objmarca=new XoopsFormCheckBox(_AM_XT_MONITOR_MARCALL,'selall');
	$objmarca->setExtra("onclick=\"var optionids = new Array(".$option_ids_str."); xoopsCheckAllElements(optionids, 'idselall');\" ");

	$objmarca->setExtra("id='idselall' ");
	$objmarca->addOption('');
	$sform->addElement($objmarca);

	// consulta em tela ou impressão
	$radiosaida= new XoopsFormRadio(_AM_XT_MONITOR_LOCSAI, 'saida','T');
	$radiosaida->addOption("T",_AM_XT_MONITOR_TELA);
	$radiosaida->addOption("P",_AM_XT_MONITOR_PDF );
	$radiosaida->addOption("C",_AM_XT_MONITOR_CSV  );
	$radiosaida->addOption("X",_AM_XT_MONITOR_XLS  );
	$sform->addElement($radiosaida);


	//orientação do papel e formato do papel
	$radiosorienta= new XoopsFormRadio(_AM_XT_MONITOR_ORIENT, 'orientation','P');
	$radiosorienta->addOption("P",_AM_XT_MONITOR_VERT );
	$radiosorienta->addOption("L", _AM_XT_MONITOR_HORIZ);
	$sform->addElement($radiosorienta);

	$sel_formato = new XoopsFormSelect(_AM_XT_MONITOR_FORMPAPER, "paper",'A4');
	$sel_formato->addOptionArray(array('A3' =>'A3','A4' =>'A4','A5' =>'A5' ,'Letter' =>'Letter', 'Legal' => 'Legal')  );
	$sform->addElement($sel_formato);

	$sform->addElement(new XoopsFormText(_AM_XT_MONITOR_REGPPAGE,'men_p_page',4,4,30));

	$sform->addElement(new XoopsFormHidden("opt",'enviar1'));
	$sform->addElement( new XoopsFormButton('', "post", _SUBMIT, "submit") );
	$sform->display();
}
//echo ($opt!="") ? "<p align=\"center\"><a href='".$_SERVER['PHP_SELF']."'>oltar</a></p>" : "";
if($_GET['saida']=='T' or !isset($_GET['saida'])) {
	//include("footer.php");
	xoops_cp_footer();
}

?>