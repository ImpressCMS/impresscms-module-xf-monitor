<?php
/*
* $Id: admin/xt_monitor_vererros.php
* Module: xt_monitor
* Version: v1.0
* Release Date: 13 de abril de 2007
\* Author: Claudia Antonini Vitiello Callegari 
\* Analista: Gilberto G. de Oliveira (Giba) 
*/
//$area=0;  // todos podem acessar

// para ser usado com include em outros scripts
// visualizar  ou  editar arquivos de log  tipo texto

include("admin_header.php");

$arq=XOOPS_ROOT_PATH.'/modules/xt_monitor/uploads/xt_monitor_erros.php';

if($_GET['limpar']=='limpar'){
	$hiddens['limpar'] = 'limpar2';
	xoops_confirm($hiddens,$_SERVER['PHP_SELF'],'Confirma limpar arquivo de erros ?','Limpar',true);
	
}

if($_POST['limpar']=='limpar2'){
	// apagar o arquivo
	if(unlink($arq)){
		xoops_result('Arquivo de Erros foi apagado com sucesso ');
	}else{
		xoops_error('Erro ao eliminar arquivo de erros ');
	}
	
}

$opt ='ver';
if (isset($_POST['post']))
   $opt=$_POST['post'];


if($opt!='salvar') {
if ($LogHand = fopen($arq, 'r')) {
       if(filesize($arq)>0){
       	 $conteudo=fread($LogHand, filesize($arq)) ;
       }else{
       	  $conteudo=' ';
       }
		
       fclose($LogHand);
}else {
        if(!file_exists($arq)){
        	//xoops_error("Arquivo $arq  não encontrado , não ha erros ") ;	
        	xoops_error("Não ha erros ") ;	
        	
        }else{
        	//xoops_error("Não foi possível abrir o arquivo $arq") ;	
        	xoops_error("Não foi possível abrir o arquivo de erros ") ;	
        }
		
        $opt=' ';
}
}


switch($opt){
    case "salvar":
	$conteudo=$_POST['conteudo'];	
    if ($LogHand = fopen($arq, 'w')) {
        fputs($LogHand,$conteudo ) ;
        fclose($LogHand);
       xoops_result('Arquivo salvado com sucesso');
    }else {
       xoops_error('Não foi possível salvar os dados, tente novamente');
    }
    break;
    case "ver":
       echo nl2br($conteudo);
        break;
    case "editar" :

        $sform = new XoopsThemeForm('Erros gerados, devido falhar gravação de log no Banco de Dados', "storyform", xoops_getenv('PHP_SELF') );
        $sform->addElement(new XoopsFormTextArea("",'conteudo', $conteudo, 50,68));
        $sform->addElement( new XoopsFormButton('', 'post', "salvar", 'submit') );
        $sform->display();

    default:

}
echo "<h4 align='center' > <a href='".$_SERVER['PHP_SELF'].'?limpar=limpar'.">Apagar Erros </a></h4>";
xoops_cp_footer();
?>