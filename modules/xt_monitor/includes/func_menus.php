<?php

//function adminMenu ($currentoption = 0, $breadcrumb = '')
//function adminMenu ($currentoption = 0)
function adminMenu ($xmenu='',$xsubmenu='',$admin_mydirname='')
{
    #######################################################################################################
    ## $admin_mydirname = Nome do diretório raiz do módulo.                              ##
    ##                    Não pode ser movido para outro local.                                          ##
    #######################################################################################################

    //$admin_mydirname = basename( dirname( dirname( __FILE__ ) ) ) ; // Generalizando o nome do diretório do módulo. - GibaPhp

    include(XOOPS_ROOT_PATH.'/modules/'.$admin_mydirname.'/admin/menu.php');

    $submenu = array();

	$currentoption='current'; // default...
	
    if (isset($xmenu) && $xmenu != ''){
	  $currentoption=$xmenu;
    }else{
      $url = trim(basename($_SERVER['PHP_SELF']));
  	  foreach($adminmenu as $key => $value ){
	    $soarq=basename($value['link']);
        $pos=strpos($soarq,'?');
	    if($pos>0){
	  	  $soarq=substr($soarq,0,$pos);
	    }else{
	   	  $soarq=substr($soarq,0);
	    }

        if(trim($soarq)==trim($url)){
		  $currentoption=$key;
		  break;
	    }
	  }
    }
    if (isset($xsubmenu) && $xsubmenu != ''){
	  $currentoptionSub=$xsubmenu;
    }else{
	  $currentoptionSub=0;
    }
	########################by rplima - submenu#########################################

	/* Nice button styles */
	?><style type="text/css">
	#buttontop { float:left; width:100%; background: #e7e7e7; font-size:93%; line-height:normal; border-top: 1px solid #b7ae88; border-left: 1px solid #b7ae88; border-right: 1px solid #b7ae88; margin: 0; }
	#buttonbar { float:left; width:100%; background: #e7e7e7 url("<?php echo XOOPS_URL."/modules/$admin_mydirname/images/bg.gif"?>") repeat-x left bottom; font-size:93%; line-height:normal; border-left: 1px solid #b7ae88; border-right: 1px solid #b7ae88; margin-bottom: 12px; }
	#buttonbar ul { margin:0; margin-top: 15px; padding:10px 10px 0; list-style:none; }
	#buttonbar li { display:inline; margin:0; padding:0; }
	#buttonbar a { float:left; background:url("<?php echo XOOPS_URL."/modules/$admin_mydirname/images/left_both.gif"?>") no-repeat left top; margin:0; padding:0 0 0 9px; border-bottom:1px solid #b7ae88; text-decoration:none; }
	#buttonbar a span { float:left; display:block; background:url("<?php echo XOOPS_URL."/modules/$admin_mydirname/images/right_both.gif"?>") no-repeat right top; padding:5px 15px 4px 6px; font-weight:bold; color:#765; }
	/* Commented Backslash Hack hides rule from IE5-Mac \*/
	#buttonbar a span {float:none;}
	/* End IE5-Mac hack */
	#buttonbar a:hover span { color:#272727; }
	#buttonbar #current a { background-position:0 -150px; border-width:0; }
	#buttonbar #current a span { background-position:100% -150px; padding-bottom:5px; color:#272727; }
	#buttonbar a:hover { background-position:0% -150px; }
	#buttonbar a:hover span { background-position:100% -150px; }
	.tdbuttonsmall, .tdbuttonsmall_off { vertical-align: top; border: 0px #cccccc solid; padding: 3px; }
	.tdbuttonsmall_off {filter: alpha(opacity=30); -moz-opacity: 0.3; opacity: 0.30; }
	.subtitle { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; }

	<!--########################by rplima - submenu#########################################-->
	#buttonbar1 { float:left; width:100%; background: #e7e7e7 url("../images/bg.gif") repeat-x left bottom; font-size:93%; line-height:normal; border-left: 1px solid #b7ae88; border-right: 1px solid #b7ae88; margin-bottom: 12px; margin-top: -12px; }
	#buttonbar1 ul { margin:0; margin-top: 0; padding:10px 10px 0; list-style:none; }
	#buttonbar1 li { display:inline; margin:0; padding:0; }
	#buttonbar1 a { float:left; background:url("../images/left_both.gif") no-repeat left top; margin:0; padding:0 0 0 9px; border-bottom:1px solid #b7ae88; text-decoration:none; }
	#buttonbar1 a span { float:left; display:block; background:url("../images/right_both.gif") no-repeat right top; padding:5px 15px 4px 6px; font-weight:bold; color:#765; }
	/* Commented Backslash Hack hides rule from IE5-Mac \*/
	#buttonbar1 a span {float:none;}
	/* End IE5-Mac hack */
	#buttonbar1 a:hover span { color:#272727; }
	#buttonbar1 #current a { background-position:0 -150px; border-width:0; }
	#buttonbar1 #current a span { background-position:100% -150px; padding-bottom:5px; color:#272727; }
	#buttonbar1 a:hover { background-position:0% -150px; }
	#buttonbar1 a:hover span { background-position:100% -150px; }
	<!--########################by rplima - submenu#########################################-->
	</style><?php
	global $xoopsDB, $xoopsModule, $xoopsConfig, $xoopsModuleConfig;
	$xv = str_replace('XOOPS ','',XOOPS_VERSION);
	$tblCol = Array();
	$tblCol[0]=$tblCol[1]=$tblCol[2]=$tblCol[3]=$tblCol[4]=$tblCol[5]=$tblCol[6]=$tblCol[7]=$tblCol[8]=$tblCol[9]=$tblCol[10]=$tblCol[11]=$tblCol[12]=$tblCol[13]=$tblCol[14]=$tblCol[15]='';

   	########################by rplima - submenu#########################################
    $tblCol[$currentoption] = 'current';
    $tblColSub[$currentoptionSub] = 'current';
   	########################by rplima - submenu#########################################

	//$nossapatria = "<img src=".XOOPS_URL."/modules/".$admin_mydirname."/images/br.gif align='middle' alt='Brasil' width='21' height='14' hspace='0'/>";
	//$xt = "<img src=".XOOPS_URL."/modules/".$admin_mydirname."/images/xt.gif align='middle' alt='XoopsTotal Support Brasil' hspace='0'/>";

	echo "<div id='buttontop'>";
	echo "<table style=\"width: 100%; padding: 0; \" cellspacing=\"0\"><tr>";
	echo "<td style=\"width: 50%; font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;\"><b>"._CPHOME.' -  '.$xoopsModule->name()."</b></td>";
	echo "<td style=\"width: 50%; font-size: 10px; text-align: right; color: #2F5376; padding: 0 6px; line-height: 18px;\">  </td>";
	echo "</tr></table>";
	echo "</div>";

	echo "<div id='buttonbar'>";
	echo "<ul>";

	foreach($adminmenu as $key => $value ){
//		echo 'key ',$key, ' link ',$value['link'],' title ',$value['title'];
		$soarq=trim(basename($value['link']));
     	########################by rplima - submenu#########################################
        $te = explode('?',$soarq);
        if (count($te) > 1)
          $soarq .= '&xmenu='.$key;
        else
          $soarq .= '?xmenu='.$key;
     	########################by rplima - submenu#########################################

        if (isset($tblCol)){
           $tblCol[] = "" ; // Definindo variavel para prevenir erros de notice. GibaPhp
        }
        echo "<li id='".$tblCol[$key]."'><a href=\"".$soarq."\"><span>".$value['title']."</span></a></li>";

		########################by rplima - submenu#########################################
        if (isset($value['sub']) && count($value['sub']) >= 1){
			foreach($value['sub'] as $keySub => $valueSub ){
				$soarqSub=basename($valueSub['link']);
				$teSub = explode('?',$soarqSub);
				if (count($teSub) > 1)
					$soarqSub .= '&xmenu='.$key.'&xsubmenu='.$keySub;
				else
					$soarqSub .= '?xmenu='.$key.'&xsubmenu='.$keySub;
					$submenu[$key] .= "<li id='".$tblColSub[$keySub]."'><a href=\"".$soarqSub."\"><span>".$valueSub['title']."</span></a></li>";
				}
			}
		########################by rplima - submenu#########################################
	}

	echo "</ul></div>";
    ########################by rplima - submenu#########################################
	$subs = array_keys($submenu);
	if (in_array($currentoption,$subs)){
	  echo "<div id='buttonbar1'>";
  	  echo "<ul>";
      echo $submenu[$currentoption];
      echo "</ul></div>";
	}
	########################by rplima - submenu#########################################
	echo "<br style='clear:both;' />";
}
?>