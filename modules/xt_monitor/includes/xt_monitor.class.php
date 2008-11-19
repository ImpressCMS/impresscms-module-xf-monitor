<?php
/*
* $Id: include/xt_monitor.class.php
* Module: xt_monitor
* Version: v1.0
* Release Date: 03 de abril de 2007
* Author: Claudia Antonini Vitiello Callegari 
\* Analista: Gilberto G. de Oliveira (Giba) 
*/

### =============================================================
### Developer: Fábio Egas e Fernando Santos 
### Copyright: Mastop InfoDigital © 2003-2007
### -------------------------------------------------------------

include_once XOOPS_ROOT_PATH."/class/xoopsobject.php";

class xt_monitor extends XoopsObject
{
	var $db;
	var $tabela;
	var $id;
	var $total=0;
	var $afetadas=0;
	var $id_is_string=0;  // 0(não)  ou 1(sim)  Indica que o campo do id é string
	
	
	// construtor da classe
	function xt_monitor()
	{
		// Não usado diretamente
	}

	function store()
	{
		if ( !$this->cleanVars() ) {
			return false;
		}

		// trecho inserido por Claudia
		if(!$this->validar()){
		   //$this->setErrors("Erro na validação:<br />");	
			return false;
		}
		
		if(method_exists($this,'atualiza_log')){
			if(!$this->atualiza_log()){
				$this->setErrors('Erro na atualização do log');
				return false;
			}
		}else {
			//echo 'não existe o metodo atualiza_log'	;
		}
		//  fim do trecho 
		
		$myts =& MyTextSanitizer::getInstance();
		foreach ( $this->cleanVars as $k=>$v ) {
			$indices[] = $k;
			$valores[] = $v;
			//$$k = $v;
		}
		if (is_null($this->getVar($this->id)) || $this->getVar($this->id) == 0) {
			$sql = "INSERT INTO ".$this->tabela." (";
			$sql .= implode(",", $indices);
			$sql .= ") VALUES (";
			for ($i = 0; $i<count($valores); $i++){
				if(!is_int($valores[$i])){
					$sql .= $this->db->quoteString($valores[$i]);
				}else{
					$sql .= $valores[$i];
				}
				if ($i != (count($valores)-1)) {
					$sql .= ",";
				}
			}
			$sql .= ")";
		}else {
			$sql ="UPDATE ".$this->tabela." SET ";
			for ($i = 1; $i<count($valores); $i++){
				$sql .= $indices[$i]."=";
				if(!is_int($valores[$i])){
					$sql .= $this->db->quoteString($valores[$i]);
				}else{
					$sql .= $valores[$i];
				}
				if ($i != (count($valores)-1)) {
					$sql .= ",";
				}
			}
			$sql .= " WHERE ".$this->id." = ";
			if($this->id_is_string){
				$sql.="'".$this->getVar($this->id)."'";
			}else{
				$sql.=$this->getVar($this->id);
			}
			
			
		}
		//echo $sql;
		$result = $this->db->query($sql);
		$this->afetadas = $this->db->getAffectedRows();
		if (!$result) {
			$this->setErrors("Erro ao gravar dados na Base de Dados. <br />".$this->db->error());
			return false;
		}
		if (is_null($this->getVar($this->id)) || $this->getVar($this->id) == 0) {
			$this->setVar($this->id, $this->db->getInsertId());
			return $this->db->getInsertId();
		}
		return $this->getVar($this->id);
	}

	function atualizaTodos($campo, $valor, $criterio = null)
	{
		$set_clause = is_numeric($valor) ? $campo.' = '.$valor : $campo.' = '.$this->db->quoteString($valor);
		$sql = 'UPDATE '.$this->tabela.' SET '.$set_clause;
		if (isset($criterio) && is_subclass_of($criterio, 'criteriaelement')) {
			$sql .= ' '.$criterio->renderWhere();
		}
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		return true;
	}

	function delete()
	{
		$sql = sprintf("DELETE FROM %s WHERE ".$this->id." = %u", $this->tabela, $this->getVar($this->id));
		if ( !$this->db->query($sql) ) {
			return false;
		}
		$this->afetadas = $this->db->getAffectedRows();
		return true;
	}

	function deletaTodos($criterio = null)
	{
		$sql = 'DELETE FROM '.$this->tabela;
		if (isset($criterio) && is_subclass_of($criterio, 'criteriaelement')) {
			$sql .= ' '.$criterio->renderWhere();
		}
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		$this->afetadas = $this->db->getAffectedRows();
		return true;
	}

	function load($id)
	{
		$id="'".$id."'";
		$sql = "SELECT * FROM ".$this->tabela." WHERE ".$this->id."=".$id." LIMIT 1";
		$myrow = $this->db->fetchArray($this->db->query($sql));
		if (is_array($myrow) && count($myrow) > 0) {
			$this->assignVars($myrow);
			return true;
		}else{
			return false;
		}
	}

	function PegaTudo($criterio=null, $objeto=true)
	{
		$ret = array();
		$limit = $start = 0;
		$classe = get_class($this);
		if ( !$objeto ) {
			$sql = 'SELECT '.$this->id.' FROM '.$this->tabela;
			if (isset($criterio) && is_subclass_of($criterio, 'criteriaelement')) {
				$sql .= ' '.$criterio->renderWhere();
				if ($criterio->getSort() != '') {
					$sql .= ' ORDER BY '.$criterio->getSort().' '.$criterio->getOrder();
				}
				$limit = $criterio->getLimit();
				$start = $criterio->getStart();
			}
			$result = $this->db->query($sql, $limit, $start);
			$this->total = $this->db->getRowsNum($result);
			if ($this->total > 0){
				while ( $myrow = $this->db->fetchArray($result) ) {
					$ret[] = $myrow[$this->id];
				}
				return $ret;
			}else{
				return false;
			}
		} else {
			$sql = 'SELECT * FROM '.$this->tabela;
			if (isset($criterio) && is_subclass_of($criterio, 'criteriaelement')) {
				$sql .= ' '.$criterio->renderWhere();
				if ($criterio->getSort() != '') {
					$sql .= ' ORDER BY '.$criterio->getSort().' '.$criterio->getOrder();
				}
				$limit = $criterio->getLimit();
				$start = $criterio->getStart();
			}
			$result = $this->db->query($sql, $limit, $start);
			$this->total = $this->db->getRowsNum($result);
			if ($this->total > 0){
				while ( $myrow = $this->db->fetchArray($result) ) {
					$ret[] = new $classe($myrow);
				}
				return $ret;
			}else{
				return false;
			}
		}
	}
	/*
	Usando a função administração:
	$url = URL de onde o script está sendo executado. Ex.:  XOOPS_URL."/modules/xt_conteudo/admin/index.php"
	$campos = Array com as seguintes Opções:
	['op'] = Nome do 'Case' OP que o script está sendo executado. Ex.: $campos['op'] = 'listar'
	['form'] = 0 (zero) para exibir os registros em modo visualização, 1 em modo edição. Ex.:  $campos['form'] = 1
	['checks'] = 1 para exibir checkboxes para operações em grupo. Será exibido também um option list com operações em grupo (com o nome de group_action). VER MAIS OPÇÕES ABAIXO -  Ex.:  $campos['checks'] = 1
	['lang']['titulo'] = Título da página de administração (Preferível que seja constante de tradução). Ex.: $campos['lang']['titulo'] = 'XT-Conteúdo - Administração'
	['lang']['filtros'] = Nome para os filtros (Preferível que seja constante de tradução). Ex.: $campos['lang']['filtros'] = "Filtros"
	['lang']['exibir'] = Palavra "Exibir"  (Preferível que seja constante de tradução). Ex.: $campos['lang']['exibir'] = "Exibir "
	['lang']['exibindo'] = Nome para a linha de exibição de registros  (Preferível que seja constante de tradução).Ex.: $campos['lang']['exibindo'] = "Exibindo do %u<sup>o</sup> ao %u<sup>o</sup> de <b>%u</b> páginas."
	['lang']['por_pagina'] = Tradução (preferível constante) para "registros por página". Ex.: $campos['lang']['por_pagina'] = "conteúdos por página"
	['lang']['acao'] = Tradução para palavra "Ação" (preferível constante). Ex.: $campos['lang']['acao'] = "Ação"
	['lang']['semresult'] = Frase exibida quando não houverem registros encontrados (Preferível que seja constante de tradução). Ex.: $campos['lang']['semresult'] = "Nenhum Registro Encontrado!"
	['precrit']['campo'] = Array contento os nomes dos campos a serem usados como pré-critério.
	['precrit']['valor'] = Array contento os valores dos campos a serem usados como pré-critério.
	['nome'] = Array contendo todos os nomes dos campos (banco de dados) a serem exibidos na tela.
	['rotulo'] = Array contendo todos os rótulos (preferível constante) dos campos exibidos na tela.
	['tipo'] = Array contendo os tipos de dados exibidos na tela. Podem ser 'none', 'select', 'simnao' e 'text'.
	['tamanho'] = Array contendo os tamanhos para os filtros dos campos do tipo 'text'.
	['options'] = Array contendo as opções para os filtros dos campos do tipo 'select'.
	['nofilters'] = 1 para que os filtros sejam retirados.
	['nosort'] = Array com valor '1' para os campos que não se deseja link de ordenar.
	['show'] = Array contendo códigos PHP para quando se deseja exibir algo diferente do registro que está no banco de dados ( formatar um link, uma imagem, uma data, etc). Neste caso pode-se usar todas as constantes do Xoops, superglobais e o objeto em questão, através da variável $reg.

	-----------------------
	Exemplos de ['precrit'], ['nome'], ['rotulo'], ['tipo'], ['tamanho'], ['options'], ['nosort'] e ['show'].

	$campos['precrit']['campo'][1] = "bib_10_id";
	$campos['precrit']['valor'][1] = $bib_10_id; // Neste caso, $bib_10_id deve ser setado assim: $bib_10_id = (empty($_GET['bib_10_id'])) ? $_POST['bib_10_id'] : $_GET['bib_10_id'];

	$campos['nome'][1] = 'xco_10_id';
	$campos['rotulo'][1] = 'ID';
	$campos['tipo'][1] = "text";
	$campos['tamanho'][1] = 5;
	$campos['show'][1] = '"<a href=\'".XOOPS_URL."/modules/".XTC_MOD_DIR."/index.php?id=".$reg->getVar($reg->id)."\' target=\'_blank\'>".$reg->getVar($reg->id)."</a>"';

	$campos['nome'][2] = 'xco_10_idpai';
	$campos['rotulo'][2] = "Exibir Em";
	$campos['tipo'][2] = "select";
	$campos['options'][2] = array(1=>"Teste 1", "dois"=>"Outro Teste", 1983=>"Ano Bom");

	$campos['nome'][3] = 'xco_30_titulo';
	$campos['rotulo'][3] = "Título";
	$campos['tipo'][3] = "text";

	$campos['nome'][4] = 'xco_12_comentarios';
	$campos['rotulo'][4] = _COMMENTS;
	$campos['tipo'][4] = "simnao";

	$campos['nome'][5] = 'xco_30_imagem';
	$campos['rotulo'][5] = "Imagem";
	$campos['tipo'][5] = "text";
	$campos['nosort'][5] = 1;

	-----------------------

	['botoes'] = Array bidimensional contendo botões adicionais para exibir na coluna "Ação".
	Ex.:
	$campos['botoes'][1]['link'] = XOOPS_URL.'/modules/meu_modulo/admin/index.php?op=prioridades';
	$campos['botoes'][1]['imagem'] = XOOPS_URL.'/modules/meu_modulo/images/prioridades.gif';
	$campos['botoes'][1]['texto'] = 'Definir Prioridades';

	$campos['botoes'][2]['link'] = XOOPS_URL.'/modules/meu_modulo/admin/index.php?op=desativar';
	$campos['botoes'][2]['imagem'] = XOOPS_URL.'/modules/meu_modulo/images/desativar.gif';
	$campos['botoes'][2]['texto'] = 'Desativar Cliente';


	-----------------------
	Opções para ['checks'] = 1.
	['group_del'] = 1
	$campos['group_del'] = 1; // Adiciona opção de apagar registros selecionados pelos checkboxes (group_action = group_del).
	$campos['lang']['group_del'] = "Apagar Selecionados"; // Texto exibido no Option
	// Sequência para Options Personalizados:
	$campos['group_action'][1]['texto'] = "Aprovar"; // Texto do Option
	$campos['group_action'][1]['valor'] = "aprova"; // $_POST['group_action'] = 'aprova'
	$campos['group_action'][2]['texto'] = "Reprovar"; // Texto do Option
	$campos['group_action'][2]['valor'] = "reprova"; // $_POST['group_action'] = 'reprova'

	$campos['group_del_function'][1] = 'deletaArquivo'; // Função adicional a ser chamada antes de deletar o registro
	$campos['group_del_function'][2] = 'deletaPermissoes'; // Função adicional a ser chamada antes de deletar o registro

	$campos['lang']['group_action'] = "Ações em Grupo: "; // Texto que precede o select
	$campos['lang']['group_erro_sel'] = "Selecione um registro!"; // Texto de Erro caso o user tente enviar o form sem selecionar nenhum registro
	$campos['lang']['group_del'] = "Apagar Selecionados"; // Texto para o Option group_del. Setar apenas se $campos['group_del'] = 1;
	$campos['lang']['group_del_sure'] = "Deseja Apagar os Registros Selecionados?" // Mensagem de confirmação para exclusão dos registros selecionados
	-----------------------
	*/
	function administracao($url, $campos) {
		$criterio = new CriteriaCompo();
		if(!empty($campos['precrit']['campo']) && !empty($campos['precrit']['valor'])){
			$precrit_hidden = "";
			$precrit_url = "";
			foreach ($campos['precrit']['campo'] as $k=>$v) {
				$criterio->add(new Criteria($v, $campos['precrit']['valor'][$k]));
				$precrit_hidden .= "<input type='hidden' name='".$v."' value='".$campos['precrit']['valor'][$k]."'>";
				$precrit_url .= "&".$v."=".$campos['precrit']['valor'][$k];
			}
		}else{
			$precrit_hidden = "";
			$precrit_url = "";
		}
		if(!empty($campos['checks']) && !empty($_POST['group_action']) && is_array($_POST['checks']) && $_POST['group_action'] == "group_del_ok"){
			$chks = $_POST['checks'];
			$classe = get_class($this);
			foreach ($chks as $k=>$v) {
				$nova = new $classe($k);
				if (!empty($campos['group_del_function']) && is_array($campos['group_del_function'])) {
					foreach ($campos['group_del_function'] as $k=>$v)
					$nova->$v();
				}
				$nova->delete();
			}
		}
		if (!empty($campos['checks']) && !empty($_POST['group_action']) && $_POST['group_action'] == "group_del" && is_array($_POST['checks'])) {
			$chks = $_POST['checks'];
			foreach ($chks as $k=>$v) {
				$hiddens['checks['.$k.']'] = 1;
			}
			$hiddens['op'] = $campos['op'];
			$hiddens['group_action'] = 'group_del_ok';
			return xoops_confirm($hiddens, $url, $campos['lang']['group_del_sure'], _SUBMIT)."<br />";
		}
		$busca_url = '';
		if (!empty($_GET['busca'])) {
			foreach ($_GET['busca'] as $k => $v) {
				if($v != '' && $v != '-1' && in_array($k, $campos['nome'])){
					if(is_numeric($v)){
						$criterio->add(new Criteria($k, $v));
					}else{
						$criterio->add(new Criteria($k, "%$v%",'LIKE'));
					}
					$busca_url .= '&busca['.$k.']='.$v;
				}
			}
		}
		$limit = (!empty($_GET['limit']) && $_GET['limit'] <= 100) ? $_GET['limit'] : 15;
		$criterio->setLimit($limit);
		$start = (empty($_GET['start'])) ? 0 : $_GET['start'];
		$criterio->setStart($start);
		$order = (empty($_GET['order'])) ? 'DESC' : $_GET['order'];
		$criterio->setOrder($order);
		$sort = (!empty($_GET['sort']) && in_array($_GET['sort'], $campos['nome'])) ? $_GET['sort'] : $campos['nome'][1];
		$criterio->setSort($sort);
		$form = (!empty($campos['form'])) ? 1 : 0;
		$checks = (!empty($campos['checks'])) ? 1 : 0;
		$op = (!empty($campos['op'])) ? $campos['op'] : '';
		$norder = ($order == "ASC") ? "DESC" : "ASC";
		$colunas = count($campos['rotulo']);
		$colunas = (!empty($campos['checks'])) ? $colunas + 1 : $colunas;
		$colunas = (!empty($campos['botoes'])) ? $colunas + 1 : $colunas;
		$url_colunas = $url."?op=".$op."&limit=".$limit."&start=".$start.$busca_url.$precrit_url;
		$url_full_pg = $url."?op=".$op."&limit=".$limit."&sort=".$sort."&order=".$order.$busca_url.$precrit_url;
		$contar = $this->contar($criterio);
		$ret = '
		<style type="text/css">
		.hd {background-color: #c2cdd6; padding: 5px; font-weight: bold;}
		tr.bx td {background-color: #DFDFDF; padding: 5px; font-weight: bold; color: #000000}
		tr.hd td {background-image:url("images/bg.gif"); padding: 5px; font-weight: bold; border:1px solid #C0C0C0; color: #000000}
		tr.hd td.hds {background-image:url("images/bgs.gif"); padding: 5px; font-weight: bolder; border:1px solid #C0C0C0; border-top: 1px solid #000000; color: #000000}
		tr.hd td a{color: #1D5F9F}
		.fundo1 {background-color: #DFDFDF; padding: 4px;}
		tr.fundo1 td {background-color: #DFDFDF; padding: 4px; border:1px solid #C0C0C0;}
		.fundo2 {background-color: #E0E8EF; padding: 4px;}
		tr.fundo2 td {background-color: #E0E8EF; padding: 4px; border:1px solid #C0C0C0;}
		.neutro {background-color: #FFFFFF; padding: 4px;}
		tr.neutro td {background-color: #FFFFFF; padding: 4px; border:1px solid #9FD4FF;}
		</style>
		<script language="javascript" type="text/javascript">
	function exibe_esconde(tipo){
   	var d = document;
    var coisinha = d.getElementById(tipo);
   	if (coisinha.style.display == ""){
	     coisinha.style.display = "none";
   }
   else {
      coisinha.style.display = "";
   }
}
function changecheck(){
	var f = document.getElementById("update_form");
	var inputs = document.getElementsByTagName("input");
	for(var t = 0;t < inputs.length;t++){
		if(inputs[t].type == "checkbox" && inputs[t].id != "checkAll"){
		inputs[t].checked = !inputs[t].checked;
		inputs[t].onclick();
		}
	}
	return true;
}'.(($checks) ? '
function verificaChecks(){
var grp_sel = document.getElementById("group_action");
if(grp_sel.options[grp_sel.selectedIndex].value == 0) return true;
var inputs = document.getElementsByTagName("input");
	for(var t = 0;t < inputs.length;t++){
		if(inputs[t].type == "checkbox" && inputs[t].checked == true) return true;
	}
	alert("'.$campos['lang']['group_erro_sel'].'");
	return false;
}
function marcaCheck(linha, ckbx, classe){
var tr = document.getElementById(linha);
var valor = document.getElementById(ckbx).checked;
//alert(tr.onmouseout);
if(valor == true){
tr.className = "neutro";
tr.onmouseout = function(){};
return true;
}else{
tr.className = classe;
tr.onmouseout = function(){this.className=classe};
return true;
}
}
</script>' : "</script>");
		$ret .= '
<table width="100%" border="0" cellspacing="0" class="outer">
<tr><td class="outer" style="background-color: #F3F2F2;"><div style="text-align: center;"><b>'.$campos['lang']['titulo'].'</b>'.((empty($campos['nofilters']))? ' - <a href="javascript:void(0);"  onclick="exibe_esconde(\'busca\');">'.$campos['lang']['filtros'].'</a><br />' : "<br />");
		$ret .= "<form action='".$url."' method='GET' name='form_npag'>".$precrit_hidden."<b>".$campos['lang']['exibir']."&nbsp;&nbsp;<input type='text' name='limit' value='".$limit."' size='4' maxlength='3' style='text-align:center'>&nbsp;&nbsp;".$campos['lang']['por_pagina']."</b>";
		if (!empty($_GET['busca'])) {
			foreach ($_GET['busca'] as $k => $v) {
				if($v != '' && $v != '-1'){
					$ret .= "<input type='hidden' name='busca[".$k."]' value='".$v."'>";
				}
			}
		}
		$ret .= "<input type='hidden' name='op' value='".$op."'><input type='hidden' name='sort' value='".$sort."'><input type='hidden' name='order' value='".$order."'>";
		$ret .= "&nbsp;&nbsp;&nbsp;<input type='image' src='images/envia.gif' style='border:0px; background-color:none' align='absmiddle'></form>";
		$ret .= "<table width='100%' border='0' cellspacing='0'>";
		$ret.= "<tbody><tr><td colspan='".$colunas."' align='right'>".sprintf($campos['lang']['exibindo'], $start+1, ((($start+$limit) < $contar) ? $start+$limit : $contar), $contar)."</td></tr></tbody>";
		$ret .= "<tbody><tr class='hd'>";
		$ret.= ($checks) ? "<td align='center'><input type='checkbox' name='checkAll' id='checkAll' onclick='changecheck();'></td>" : "" ;
		foreach ($campos['rotulo'] as $k => $v) {
			$ret .= "<td nowrap='nowrap' align='center' ".(($sort == $campos['nome'][$k] && empty($campos['nosort'][$k])) ? "class='hds'" : '').">".((empty($campos['nosort'][$k])) ? "<A HREF='".$url_colunas."&sort=".$campos['nome'][$k]."&order=".$norder."'>".$v." ".(($sort == $campos['nome'][$k]) ? "<img src='images/".$order.".gif' align='absmiddle'>" : '')."</a></td>" : $v."</td>");
		}
		$ret.= (!empty($campos['botoes'])) ? "<td align='center'>".$campos['lang']['acao']."</td>" : "";
		$ret .="</tr></tbody>";
		if(empty($campos['nofilters'])){
			$ret.="<form action='".$url."' method='GET' name='form_busca'><tbody><tr id='busca' ".((!empty($_GET['busca'])) ? '' : "style='display:none'")." class='neutro'>";
			$ret.= ($checks) ? "<td>&nbsp;</td>" : "";
			foreach ($campos['rotulo'] as $k => $v) {
				$ret .= "<td align='center'>";
				switch ($campos['tipo'][$k]){
					case "none":
						break;
					case "select":
						$ret.="<select name='busca[".$campos['nome'][$k]."]'><option value='-1'>"._SELECT."</option>";
						foreach ($campos['options'][$k] as $x => $y){
							$ret.="<option value='".$x."'";
							$ret.= (isset($_GET['busca'][$campos['nome'][$k]]) && $_GET['busca'][$campos['nome'][$k]] == $x) ? ' selected="selected"' : '';
							$ret.=">".$y."</option>";
						}
						$ret.="</select>";
						break;
					case "simnao":
						$ret.="<select name='busca[".$campos['nome'][$k]."]'><option value='-1'>"._SELECT."</option>";
						$ret.="<option value='1'";
						$ret.= (isset($_GET['busca'][$campos['nome'][$k]]) && $_GET['busca'][$campos['nome'][$k]] == 1) ? ' selected="selected"' : '';
						$ret.=">"._YES."</option>";
						$ret.="<option value='0'";
						$ret.= (isset($_GET['busca'][$campos['nome'][$k]]) && $_GET['busca'][$campos['nome'][$k]] == 0) ? ' selected="selected"' : '';
						$ret.=">"._NO."</option>";
						$ret.="</select>";
						break;
					case "text":
					default:
						$ret.="<input type='text' name='busca[".$campos['nome'][$k]."]' value='".(isset($_GET['busca'][$campos['nome'][$k]]) ? $_GET['busca'][$campos['nome'][$k]] : '')."' size='".((isset($campos['tamanho'][$k])) ? $campos['tamanho'][$k]: 20)."'/>";
				}
				if (empty($campos['botoes']) && $k == count($campos['rotulo'])) {
					$ret .= " <input type='image' src='images/envia.gif' style='border:0px; background-color:none' align='absmiddle'>";
				}
				$ret .= "</td>";
			}
			$ret.= (!empty($campos['botoes'])) ? "<td align='center'><input type='image' src='images/envia.gif' style='border:0px; background-color:none'></td>" : "";
			$ret.="</tr></tbody>";
			$ret.= $precrit_hidden."<input type='hidden' name='op' value='".$op."'><input type='hidden' name='sort' value='".$sort."'><input type='hidden' name='order' value='".$order."'><input type='hidden' name='limit' value='".$limit."'></form>";
		}
		$registros = $this->PegaTudo($criterio);
		if (!$registros || count($registros) == 0) {
			$ret.= "<tbody><tr><td colspan='".$colunas."'><h2>".$campos['lang']['semresult']."</h2></td></tr></tbody>";
			$ret.="<tbody><tr class='bx'><td colspan='".$colunas."' align='left'>".$this->paginar($url_full_pg,$criterio)."</td></tr></tbody>";
		}else{
			$ret.= ($form || $checks) ? "<form action='".$url."' method='POST' name='update_form' id='update_form' ".(($checks) ? "onsubmit='return verificaChecks()'" : "").">" : '';
			foreach ($registros as $reg) {
				$eod = (!isset($eod) || $eod == "fundo1") ? "fundo2" : "fundo1";
				$ret.= "<tbody><tr id='tr_reg_".$reg->getVar($reg->id)."' class='".$eod."' onmouseover='this.className=\"neutro\";' onmouseout='this.className=\"".$eod."\"'>";
				$ret.= ($checks) ? "<td align='center'><input type='checkbox' name='checks[".$reg->getVar($reg->id)."]' id='checks[".$reg->getVar($reg->id)."]' value='1' onclick='marcaCheck(\"tr_reg_".$reg->getVar($reg->id)."\", \"checks[".$reg->getVar($reg->id)."]\", \"".$eod."\");'></td>" : "" ;
				foreach ($campos['rotulo'] as $l => $f){
					$ret.= "<td>";
					switch ($campos['tipo'][$l]){
						case "none":
							$ret.= (empty($campos['show'][$l])) ? $reg->getVar($campos['nome'][$l]) : eval('return '.$campos["show"][$l].';');
							break;
						case "select":
							if($form && empty($campos['show'][$l])){
								$ret.="<select name='campos[".$reg->getVar($reg->id)."][".$campos['nome'][$l]."]'>";
								foreach ($campos['options'][$l] as $x => $y){
									$ret.="<option value='".$x."'";
									$ret.= ($reg->getVar($campos['nome'][$l]) == $x) ? ' selected="selected"' : '';
									$ret.=">".$y."</option>";
								}
								$ret.="</select>";
							}elseif (!empty($campos['show'][$l])){
								$ret.= eval('return '.$campos["show"][$l].';');
							}else{
								$ret.= 	(isset($campos['options'][$l][$reg->getVar($campos['nome'][$l])])) ? $campos['options'][$l][$reg->getVar($campos['nome'][$l])]:$reg->getVar($campos['nome'][$l]) ;
							}
							break;
						case "simnao":
							if($form && empty($campos['show'][$l])){
								$ret.="<select name='campos[".$reg->getVar($reg->id)."][".$campos['nome'][$l]."]'>";
								$ret.="<option value='1'";
								$ret.= ($reg->getVar($campos['nome'][$l]) == 1) ? ' selected="selected"' : '';
								$ret.=">"._YES."</option>";
								$ret.="<option value='0'";
								$ret.= ($reg->getVar($campos['nome'][$l]) == 0) ? ' selected="selected"' : '';
								$ret.=">"._NO."</option>";
								$ret.="</select>";
							}elseif (!empty($campos['show'][$l])){
								$ret.= eval('return '.$campos["show"][$l].';');
							}else{
								$ret.= ($reg->getVar($campos['nome'][$l]) == 1) ? _YES : (($reg->getVar($campos['nome'][$l]) == 0) ? _NO : $reg->getVar($campos['nome'][$l]));
							}
							break;
						case "text":
						default:
							$ret.= ($form && empty($campos['show'][$l])) ? "<input type='text' name='campos[".$reg->getVar($reg->id)."][".$campos['nome'][$l]."]' value='".$reg->getVar($campos['nome'][$l])."' size='".((isset($campos['tamanho'][$l])) ? $campos['tamanho'][$l]: 20)."'/>" : (!empty($campos['show'][$l]) ? eval('return '.$campos["show"][$l].';'): $reg->getVar($campos['nome'][$l]));
					}

					$ret.="</td>";
				}
				//$ret.= "<td nowrap='nowrap'><a href='".$url."?op=".$op."_editar&".$reg->id."=".$reg->getVar($reg->id)."'><img src='images/editar.gif'></a> <a href='".$url."?op=".$op."_deletar&".$reg->id."=".$reg->getVar($reg->id)."'><img src='images/deletar.gif'></a> ".((!empty($campos['print'])) ? "<a href='".$url."?op=".$op."_imprime&".$reg->id."=".$reg->getVar($reg->id)."' target='_blank'><img src='images/imprime.gif'></a>" : '');
				if(!empty($campos['botoes'])){
					$ret.= "<td nowrap='nowrap'>";
					if (is_array($campos['botoes'])) {
						foreach ($campos['botoes'] as $b) {
							$ret .= "<a href='".$b['link']."&".$reg->id."=".$reg->getVar($reg->id)."' title='".$b['texto']."'><img src='".$b['imagem']."' alt='".$b['texto']."'></a> ";
						}
					}
					$ret.="</td>";
				}
				$ret.="</tr></tbody>";
			}
			if($form || $checks){
				$ret.= "<tbody><tr><td colspan='".$colunas."'>";
				$ret.= $precrit_hidden."<input type='hidden' name='sort' value='".$sort."'><input type='hidden' name='order' value='".$order."'><input type='hidden' name='limit' value='".$limit."'><input type='hidden' name='start' value='".$start."'>";
				if (!empty($_GET['busca'])) {
					foreach ($_GET['busca'] as $k => $v) {
						if($v != '' && $v != '-1'){
							$ret .= "<input type='hidden' name='busca[".$k."]' value='".$v."'>";
						}
					}
				}
				$ret.="<input type='hidden' name='op' value='".$op."'>&nbsp;<br />";
				if($checks){
					$ret .= $campos['lang']['group_action'] . " <select name='group_action' id='group_action'><option value='0'>"._SELECT."</option>";
					$ret .= (!empty($campos['group_del'])) ? "<option value='group_del'>".$campos['lang']['group_del']."</option>" : "";
					if(!empty($campos['group_action'])){
						foreach ($campos['group_action'] as $grp) {
							$ret .= "<option value='".$grp['valor']."'>".$grp['texto']."</option>";
						}
					}
					$ret .= "</select> ";
				}
				$ret.="<input type='submit' value='"._SUBMIT."'><br />&nbsp;</td></tr></tbody></form>";
			}
			$ret.="<tbody><tr class='bx'><td colspan='".$colunas."' align='left'>".$this->paginar($url_full_pg,$criterio)."</td></tr></tbody>";
		}
		$ret.="</table></div></td></tr></table><br />";
		return $ret;
	}

	function contar($criterio=null){
		$sql = 'SELECT COUNT(*) FROM '.$this->tabela;
		if (isset($criterio) && is_subclass_of($criterio, 'criteriaelement')) {
			$sql .= ' '.$criterio->renderWhere();
		}
		$result = $this->db->query($sql);
		if (!$result) {
			return 0;
		}
		list($count) = $this->db->fetchRow($result);
		return $count;
	}

	// Retorna a paginação pronta
	function paginar($link, $criterio=null){
		$ret = '';
		$order = 'ASC';
		$sort = $this->id;
		if (isset($criterio) && is_subclass_of($criterio, 'criteriaelement')) {
			$limit = $criterio->getLimit();
			$start = $criterio->getStart();
			if ($criterio->getSort() != '') {
				$order = $criterio->getOrder();
				$sort = $criterio->getSort();
			}
		}else{
			$limit = 15;
			$start = 0;
		}
		$todos = $this->contar($criterio);
		$total = ($todos % $limit == 0) ? ($todos/$limit) : intval($todos/$limit)+1;
		$pg = ($start) ? intval($start/$limit)+1 : 1;
		$ret.= (!empty($_GET['busca'])) ? "<input type=button value='"._ALL."' onclick=\"document.location= '".$_SERVER['PHP_SELF']."?limit=".$limit."&order=".$order."&sort=".$sort."&op=".$GLOBALS["op"]."'\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		for($i=1;$i<=($total);$i++ )
		{
			$start = $limit * ($i-1);
			if($i == $pg){
				$ret .=  "<span style='font-weight: bold; color: #CF0000; font-size: 15px;'> $i </span>";
			}elseif(($pg - 10) > $i){
				if (!isset($pg1)) {
					$ret .= ("<A HREF='".$link."&start=".$start."'>1</a>. . .");
					$pg1 = true;
				}
				continue;
			}elseif ($i < ($pg + 10)){
				$ret .= (" <A HREF='".$link."&start=".$start."'>".$i."</a> ");
			}else{
				$ret .= (". . . <A HREF='".$link."&start=".(($todos % $limit == 0) ? $todos - $limit : $todos-($todos % $limit))."'>".$total."</a>");
				break;
			}
			if( $i!=$total ){
				$ret .= ("|");
			}
		}
		return $ret;
	}

//  funções abaixo  inseridas  por Claudia 
	/**
	 * Inserida por Claudia A. V. Callegari
	 *  
	 * @param   $antes get_object_vars($objeto) objeto convertido em array
	 * @param  $depois get_object_vars($this)   objeto convertido em array
	 * @param  $lista_campos  array com lista de campos da tabela (deveria ser o titulo do campo )
	 * @return string com a lista do que alterou
	 */

   function veri_alter($antes,$depois,$lista_campos) {
     
      for($i=0;$i<=count($lista_campos);$i++) {
//         $vr_antes=trim($antes[$lista_campos[$i]['campo']]);
//         $vr_depois=trim($depois[$lista_campos[$i]['campo']]);
		$vr_antes=stripslashes(trim($antes['vars'][$lista_campos[$i]['campo']]['value']  ));
        $vr_depois=stripslashes(trim($depois['cleanVars'][$lista_campos[$i]['campo']]));
              
        
         if($vr_antes!=$vr_depois)
            $obs.="\n ".$lista_campos[$i]['descri']." alterou de :$vr_antes para: $vr_depois";
      }
      
      // inserido as duas linhas abaixo, para não dar erro quando tem apóstrole no conteúdo
      $obs=stripslashes($obs);
      $obs=addslashes($obs);
      
      return $obs ;
   }
   

   
   /**
 * Verificar se o valor $x é inteiro ou não
 *
 * @param unknown_type $x
 * @return unknown
 */
	
function myIsInt($x) {
   return ( is_numeric ($x ) ?  intval(0+$x ) ==  $x  :  false );
}
   
  
/**
 *  verificar valores digitados em forms
 *  $par=1, checa o valor , retornando falso ou verdadeiro
 *  $par=2 , checa o valor  e retorna o valor corrigido
 *
 * @param unknown_type $valor
 * @param unknown_type $par
 * @param unknown_type $men
 * @return unknown
 */

function checa_val($valor,$par=1) {

  if (((substr_count($valor,".")>0 and  substr_count($valor,",")>0))  or
          (substr_count($valor,".")>1) or  (substr_count($valor,",")>1)  ) {
      if($par==1)
         return false;
         
   }
   $valor= str_replace(",",".",$valor);
 if(!is_numeric($valor)) {
    return false;
  }

    if($par==1)
       return true;
    else
       return $valor;
}
 /**
  * converte data no formato YYYY-MM-AA  para formato unixtime
  *
  * @param string  $data
  * @return int  - data formato unixtime
  */
  
function data2u($data){

	$a= ereg('([0-9]{4})([-.\/])([0-9]{2})([-.\/])([0-9]{2})',$data,$datadiv);
	if(!$a) {
		return false;
	}
	$dia=$datadiv[5]; $mes=$datadiv[3] ; $ano=$datadiv[1];
	if(checkdate($mes,$dia,$ano)){
		$data2=mktime(0,0,0,$mes,$dia,$ano);
		return $data2;
	}else{
		return false;
	}

}
  



}
?>