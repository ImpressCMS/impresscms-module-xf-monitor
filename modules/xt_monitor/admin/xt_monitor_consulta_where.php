<?php

require_once XOOPS_ROOT_PATH.'/modules/xt_monitor/includes/functions.php';

	if(!empty($dt1) or !empty($dt2)   ) {
		$dt1_conv=conv_data($dt1);
		if(!$dt1_conv) {
			error_sai(" Data inválida : $dt1 ");
			break;
		}
		$dt2_conv=conv_data($dt2,23,59);
		if(!$dt2_conv) {
			error_sai(" Data inválida : $dt2 ");
			break;
		}
		if($dt2_conv<$dt1_conv) {
			//xoops_error(" Período inválido $dt1 - $dt2 ");
			error_sai(" Período inválido $dt1 - $dt2 ");
			break;
		}
	}
	
	
	if (!empty($dt1_conv) ) {
		$sql.=" and qry_10_dt >=$dt1_conv  ";
	}
	if (!empty($dt2_conv)) {
		$sql.=" and  qry_10_dt<=$dt2_conv";
	}
	
	if(!empty($uid)){
		$sql.=" and  qry_14_uid=$uid";
	}
	
	if(!empty($ip_origem)){
		$sql.=" and  qry_30_ip  like '$ip_origem%'";		
	}
	
	if(!empty($module)){
		$sql.=" and  qry_30_module='$module'";		
	}

	if(!empty($tabela)){
		$sql.=" and  qry_30_table='$tabela'";		
	}

	if(!empty($script)){
		$sql.=" and  qry_30_script like '%$script%'";		
	}

	 if(!empty($sql_query)) {
           $sql.=" and qry_33_sql like '%$sql_query%'" ;
     }
      
	 if(!empty($observacao)) {
           $sql.=" and qry_33_obs like '%$observacao%'" ;
     }
	
	if(($com_erro)){
		$sql.=" and qry_14_error>0";
	}
	
// 	colocar filtro para pegar somente modulos que o user é administrador.
	$module_handler =& xoops_gethandler('module');
	$criterio=get_criterio_mid();
	if(!is_null($criterio)){
		$modules =& $module_handler->getList($criterio ,1);
		$array_chaves=array();
		foreach($modules as $key => $value){
			$array_chaves[]=$key;
		}

		
		if(count($array_chaves>0)){
			$sql.=' and qry_30_module in ('.implode(',',array_map('entre_aspas',$array_chaves)).')';
		}else{
			// indica que não pode visualizar nehum modulo
			$sql.='  and  false '	;
		}
		
		
	}

	
	
	
?>	