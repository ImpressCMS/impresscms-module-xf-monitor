<?php
/*
* $Id: includes/xt_guardian_c_param.php
* Module: xt_guardian_c
* Version: v1.0
* Release Date: 23 de março  de 2007
* Author: Claudia Antonini Vitiello Callegari 
* Analista: Gilberto G. de Oliveira (Giba) 
*/

include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';

$sform = new XoopsThemeForm($form['titulo'], 'formulario', xoops_getenv('PHP_SELF'), 'post', $token);

define("_AM_XT_GUARDIAN_C_TIMEFAULT","Enviar email ao admin do webservice, de X em X minutos, qdo. o mesmo falhar ");
define("_AM_XT_GUARDIAN_C_DTULTEMAIL","Data-hora do último email enviado ");

$sform->addElement(new XoopsFormText(_AM_XT_GUARDIAN_C_URL, 'par_30_url', 50,255,$par_30_url ));		
$sform->addElement(new XoopsFormText(_AM_XT_GUARDIAN_C_EMAIL, 'par_30_email',50,255, $par_30_email ));		
$sform->addElement(new XoopsFormText(_AM_XT_GUARDIAN_C_TIMEFAULT, 'par_10_timefault',4,4, $par_10_timefault ));		
$sform->addElement(new XoopsFormLabel(_AM_XT_GUARDIAN_C_DTULTEMAIL,date('d/m/Y H:i:s',$par_10_dtultemail) ));		

$xt_guardian_dirupload=XOOPS_ROOT_PATH.'/modules/xt_guardian_c/uploads';
if(!is_writable($xt_guardian_dirupload)){
 	$sform->addElement(new XoopsFormLabel(_AM_XT_GUARDIAN_C_PASTAUPLOAD,$xt_guardian_dirupload ));		
}

// hidden id
$sform->addElement(new XoopsFormHidden('par_10_id', $par_10_id));

$sform->addElement(new XoopsFormHidden('op', $op));
$sform->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

?>