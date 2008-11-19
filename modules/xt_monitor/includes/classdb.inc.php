<?php
/*
   Autor: Walace Soares
	Livro: Construindo um Site B2C - da Concepção à programação
	Data:  21/01/2001
	
	Classe bd
		Propriedades:
			bd ->  Tipo do banco de dados 
			id ->  Indeitifcador da conexão com o BD
		Opeadores:
			+bd() -> Construtor
			+conecta() ->  Conecta o banco de dados desejado
			
	
	Classe consulta
		Propriedades:
			bd -> Tipo do BD
			id -> Identificador da conexão
			res -> Identificador do resultado da consulta
			row -> Linha atual
			nrw -> Total de linhas resultantes da consulta
			data -> Array com valores dos campos da linha atual

		Operadores:
			+consulta -> Construtor
			+executa -> Executa um comando SQL no BD
			+primeiro -> Busca a primeira linha da consulta
			+proximo -> Busca a próxima linha
			+anterior -> Busca a linha anterior a atual
			+ultimo -> via para a ultima linha da consulta
			+navega -> Busca uma linha especifica
			+dados -> Retorna os campos da linha atual
*/
class bd3 {

 var $bd;
 var $id;

 function bd3($sgbd="postgresql") {
	$this->bd = $sgbd;
 }
 function conecta($bd,$servidor,$porta,$usuario,$senha,$new_link=0) {
	if($this->bd=="postgresql") {
		$this->id = pg_connect($servidor,$porta,$usuario,$senha,$bd);
	}
	else {
		$this->id = mysql_connect($servidor,$usuario,$senha,$new_link);
		if($this->id) {
			mysql_select_db($bd,$this->id);
		}
		else
			$this->id = 0;
	}
 }
}

class consulta3 {

 var $bd;
 var $res;
 var $row;
 var $nrw;
 var $data;

 function consulta3(&$bd) {
	$this->bd = $bd;
 }

 function executa($sql="",$tipo="") {
	if($sql=="") {
		$this->res = 0;
		$this->nrw = 0;
		$this->row = -1;
	}
	if($this->bd->bd=="postgresql") {
		$this->res = pg_exec($this->bd->id,$sql);
		$this->nrw = pg_numrows($this->res);
	}
	else {

		$this->res = mysql_query($sql,$this->bd->id);  
      if(!$this->res)  {
//			echo "Error: ".mysql_errno()."; descrição: ".mysql_error();
//         echo "<br> sql= $sql";
       }
		$this->nrw = @mysql_num_rows($this->res);
      
	}


	$this->row = 0;
	if($this->nrw>0)
		$this->dados();
 }

 function primeiro() {
	$this->row = 0;
	$this->dados();
 }

 function proximo() {
	$this->row = ($this->row<($this->nrw-1))?++$this->row:($this->nrw - 1);
	$this->dados();
 }

 function anterior() {
	$this->row = ($this->row>0) ? --$this->row : 0;
	$this->dados();
 }

 function ultimo() {
	$this->row = $this->nrw-1;
	$this->dados();
 }

 function navega($linha) {
	if($linha>=0 AND $linha<$this->nrw) {
		$this->row = $linha;
		$this->dados();
	}
 }

 function dados() {
	if($this->bd->bd=="postgresql")
		$this->data = pg_fetch_array($this->res,$this->row);
	else {
		mysql_data_seek($this->res,$this->row);
		$this->data = mysql_fetch_array($this->res);
	}
 }
 
 function last_id($seq="",$sql="SELECT LAST_INSERT_ID()") {
	if($this->bd=="postgresql") { 
		$sql = "SELECT CURRVAL('$seq')";
		$this->executa($sql);
		if(!$this->res)
			return 0;
		return $this->data[0];
	}
	else {
		$this->executa($sql);
		return $this->data[0];
	}
  }



}

?>