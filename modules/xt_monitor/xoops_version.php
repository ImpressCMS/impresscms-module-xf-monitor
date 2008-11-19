<?php
/**
* xt_monitor - Module to audit on charts of all the modules.
*
* File: xoops_version.php
*
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license		GNU General Public License (GPL)
*			a copy of the GNU license is enclosed.
* ----------------------------------------------------------------------------------------------------------
* 			xt_monitor
* @since		1.00
* @author		Claudia
* @version		$Id$
*/
 
if ( !defined( 'ICMS_ROOT_PATH' ) ) die( 'ICMS root path not defined' );

$modversion['name'] = _MI_XT_MONITOR_NAME;
$modversion['version'] = 1.0;
$modversion['date'] 			= 'not public';
$modversion['status'] 			= 'Alfa-2';
$modversion['status_version'] 	= 'Alfa';
$modversion['description'] = '';
$modversion['author'] = "Claudia A. V. Callegari";
$modversion['credits'] = "Gilberto Galdino de Oliveira (Giba)";
//$modversion['help'] = "";
$modversion['license'] = "GPL";
$modversion['official'] = 0;
$modversion['image'] = "images/logo.gif";
$modversion['dirname'] = basename( dirname( __FILE__ ) );

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Sql file (must contain sql generated by phpMyAdmin or phpPgAdmin)

// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = "sql/xt_monitor.sql";

// Tables created by sql file (without prefix!)

// se alterar  _MI_XT_MONITOR_DIRNAME diferente de xt_monitor,  deverá alterar em  sql/xt_monitor.sql 
$modversion['tables'][0] = _MI_XT_MONITOR_DIRNAME."_md_module";
$modversion['tables'][1] = _MI_XT_MONITOR_DIRNAME."_qry_querys";
$modversion['tables'][3] = _MI_XT_MONITOR_DIRNAME."_hf_hash_files";
$modversion['tables'][4] = _MI_XT_MONITOR_DIRNAME."_par_param ";



// Search
$modversion['hasSearch'] = 0;
$modversion['search']['file'] = "";
$modversion['search']['func'] = "";

// Menu
$modversion['hasMain'] = 0;
//$modversion['sub'][1]['name'] = _MI_XMAIL_SMNAME1;
//$modversion['sub'][1]['url'] = "submit.php?op=add";



// Templates
//$modversion['templates'][1]['file'] = 'clicheque_consulta1.htm';
//$modversion['templates'][1]['description'] = 'Consulta Clientes ';


// Comments
$modversion['hasComments'] = 0;
//$modversion['comments']['pageName'] = '';
//$modversion['comments']['itemName'] = '';


//$modversion['onInstall'] = ''; 

/**
* Select the number of news items to display on top page
*/

?>