<?php   
/*
* $Id: admin/xt_monitor_consulta_form.php
* Module: xt_monitor
* Version: v1.0
* Release Date: 20/04/2007
\* Author: Claudia Antonini Vitiello Callegari 
\* Analista: Gilberto G. de Oliveira (Giba)
*
	Usado  como include   em xt_monitor_consulta.php   e  xt_monitor_limpaqry.php
*/  
     $sform = new XoopsThemeForm($tema_form, "storyform", xoops_getenv('PHP_SELF'),$metodo);

      // período
      $periodo_tray = new XoopsFormElementTray(_AM_XT_MONITOR_PERIODO, _AM_XT_MONITOR_ATE);
      $dt1_text = new XoopsFormText(_AM_XT_MONITOR_INICIO, "dt1", 10, 10, "" )  ;
      $dt2_text = new XoopsFormText(_AM_XT_MONITOR_FIM, "dt2", 10, 10, "" );
      $periodo_tray->addElement($dt1_text);
      $periodo_tray->addElement($dt2_text);
      $sform->addElement($periodo_tray);

		// selecionar usuario do xoops
		$select_uid=new XoopsFormSelectUser(_USERNAME,'uid',true);
		$sform->addElement($select_uid);
		
		// ip de origem o user 
		$sform->addElement(new XoopsFormText('Ip origem <br>Pode informar so inicial:','ip_origem',15,15,''));
		
		// modulo
		$sform->addElement(new XoopsFormText('Módulo (dirname) :','module',30,30,''));
		
		// tabela
		$sform->addElement(new XoopsFormText('Tabela:<br>(Sem prefixo)','tabela',30,30,''));
		
		// script
		$sform->addElement(new XoopsFormText('Script php :','script',30,30,''));
	
		// query sql
		$sform->addElement(new XoopsFormText('Sql (query):','sql_query',50,50,''));
		
		// query observacao
		$sform->addElement(new XoopsFormText('Observação:','observacao',50,50,''));
		
		// com erros ou sem erros
		$sform->addElement(new XoopsFormRadioYN('Com erro ?','com_erro'));

?>