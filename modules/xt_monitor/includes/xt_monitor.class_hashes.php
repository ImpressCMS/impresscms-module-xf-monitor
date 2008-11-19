<?php
/*
* $Id: include/xt_monitor.class_hashes.php
* Module: xt_monitor
* Version: v1.0
* Release Date: 24 de maio  de 2007
* Author: Claudia Antonini Vitiello Callegari
\* Analista: Gilberto G. de Oliveira (Giba)
*/
// Objetivo: Controlar se houve alterações em arquivos de programas de sites xoops.
// 1- Gera os hashes dos arquivos e armazena no banco de dados
// 2- Periodicamente calcula os hashes e compara com o valor armazenado no banco , avisando
//     o administrador quando houve  alteração.

require_once(XOOPS_ROOT_PATH."/modules/xt_monitor/includes/functions.php");

class xt_monitor_hashes {
	var $ext_valid;   // array com extensões de arquivos válidas para calcular
	var $pastas_off;  // array com nomes completos de pastas que não precisam ser verificadas
	var $dirpath;	  // pasta inicial para gerar calculo
	var $arq_log;     // nome e localização do arquivo de log
	var $gravalog;    // indica se gravará arquivo de log ou não
	var $array_files; // array contendo resultado do cálculo dos hashes dos arquivos a partir
	// de $this->dirpath
	var $qtd_p_inc;		// quantidade de inclusões em cada sql

	var $mensagens;		// armazena mensagens durante o processamento
	var $email_notif;   // lista de emails a serem notificados com resultado, qdo. houver erros
	
	var $gerarq;		// Indica se deverá gerar arquivos tipo .htaccess do Apache
	var $nomearq;		// Nome do arquivo tipo .htaccess
	
	

	function xt_monitor_hashes(){
		global $xoopsDB;
		$this->ext_valid=array();
		$this->pastas_off=array();
		$this->dirpath=XOOPS_ROOT_PATH;
		$this->arq_log=XOOPS_ROOT_PATH.'/modules/xt_monitor/uploads/xt_monitor_hash.log';
		$this->gravalog=1;
		$this->array_files=array();
		$this->qtd_p_inc=100;
		$this->mensagens=array();
		$this->email_notif="";
	}

/**
 * Gera matriz $this->array_files, percorrendo as pastas a partir de $this->dirpath
 *
 */

	function calc_files(){
		$this->array_files=array(); // zerar matriz
		$this->mensagens[]=" <b>Geração de hashes de arquivos a partir de: </b> ".$this->dirpath;
		$this->ler($this->dirpath);
	}

	/**
 * Leitura recursiva de diretórios 
 *
 * @param string $file -> nome de arquivo ou pasta .
 * Se for  pasta, chama a função novamente recursivamente até encontrar arquivo.
 * Se for arquivo, guarda no array $this->array_files
 */

	function ler($file) {
		if( in_array($file,$this->pastas_off)) {
				echo "<br>  pasta off ".$file;
				return;
		}
		if (is_dir($file)) {
			// verificar se a pasta é válida
			
			$handle = opendir($file);
			while($filename = readdir($handle)) {
				if ($filename != "." && $filename != "..") {
					$this->ler($file."/".$filename);
				}

			}
			closedir($handle);
		} else {
			$_arq= explode('.',basename($file));
			$ext_arq=$_arq[count($_arq)-1];
			if( !in_array($ext_arq,$this->ext_valid) and !in_array('*',$this->ext_valid)) {
				//echo "<br> Extensão não válida  $ext_arq ";
			}else{
				// extensão válida, colocar no array
				$this->array_files[]=$file;
			    //echo "<br> valido ".$file;
			}
		}
	}
/**
 * Baseado na matriz $this->array_files gerada em $this->calc_files()
 * grava no Banco de dados 
 *
 */
	function salva_hashes(){
		global $xoopsDB;
		// excluir os registros da tabela
		$sql=' truncate table '.$xoopsDB->prefix('xt_monitor_hf_hash_files');
		$result=$xoopsDB->queryf($sql);
		if(!$result){
			$this->mensagens[]="Erro na eliminação dos registros antigos: ".$xoopsDB->error()."<br>".$sql;
			return ;
		}
		
		
		$toti=count($this->array_files);
		for($i=0;$i<$toti;){
			$sql=" replace ".$xoopsDB->prefix('xt_monitor_hf_hash_files').' (hf_30_file,hf_30_hash)  values ';
			$values='';
			for($i2=0;($i2<$this->qtd_p_inc  and $i<$toti) ;$i2++){
				$values.=($i2>0 ? "," :""). "('".$this->array_files[$i]."','".md5_file($this->array_files[$i])."')";
				$i++;
			}
			$sql.=$values;
			//  atualizar o banco
			$result=$xoopsDB->queryf($sql);
			if(!$result){
				$this->mensagens[]="Erro na atualização: ".$xoopsDB->error()."<br>".$sql;
			}else{
				$this->mensagens[]=' Gravou '.$i2.' registros';
			}

		}

	}


/**
 * Resgata valores do banco de dados e compara com a matriz calculada
 *
 */
function compara_hashes(){
	global $xoopsDB;
	$inicio=time();
	$this->calc_files(); // carrega os arquivos atuais
	// resgatar valores do Banco de Dados
	$sql=' select * from '.$xoopsDB->prefix('xt_monitor_hf_hash_files');
	$result=$xoopsDB->queryf($sql);
	$erros=0;
	$this->mensagens[]=date('d/m/Y H:i')." XT_MONITOR - Verificação de arquivos alterados a
	 partir do diretório ".$this->dirpath." - ".XOOPS_URL;
	$this->mensagens[]="";
	if(!$result){
		$this->mensagens[]="Erro no resgate de dados: ".$xoopsDB->error()."<br>".$sql;
		$erros++;
	}else{
		$files_alterados=array();	
		$files_novos=array();
		$files_removidos=array();	
		while($cat_data=$xoopsDB->fetcharray($result)){
			$file=$cat_data['hf_30_file'];
			$hashe=$cat_data['hf_30_hash'];
			if(!in_array($file,$this->array_files)){
				// não encontrou
				$files_removidos[]=$file ;
			}else{
				// comparar os hashes
				if(!(md5_file($file)==$hashe)){
					$files_alterados[]=$file;		
				}
				// eliminar da matriz, pois ja processou
				$chave=array_search($file,$this->array_files);
				$this->array_files[$chave]=null;
			}
		}// fecha while	
		// verificar os que sobraram em $this->array_files,  pois indica que são novos
		$totarray=count($this->array_files);
		for($i=0;$i<$totarray;$i++){
			if(!is_null($this->array_files[$i])){
				$files_novos[]=$this->array_files[$i];
			}
			
		}
		// Notificar os erros encontrados 
		if(count($files_alterados)>0){
			$this->mensagens[]="<b> ARQUIVOS ALTERADOS:</b>\n".implode("\n",$files_alterados);
			$erros++;	
		}	
		if(count($files_removidos)>0){	
			$this->mensagens[]="<b> ARQUIVOS REMOVIDOS:</b>\n".implode("\n",$files_removidos);
			$erros++;
		}	
		if(count($files_novos)>0){	
			$this->mensagens[]="<b> ARQUIVOS NOVOS:</b>\n".implode("\n",$files_novos);
			$erros++;
		}
		
		// gerar arquivo tipo .htaccess do Apache

		
		echo "<pre>";
		if($this->gerarq){
			$this->mensagens[]="<b> Atualização de arquivos $this->nomearq </b>";
			$array_tot=array_merge($files_alterados,$files_novos);
			$totfiles=count($array_tot);
			// gerar matriz, onde o índice é o diretorio 
			$novo_array=array();
			//print_r($array_tot);
			for($i=0;$i<($totfiles);$i++){
				$nomefile=$array_tot[$i];
				$dirfile=dirname($nomefile);
				$sonome=basename($nomefile);
				$novo_array[$dirfile][$sonome]=1;				
			}
			//print_r($novo_array);
			echo "</pre>";
			
			//  adaptar 
			//<Files ~ "\.(htm|html|css|js|php)$">
			
			$tag_xt_monitor='## XT_MONITOR';
			foreach($novo_array as $dirfile  =>$arrayvalue){
			    $stringfile=$tag_xt_monitor.'  '.date('d/m/Y H:i');
				foreach($arrayvalue as $nomearq => $value2 ){	
					$stringfile.="\n<Files $nomearq>\n Deny from all \n </Files> ";
			   }
			   $arqacess=$dirfile.'/'.$this->nomearq;
				$operacao='gerado';
			   // verificar se o arquivo existe e eliminar conteúdo após '## XT_MONITOR'
				if(file_exists($arqacess)){
				   $conteudo=file_get_contents($arqacess);
					$arrayfile=split($tag_xt_monitor,$conteudo);					
					$stringfile=$arrayfile[0]."\n".$stringfile;
					unlink($arqacess);
					$operacao='alterado';
				}
			   if(GrvLog($arqacess,$stringfile)){
				  $this->mensagens[]="Arquivo $operacao ".$arqacess;	
			   }

			}
			
		}
		
		
	}
	if($erros==0){
		$this->mensagens[]=" Não foram encontrados erros , comparou $totarray arquivos ";
	}
	$final=time();
	$this->mensagens[]="Início: ".date('d/m/y H:i:s',$inicio)."  Fim: ".date('d/m/y H:i:s',$final)." <b>Duração: ".intval($final - $inicio)."  segundos </b>	";
	// enviar email com resultado 
	global $xoopsConfig;
	$xoopsMailer =&getMailer();
	$xoopsMailer->useMail();
	$xoopsMailer->multimailer->IsHTML(true);
	//$xoopsMailer->setToUsers($getuser[0]);
	$xoopsMailer->setToEmails(explode(';',$this->email_notif));
	$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
	// setar o return-path (versões da phpmailer anteriores a 1.70  não setavam..)
	$xoopsMailer->multimailer->Sender=$xoopsConfig['adminmail'];
	$xoopsMailer->setFromName($xoopsConfig['sitename']);
	$xoopsMailer->setSubject('XT_MONITOR - Resultado da verificação de Arquivos');
	
	$xoopsMailer->setBody(nl2br(implode("\n",$this->mensagens)));
	
	
	
	if ( !$xoopsMailer->send() ) {
		$this->mensagens[]="\nFalha no envio de email para (".$this->email_notif.") ,com resultado da operação ".$xoopsMailer->getErrors();
	}else{
		$this->mensagens[]="\nEmail enviado com sucesso para ($this->email_notif) ,com resultado da operação ";
	}
	
	if($this->gravalog){
		GrvLog($this->arq_log,implode("\n",$this->mensagens)."\n".str_repeat('*',60));
	}
	

}

   function getHtmlMensagens()
    {
        $ret = '<h4>Resultado</h4>';
        if (!empty($this->mensagens)) {
            foreach ($this->mensagens as $mens) {
                $ret.= $mens.'<br />';
            }
        } else {
            $ret.= 'Não Há <br />';
        }
        return $ret;
    }




} // fecha class
?>