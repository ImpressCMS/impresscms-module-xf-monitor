<?php
/*
* $Id: includes/xt_monitor_class_querys.php
* Module: xt_monitor
* Version: v1.0
* Release Date: 04 de abril  2007
\* Author: Claudia Antonini Vitiello Callegari 
\* Analista: Gilberto G. de Oliveira (Giba) 
*/

include_once XOOPS_ROOT_PATH."/modules/xt_monitor/includes/xt_monitor.class.php";
class xt_monitor_querys   extends xt_monitor {
	function xt_monitor_querys($id=null){
		$this->db =& Database::getInstance();
		$this->tabela = $this->db->prefix("xt_monitor_qry_querys");
		$this->id = "qry_10_id";
		$this->id_is_string=0;
		
		$this->initVar("qry_10_id", XOBJ_DTYPE_INT, null, false);
		$this->initVar("qry_14_uid", XOBJ_DTYPE_INT, null, false);
		$this->initVar("qry_30_ip", XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("qry_30_script", XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("qry_33_sql", XOBJ_DTYPE_TXTAREA, null, false);
		$this->initVar("qry_33_obs", XOBJ_DTYPE_TXTAREA, null, false);
		$this->initVar("qry_14_error", XOBJ_DTYPE_INT, null, false);
		$this->initVar("qry_33_menerro", XOBJ_DTYPE_TXTAREA, null, false);		
		$this->initVar("qry_30_module", XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("qry_30_table", XOBJ_DTYPE_TXTBOX, null, false);
		
		if ( !empty($id) ) {
			if ( is_array($id) ) {
				$this->assignVars($id);
			} else {
				$this->load($id);
			}
		}
	}


    function validar($opt='') {
      global $men_erro;
      

      return true;
   }  // fecha function validar 

}

?>