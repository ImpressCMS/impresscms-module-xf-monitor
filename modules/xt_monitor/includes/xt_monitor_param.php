<?php
/*
* $Id: includes/xt_monitor_param.php
* Module: xt_monitor
* Version: v1.0
* Release Date: 27 de fevereiro de 2007
\* Author: Claudia Antonini Vitiello Callegari 
\* Analista: Gilberto G. de Oliveira (Giba) 
*/

include_once XOOPS_ROOT_PATH."/modules/xt_monitor/includes/xt_monitor.class.php";
class xt_monitor_param   extends xt_monitor {
	function xt_monitor_param($id=null){
		$this->db =& Database::getInstance();
		$this->tabela = $this->db->prefix("xt_monitor_par_param");
		$this->id = "par_10_id";
		$this->id_is_string=0;
		$this->initVar("par_10_id", XOBJ_DTYPE_INT, null, false);
		$this->initVar("par_30_ext", XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("par_33_pastas_off", XOBJ_DTYPE_TXTAREA, null, false);
		$this->initVar("par_30_emails", XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("par_30_dirpath", XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("par_11_gerarq", XOBJ_DTYPE_INT, 0, false);
		$this->initVar("par_30_nomearq", XOBJ_DTYPE_TXTBOX,'.htaccess', false);
		
		
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
      $nomearq=$this->getVar('par_30_nomearq');
      if($this->getVar('par_11_gerarq') and empty($nomearq)){
      	$this->setErrors('É necessário informar nome do arquivo');
         return false;
      }


      return true;
   }  // fecha function validar 

}

?>