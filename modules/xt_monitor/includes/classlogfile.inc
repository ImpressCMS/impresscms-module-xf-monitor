<?php
/*
 * Classe extendida para definir  id para cada campo 
 * adaptada por Claudia A. 
 *
 */

class XoopsFormCheckBoxCla  extends XoopsFormCheckBox {	
/**
	 * prepare HTML for output
	 * 
     * @return	string
	 */
	function render(){
		$ret = "";
		if ( count($this->getOptions()) > 1 && substr($this->getName(), -2, 2) != "[]" ) {
			$newname = $this->getName()."[]";
			$this->setName($newname);
		}
		$i=0;// inserido por claudia
		foreach ( $this->getOptions() as $value => $name ) {
			$ret .= "<input type='checkbox' name='".$this->getName()."' value='".$value."'";
			if (count($this->getValue()) > 0 && in_array($value, $this->getValue())) {
				$ret .= " checked='checked'";
			}
			$ret.= " id='".$this->getName()."[$i]'";  //  inserido por  Claudia 
			$ret .= $this->getExtra()." />".$name."\n";
			$i++; // inserido por claudia
						
		}
		return $ret;
	}	
	
}
?>