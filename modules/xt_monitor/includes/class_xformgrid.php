<?php
/*
* class_xformgrid.php
* 
* Version: v1.0
* Release Date: 15 de março de 2007 
\* Author: Claudia Antonini Vitiello Callegari (claudia.avcallegari@gmail.com)
*/

/**
 * Finalidade: 1- Gerar formulario para atualizar vários registros de uma tabela
 * 				para ser utilizada dentro do xoops, pois usa $xoopsDB
 * 				Formulario gerado em forma de tabela tipo linha e coluna
 *
 *				2- atualizar a tabela após enviar o formulário via post 
 * 	
 * Obs.  Implementar para poder filtrar registros 
 */				

/*
$array_chaves -> array contendo campos chaves (id dos registros a serem atualizados )  da tabela
		Exemplo  $array[nome do campo][id do registro]

$array_campos -> array contendo o nome dos campos a serem atualizados e o type do input
		Exemplo $array_campos[0]['name']=nome do campo
				$array_campos[0]['type']=text  ou   radio  
				$array_campos[0]['size']=size do campo no form
				$array_campos[0]['label']=titulo da coluna 
				$array_campos[0]['value']=valor default , se não tiver valor
				$array_campos[0]['maxlength']=valor default , se não tiver valor
				$array_campos[0]['options']=array()  com opções no caso de radio 
				
				$array_campos[0]['extra']=qualquer comando extra a ser
												colocado na tag <input>			
				Exemplo para 'extra' ->  DISABLED , READONLY
				
				$array_campos[0]['button_exibe']=1; 1=sim  0=não .Indica se irá exibir botão tipo exibe/esconde
				conteúdo da célula da tabela.  Indicado quando o conteúdo for grande.
				
				
				
				
$array_values -> array bidimensional com os valores de cada campo para cada registro.
	Exemplo $array_values[id do registro][nome do campo]=valor do campo
	Pode ser montar pelo método  $this->pega_valores();
	
*/

include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
class xformgrid {
	
	var $table;		// nome da tabela
	var $campoid;	// nome do campo de id da tabela	
	var $array_chaves ; 
	var $array_campos ;
	var $array_values ;
	var $widhform;
	var $op ; // campo hidden (op) para formulario	default='salvar'
	var $exibe_id;  // Indica se irá exibir a coluna de id .
					// 0 não exibe  ou nome do titulo da coluna para exibir
	
	var $_errors = array();
	var $array_serialize = array();				
					
	   /**
     * add an error 
     * 
     * @param string $value error to add
     * @access public
     */
    function setErrors($err_str)
    {
        $this->_errors[] = trim($err_str);
    }

    /**
     * return the errors for this object as an array
     * 
     * @return array an array of errors
     * @access public
     */
    function getErrors()
    {
        return $this->_errors;
    }

    /**
     * return the errors for this object as html
     * 
     * @return string html listing the errors
     * @access public
     */
    function getHtmlErrors()
    {
        $ret = '<h4>Errors</h4>';
        if (!empty($this->_errors)) {
            foreach ($this->_errors as $error) {
                $ret .= $error.'<br />';
            }
        } else {
            $ret .= 'None<br />';
        }
        return $ret;
    }				
    
    
	function xformgrid($table,$campoid,$array_chaves=array(),$array_campos=array(),$array_values=array(),$widhform='100%') {

		$this->table=$table;
		$this->campoid=$campoid;		
		$this->array_chaves=$array_chaves;
		$this->array_campos=$array_campos;
		$this->array_values=$array_values;		
		$this->widhform=$widhform;
		$this->op='salvar';		
		$this->exibe_id=0;
	}
	/**
	 * Montar form em  tabela  tipo linha , coluna
	 * Sendo uma linha para cada registro da tabela e cada coluna um campo
	 * Estrutura da matriz após enviar form  $_POST['dados']['id do registro']['nome do campo']= valor
	 *
	 */
	function exibe_form(){

		$formulario="<form method='post' >
		<table border='1'  style='width:$this->widhform '  >";

		// cabeçalho da tabela
		$formulario.="<tr class='head' align='center' > ";
		if($this->exibe_id){
			$formulario.="<td>$this->exibe_id </td>";
		}
		for($i=0;$i<count($this->array_campos);$i++){
			$formulario.="<td>".$this->array_campos[$i]['label']  ." </td>  ";
		}
		$formulario.="</tr>";
		
		// conteudo da tabela
		foreach($this->array_chaves as $key => $value ){

		$formulario.="<tr class='even'  > ";	
		if($this->exibe_id){
			$formulario.="<td>$value </td>";
		}
		
		for($i=0;$i<count($this->array_campos);$i++){
			$formulario.="<td  >";

//  implementar botão exibe_esconde conforme parâmetro setado na array_campos			
			if($this->array_campos[$i]['button_exibe']==1){
				$formulario.="<input name='exibemen' id='{$key}_buttonexibe_$i'  type='button' value='Exibe' onclick=\"exibe_esconde('$i','{$key}_exibe_','{$key}_buttonexibe_')\" ><br> " ;
				$formulario.="<span id='{$key}_exibe_$i' style='display:none'  > ";
			}

// antes sem usar classe do xoops  
//			"<input type='".$this->array_campos[$i]['type']."' name='dados[$value][".$this->array_campos[$i]['name']."]'
//			 size='".$this->array_campos[$i]['size']."' ".$this->array_campos[$i]['extra'];
//			
//			$valor=$this->array_values[$value][$this->array_campos[$i]['name']];
//			
//			if(isset($valor)  and  $this->array_campos[$i]['type']!='checkbox'  ){			
//				$formulario.=" value='$valor'" ;			
//			}else{
//				$formulario.=" value='".$this->array_campos[$i]['value']."'";
//			}
//						
//			//if ($this->array_campos[$i]['type']=='checkbox' and isset($valor) and $valor>0 ) {
//			if ($this->array_campos[$i]['type']=='checkbox' and $valor== $this->array_campos[$i]['value'] ) {
//				$formulario.= " checked='checked'";
//			}
//			
//			$formulario.=" /> </td>  ";

			// usar classes do xoops
			$type=$this->array_campos[$i]['type'];
			$valor=$this->array_values[$value][$this->array_campos[$i]['name']];
			$name="dados[$value][".$this->array_campos[$i]['name']."]";			
			$size=$this->array_campos[$i]['size'];
			$maxlength=$this->array_campos[$i]['maxlength'];
			
			$options=$this->array_campos[$i]['options'];
			if($this->array_campos[$i]['options_eval']){
				eval($options);
			}
			
			if($type=='text'){
				$xform=new XoopsFormText('',$name,$size,$maxlength,$valor);
				$formulario.=$xform->render();
			}elseif ($type=='radio'){
				$xform=new XoopsFormRadio('',$name,$valor);
				$xform->addOptionArray($options);
				$formulario.=$xform->render();
			}elseif ($type=='checkbox'){
				$xform=new XoopsFormCheckBox('',$name,$valor);
				$xform->addOptionArray($options);
				$formulario.=$xform->render();
				
			}
			
			
			$formulario.="</td>";
			if($this->array_campos[$i]['button_exibe']==1){
				$formulario.="</span> ";
			}
		}
		$formulario.="</tr>";
		
		}
		$formulario.="</table><input type='submit' name='enviar' value='salvar' />
		<input type='hidden' name='op' value='$this->op' />".
		$GLOBALS['xoopsSecurity']->getTokenHTML()."		
		</form>";

		echo $formulario;
		
	}
	
	/**
	 * Atualizar tabela, usando $_POST  e $xoopsDB
	 * Montar de duas maneiras:
	 * 1- prevendo que foi passado a classe mãe, então usa-la para atualizar
	 * 2- prevendo que não foi passado classe mãe, então montar update manual
	 * 
	 * Estrutura da matriz  $_POST['dados']['id do registro']['nome do campo']= valor
	 * 
	 * @return boolean
	 */
	function atualiza_table($classemae=null,$force_inc=0){
		if(!is_null($classemae)){	
			// implementar  se desejar... 
				
		}else{
			global $xoopsDB;
			foreach($_POST['dados'] as $idreg =>$arraycampos ){
				if($force_inc){
					// veriricar se existe o id do registro 
					$sql=" select $this->campoid from $this->table where $this->campoid='$idreg' ";
					$result=$xoopsDB->query($sql);
					if(!$result){
						$this->setErrors('Erro na sql '.$sql );
						return false;
					}else{
						if($xoopsDB->getRowsNum($result)==0){
							// inserir o registro
							$sql=' insert into '.$this->table." ($this->campoid) values ('$idreg') " ;
							$result=$xoopsDB->query($sql);
							if(!$result){
								$this->setErrors('Erro ao inserir id '.$idreg);
								return false;
							}
						}
					}
				}
			
				$sql=' update '.$this->table." set ";				
				$totcampos=count($arraycampos);
				$i=0;
				foreach( $arraycampos as $nomecampo => $valorcampo ){
					//  rever esta parte, pois deve ser verificado se o tipo do campo
					// da tabela é numerico ou não  e  não  o tipo do conteudo ......
					
					if($this->array_serialize[$nomecampo]){
						$valorcampo=serialize($valorcampo);
					}else{
//						echo "<br>";
//						var_dump($this->array_serialize);
//						echo "<br>";
					}
					
					
					if(is_numeric($valorcampo)){
						$sql.= $nomecampo.'='.$valorcampo;					
					}else {
						$sql.=$nomecampo.'='.$xoopsDB->quoteString($valorcampo);
					}
					$i++;
					if($i<$totcampos) $sql.=",";
					
				}
				$sql.=" where $this->campoid='$idreg' ";
				
				$result=$xoopsDB->query($sql);
				if(!$result){
					 $this->setErrors('Erro ao atualizar registro id '.$idreg);
					 return false;
				}
			}
			return true;			
		}
		
		
	}
		
	/**
	 * Retorna array com valores dos campos por registro
	 * Se receber array vazio , retornará para todos os campos
	 * return array  $array_values
	 *  Exemplo $array_values[id do registro][nome do campo]=valor do campo
	 *
	 * @param string $table
	 * @param string $campoid
	 * @param array  $array_campos Estrutura: $array_campos[0]['name']=nome do campo1,  
	 * 	     								$array_campos[1]['name']=nome do campo2 ...  	
	 */
	function pega_valores($array_campos=array()){
		global $xoopsDB;
		if(empty($this->table) or empty($this->campoid)){
			xoops_error('É necessário informar tabela e campo chave para resgatar valores ');
			return false;			
		}
		
		$sql=' select ';

		$totcp=count($array_campos);
				
		if(($totcp)>0){
			$sql.=$this->campoid.',';
			for($i=0;$i<$totcp;$i++){
				$sql.=$array_campos[$i]['name'];
				if($i<$totcp-1){
					$sql.=",";
				}
			}
		}else {
			$sql.=' * ';			
		}
		$sql.=" from $this->table ";		
		$result=$xoopsDB->query($sql);
		if(!$result){
			xoops_error('Erro ao resgatar valores '.$sql.'  -  '.$xoopsDB->error());
			return false;			
		}
		$array_values=array();
		while($cat_data=$xoopsDB->fetcharray($result)){
			foreach($cat_data as $key => $value ){
				if($this->array_serialize[$key]){
					$value=unserialize($value);
				}
		
				$array_values[$cat_data[$this->campoid]][$key]=$value;
			
			
			}
		}
		return $array_values;
//		echo "<pre>";
//		var_dump($array_values);
//		echo "</pre>";
	}

	
}

?>
<script type="text/javascript">
function exibe_esconde(i,id_exibe,id_button){
   var d = document;
   var compose = d.getElementById(id_exibe+i);
   var button=d.getElementById(id_button+i);
   
   if (compose.style.display == ""){
	     compose.style.display = "none";
	     button.value='exibe';
   }
   else {
      compose.style.display = "";
      button.value='esconde';
      
      
   }
}
</script>