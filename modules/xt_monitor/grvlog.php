<?php
// grvlog.php
//      Function: GrvLog - Grava Log em arquivos TEXTOS padrão ASCII

Function xt_monitor_GrvLog($LogArqnome="", $writeStr="",$param="a+" ) {
//  $LogArqnome=  nome do arquivo a ser gravado
// $writeStr=  string a ser gravada no final do arquivo 


if ($LogArqnome=="" or  $writeStr=="") {
   echo "Falta parametros para gravar arquivo de log ";
   echo "veja    $LogArqnome  -  $writeStr" ;
    return false;
}


$LogHand = @fopen($LogArqnome, $param) ;
if(!$LogHand){
	return false;
}
//if(!ereg("\n$",$writeStr)) {
//	$writeStr .= "\n";
//}

fputs($LogHand,$writeStr);
fclose($LogHand);
chmod($LogArqnome,0660);
return true;
}


