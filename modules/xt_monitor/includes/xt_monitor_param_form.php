<?php
/*
* $Id: includes/xt_monitor_param.php
* Module: xt_monitor
* Version: v1.0
* Release Date: 25 de maio de 2007
\* Author: Claudia Antonini Vitiello Callegari 
\* Analista: Gilberto G. de Oliveira (Giba) 
*/

include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';

$sform = new XoopsThemeForm($form['titulo'], 'formulario', xoops_getenv('PHP_SELF')."?xmenu=$xmenu&submenu=$xsubmenu", 'post', $token);


$sform->addElement(new XoopsFormText(_AM_XT_MONITOR_EXT, 'par_30_ext', 50,255,$par_30_ext ));		
$sform->addElement(new XoopsFormTextArea(_AM_XT_MONITOR_PASTASOFF, 'par_33_pastas_off',$par_33_pastas_off ));		

$sform->addElement(new XoopsFormText(_AM_XT_MONITOR_EMAILS, 'par_30_emails',50,255, $par_30_emails ));		
$sform->addElement(new XoopsFormText(_AM_XT_MONITOR_DIRPATH, 'par_30_dirpath',50,255, $par_30_dirpath ));		


$xt_monitor_dirupload=XOOPS_ROOT_PATH.'/modules/xt_monitor/uploads';
if(!is_writable($xt_monitor_dirupload)){
 	$sform->addElement(new XoopsFormLabel(_AM_XT_MONITOR_PASTAUPLOAD,$xt_monitor_dirupload ));		
}

$sform->addElement(new XoopsFormRadioYN(_AM_XT_MONITOR_GERARQ, 'par_11_gerarq',$par_11_gerarq));		
$sform->addElement(new XoopsFormText(_AM_XT_MONITOR_NOMEARQ, 'par_30_nomearq',12,12, $par_30_nomearq ));		



// hidden id
$sform->addElement(new XoopsFormHidden('par_10_id', $par_10_id));

$sform->addElement(new XoopsFormHidden('op', $op));
$sform->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

?>