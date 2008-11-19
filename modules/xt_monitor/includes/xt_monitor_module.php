<?php
/*
* $Id: includes/xt_monitor_module.php
* Module: xt_monitor
* Version: v1.0
* Release Date: 27 de fevereiro de 2007
\* Author: Claudia Antonini Vitiello Callegari 
\* Analista: Gilberto G. de Oliveira (Giba) 
*/

include_once XOOPS_ROOT_PATH."/modules/xt_monitor/includes/xt_monitor.class.php";
class xt_monitor_module   extends xt_monitor {
	function xt_monitor_module($id=null){
		$this->db =& Database::getInstance();
		$this->tabela = $this->db->prefix("xt_monitor_md_module");
		$this->id = "md_30_dirname";
		$this->id_is_string=1;
		
		$this->initVar("md_30_dirname", XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("md_11_include", XOBJ_DTYPE_INT, null, false);
		$this->initVar("md_33_tabelas", XOBJ_DTYPE_TXTAREA, null, false);
		
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