<?php
/**
 * functions.php 
 * autor: Claudia Antonini Vitiello Callegari   claudia.avcallegari@gmail.com
 *
 * @param unknown_type $totcol
 * @return unknown
 */


    function gera_mat_formata($totcol=0) {
    // monta matriz de foramata??o com valores default  das colunas de relatorios 
    // usada na fun??o de pdf. v_extend_pdf.php -> detalhe
    // coluna 1 align  ($pdf->cell) ou $pdf->multicell
    // coluna 2 fill  0 ou 1    ($pdf->cell)
    // coluna 3 formata??o de valores (uso proprio) "V1"- indica valor com 1 casa decimal
    //                                              "V2" - valor com 2 casas decimais e assim por diante
    //      ser? usado fun??o number_format para formata-lo
    // se necess?rio  criar ou padr?es, os quais devem ser reconhecidos  na fun??o que a utilizar?
    // no caso  v_extend_pdf.php  fun??o  detalhe()

     for($i=1;$i<$totcol;$i++) {
        $mat_formata[$i][1]='L';
        $mat_formata[$i][2]=0;
        $mat_formata[$i][3]="";             
     }
     return $mat_formata;
             
    }                   


  Function GrvLog($LogArqnome="", $writeStr="") {
    //  $LogArqnome=  nome do arquivo a ser gravado
    // $writeStr=  string a ser gravada no final do arquivo


    if ($LogArqnome=="" or  $writeStr=="") {
       echo "Falta parametros para gravar arquivo de log ";
       return false;
    }

    if ($LogHand = @fopen($LogArqnome, 'a+')) {;

        if(!ereg("\n$",$writeStr) and  !ereg("^\n",$writeStr) ) {
        	$writeStr .= "\n";
        }
        fputs($LogHand,$writeStr);
        fclose($LogHand);
        return true;
    } else {
      echo "<script>alert('Não foi possível criar o arquivo de log $LogArqnome, verifique permissões com o  administrador da rede. ');</script>";

    }

}

   
 /**
 	function browserx
 
      //fun??o gen?rica para exibir browse de consulta de registros
      // $sql  => sql para executar
      // $tiporet -> indica tipo de retorno  'T' = tela , echo (default) 'P' Impressora (pdf)
      //    $tiporet = 'C' -> arquivos  CVS  implementado em 16/10/2006
      //    $tiporet = 'X' -> arquivos  XLS (excel)  implementado em 17/10/2006
      
      //$mat_campo  deve ser matriz com os campos a serem exibidos , titulos, links, etc
      // mat_campos[$i]['titulo']
      // mat_campos[$i]['campo']
      // mat_campos[$i]['link']
      // mat_campos[$i]['align']
      // mat_campos[$i]['soma']  = 0 ou 1  ==>  0 não soma  1  soma
      // mat_campos[$i]['larg'] =  largura da coluna para relatorio em pdf
      // mat_campos[$i]['extra_td'] = clausula extra para tag td
      //            exemplo: ' NOWRAP  style="font-size: 7pt"  '

      // mat_campos[$i]['extra_td_grupo1'] = clausula extra para tag td do total do grupo 1
      //            exemplo: ' NOWRAP  style="font-size: 7pt"  '

      // $mat_campos[$i]['extra_td_geral'] = clausula extra para tag td do total geral
      //            exemplo: ' NOWRAP  style="font-size: 7pt"  '

      //  $mat_campos[$i]['ln'] ->  indicar? o ln da fun??o $pdf->cell
     //                              0: to the right
//                                   1: to the beginning of the next line
//                                   2: below
//
      
      // $mat_campos[$i]['totalgeral'] = descri??o para coluna total geral
      // $mat_campos[$i]['totalgrupo1'] = descri??o para coluna total do grupo1
      

		$mat_campos[$i]['textarea']) = Indica para inserir uma textarea na td tipo readonly
									   receber os atributos separados por # 
      								 Sendo linha,coluna,extra onde extra pode ser um style por exemplo
      								 exemplo "3#50#style='border:1'"	
      
      
      
      
      //$col_grupo1=99  => indica a coluna que controla o grupo1, 99 não ter? grupo
      //$totcol_imp=0   => total de colunas a serem impressas, usado qdo. tiver grupo
                        // v?lido para pdf, para html, subtrair 1, gerando $totcol_imph
      
      //$tot_grupo1=0   => 0 ou 1  indica se totalizar? o grupo1
      //$salta_grupo1   =>  0 ou 1  indica se saltar? de p?gina por grupo (rel. pdf)


      //$setfont => matriz para fun??o fpdf->SetFont
      //       default  array("family"=>"arial", "style"=>"", "size"=>"8"

      // $multicell ->  0 (n?o) ou 1 (sim) Indica se usar? func?o multicell no pdf
      //                se for 0 (default)  usar?  fun??o cell

      // $width_table -> default '100%'. Indica o tamanho da tabela , quando gerar na Tela  $tiporet='T'
      					 pode ser informado em pixels . Exemplo  '450px'
      
         $men_p_page  ->  indica quantos registros mostrar por p?gina.. default = 30
      					 
		$orientation  -> orienta??o do papel  para pdf     'P' -> Portrait  'L' -> Landscape 
		$paper -> para pdf  tamanho do papel     A3  A4   A5   Letter  Legal 

		
		$setfont_head  -> Define fonte para o cabeçalho das coluna
						  array("family"=>"arial", "style"=>"", "size"=>"8") 
						
		$alt_col  -> Define a altura da linha . Quando multicell sera a altura que separa uma linha da outra
         
		$extra_relpdf -> String com conjunto de comandos para inserir no final do relatório em pdf
			Exemplo: $extra_relpdf='$pdf->Ln(3);$pdf->MultiCell(0,5,\'Testando informarção extra no final do rel \',1,"C"); ';   		
				
		
		$delimiter=';'  Delimitador para gerar arquivo tipo csv ,  quando $tiporet='C'
		
		$grava_cabeca=1  Quando $tiporet='C' (arquivo csv) indica se irá gravar o primeiro registros com os titulos dos campos			
			
		// exemplo de como definir o link:
//      $linkar=xoops_getenv('PHP_SELF')."?opt=enviar2";
//      $mat_campos[1]['link']='$link=$linkar."&codigoplu=".$cat_data["codigoplu"]."&loja=".$cat_data["loja"] ;';

// exemplo para colocar tag title no link (href). podendo colocar campos adicionais
// importante observar:  inserir  \' e após  title=\'
$mat_campos[2]['link']= '$link=$linkar."&cpf=".$cat_data["cpf"]." \' title=\' Usuário:".$cat_data["usuario"]." Lançado em:".conv_data2_3($cat_data["dt_mov"])." ".$cat_data["hora"] ; ';
   ou
$mat_campos[$col]['link'] ='$link="#  \' title=\' Dias na fase " ; ';
 
 
     // exemplo da defini??o dos campos
      $mat_campos[0]['campo']= '$cat_data["id_comp"] '  ;
      $mat_campos[1]['campo']= '$cat_data[\'nome\'] '  ;
      $mat_campos[2]['campo']= 'date("d/m/y",$cat_data["dt_venc"])';
      $mat_campos[3]['campo']= '$cat_data["qtd"]';
      $mat_campos[4]['campo']= '$cat_data["estc03embc"]';
      $mat_campos[5]['campo']= 'date("d/m/y",$cat_data["dt_digi"])';
      $mat_campos[6]['campo']= '$cat_data["codigoplu"]';
      $mat_campos[7]['campo']= '$cat_data["forc10apel"]';
      $mat_campos[8]['campo']= '$cat_data["loja"]';
      $mat_campos[9]['campo']= '($cat_data["provid"]==1 ? "S" :"N")';

      *** matriz de apoio
      $tot_coluna=array();
      $tot_coluna[$i]['geral'] ==> totalizar geral  a coluna $i
      $tot_coluna[$i]['grupo1'] ==> totalizar o grupo 1 da coluna $i


//      $retorno='';

//   Observa??o:  Pagina??o testada quando vari?veis passadas via  get,  para via post
//                necess?rio  testar e adaptar....
 */
 
   function browserx($sql='',$mat_campos,$tema='',$linkar='',$tiporet='T',$sintetico=0,$col_grupo1=99,$totcol_imp=0,$tot_grupo1=0,$salta_grupo1=0,$setfont=array("family"=>"arial", "style"=>"", "size"=>"8") ,$multicell=0,$totcol_cab=0, $width_table='100%',$men_p_page=30,$orientation='P',$paper='Letter',$setfont_head=array("family"=>"arial", "style"=>"", "size"=>"8"),$alt_col=4,$extra_relpdf="",$delimiter=';',$grava_cabeca=1) {
         global $xoopsDB;
         global $TituloReport,    $mat_formata,$setfont_head;

             
    if(!isset($retorno))
 	   $retorno='';

      if(empty($sql)) {
         error_sai("Sql não definida (function browserx) ");
      }
      if(count($mat_campos)==0) {
          error_sai("Matriz de campos não definida (function browserx) ");
      }
      // definir total de colunas a serem impressas, caso não tenha sido passado
       if($totcol_imp==0)  {
          $totcol_imp=count($mat_campos);
      }
      if(empty($totcol_cab))
         $totcol_cab=  $totcol_imp;
       
                  
      $result=$xoopsDB->queryf($sql);
       if(!$result) {
			error_sai("Erro na consulta  $sql <br> ".$xoopsDB->error());
        }

       if($tiporet=='T') {
//           $men_p_page=30;
           $userstart = isset($_GET['userstart']) ? intval($_GET['userstart']) : 0;
           $userfim = $userstart+$men_p_page;
           $usercount =  $xoopsDB->getRowsNum($result);
//           $arg= implode("",array_filter($_SERVER['argv'],  );
           $arg=elimina_parm('userstart');
           $nav = new XoopsPageNav($usercount, $men_p_page, $userstart, "userstart", $arg);

           
          $retorno.="<div style=\"width: $width_table\"> ";
           
        $retorno.="<table border='1' width='98%' cellspacing='1'  cellpadding='1' align='center'  class='outer'>";
        $retorno.="<tr class='itemHead' align='center'><td colspan='".$totcol_imp."'><b>$tema</b></td></tr>";
        $retorno.="<tr  class='head' >   ";
        for($i=0;$i<$totcol_cab;$i++) {
            $retorno.="<td align='center'><b>".$mat_campos[$i]['titulo']."</b>  </td> ";
        }
        $retorno.="</tr>";
       // definir matriz para totaliza??o
       $tot_coluna=array();
        for($i=0;$i<$totcol_imp;$i++) {
            $tot_coluna[$i]['grupo1']=0.0;
            $tot_coluna[$i]['geral']=0.0;
        }
       
      $inicio_rel=true;
      // titulo do grupo1 , se houver
      
      
      if(isset($mat_campos[$col_grupo1])) {
         $comando='$titulo_grupo1="'.$mat_campos[$col_grupo1]['titulo'].'"; ' ;
         eval($comando);
         //grvlog('upload/testecla.txt',$comando."\n");     
      }

      $grupo1='aslkalsdflksfd@#$@#$@#$@#$'; // definido com um valor maluco, para não coincidir de haver grupo com este conte?do
      										
        $l=0;
        while ($cat_data = $xoopsDB->fetcharray($result)) {
              $mostra_totalgrupo1=0;
              $mostra_grupo1=0;

              if($col_grupo1<99  ) {
              	   $comando='$campo_grupo1='.$mat_campos[$col_grupo1]['campo'].'; ' ;
                   //grvlog('upload/testecla.txt',$comando);
                   //chmod('upload/testecla.txt',0777);
                   eval($comando);
                   //if(isset($grupo1) and   $grupo1!=$campo_grupo1) {
                   if( $grupo1!=$campo_grupo1) {
                      // *** totalizar o grupo
                      if(!$inicio_rel) {
                         // **** imprimir total do grupo1
                           if($tem_soma and $tot_grupo1) {
                              $mostra_totalgrupo1=1;
                              //$retorno.=browserx_totgrupo1($mat_campos,$tot_coluna) ;
                            }
                           // **** zerar total do grupo
                           if( ! ($l>=$userstart  and $l<$userfim)) {  // zerar so se não for exibir
                              for($i=0;$i<$totcol_imp;$i++) {
                                if($mat_campos[$i]['soma']) {
                                   $tot_coluna[$i]['grupo1']=0;
                                }
                              }
                            }
                     } else {
                       $inicio_rel=false;
                     }
                      $comando='$grupo1='.$mat_campos[$col_grupo1]['campo'].'; ' ;
                      //grvlog('upload/testecla',$comando);
                      
                      eval($comando);
                      // ****mostrar grupo1
                      $mostra_grupo1=1;
                     // $retorno.="<tr class='Head'   >\n";
                     // $retorno.="<td colspan=".$totcol_imp  ."  ><b>$titulo_grupo1 : $grupo1</b></td?\></tr> ";
                   }
                }


           if($l>=$userstart  and $l<$userfim) {
                if($mostra_totalgrupo1) {
                    $retorno.=browserx_totgrupo1($mat_campos,$tot_coluna,$totcol_imp) ;
                    // *** zerar total do grupo1
                    for($i=0;$i<$totcol_imp;$i++) {
                        if($mat_campos[$i]['soma']) {
                           $tot_coluna[$i]['grupo1']=0;
                        }
                    }
                }
               
                
                if($mostra_grupo1) {
                      $retorno.="<tr class='Head'   >\n";
                      $retorno.="<td colspan=".$totcol_imp  ."  ><b>$titulo_grupo1  $grupo1</b></td?\></tr> ";
                }

                if(!$sintetico) {
                
    		    if(($l%2)==0) {
    				$retorno.="<tr class='even' >\n";
    		    }else {
    			    $retorno.="<tr class='odd' >\n";
    			}
                for($i=0;$i<$totcol_imp;$i++) {
                	$def_align=isset($mat_campos[$i]['align']) ? $mat_campos[$i]['align'] :'' ;
                	$def_extra_td= isset($mat_campos[$i]['extra_td']) ? $mat_campos[$i]['extra_td'] : ''; 
                	

                   $retorno.="<td  align='".$def_align ."'".$def_extra_td ." >";
                   if(!empty($mat_campos[$i]['link'])) {
                      $link1=$mat_campos[$i]["link"];

                     //grvlog('upload/testecla.txt',$link1);
                      eval($link1);
                      $retorno.= "<a href='$link'>";
                   }
                //   $campo='$retorno.='.$mat_campos[$i]['campo'].';';
           //        $campo2=$$campo;  // não funcionou
                  //grvlog('../uploads/testecla.txt',$campo);
                   $campo='$conteudo='.$mat_campos[$i]['campo'].';';
                   eval($campo);
					if(substr($mat_formata[$i+1][3],0,1)=='V' ) {       
    	            	$decimal=substr($mat_formata[$i+1][3],1,1);
       			   		$conteudo=number_format($conteudo,$decimal,',','.');
                	}     
                   
                   if($mat_campos[$i]['textarea']){
                   	    $array_textarea=explode('#',$mat_campos[$i]['textarea']);
                   	    $row_textarea=$array_textarea[0];
                   	    $col_textarea=$array_textarea[1];
                   	    $extra_textarea=$array_textarea[2];
						$retorno.="<textarea cols=$col_textarea  rows=$row_textarea $extra_textarea  readonly >$conteudo</textarea>";                   	
                   	
                   }else{
                   	  $retorno.=$conteudo;                  	
                   }
                   
                   
                   if(!empty($mat_campos[$i]['link'])) {
                      $retorno.="</a>";
                   }
                   $retorno.="</td>";
                   if( isset($mat_campos[$i]['ln']) and  $mat_campos[$i]['ln']==1) {
                      $retorno.="</tr>";
                      if(($l%2)==0) {
                         $retorno.="<tr class='even' >\n";
           		       }else {
              		     $retorno.="<tr class='odd' >\n";
        	    	   }
                   }

                }   // fecha for
                $retorno.="</tr>";
                }// fecha if !$sintetico
                
            } // fecha if
            $l++;
            // totalizar
            for($i=0;$i<$totcol_imp;$i++) {
                if( isset($mat_campos[$i]['soma']) and   $mat_campos[$i]['soma']) {
                   $comando='$valor_col='.$mat_campos[$i]['campo'].'; ' ;
                    eval($comando);
                    $tot_coluna[$i]['geral']+=floatval($valor_col);
                    $tot_coluna[$i]['grupo1']+=floatval($valor_col);
                    $tem_soma=1;
                }
            }

    } // fecha while
    // total geral , so se estiver na ?ltima p?gina
     if( $userfim  >=$usercount) {
         if(isset($tem_soma) and $tem_soma) {
            // totalizar grupo
            if($tot_grupo1) {
               $retorno.=browserx_totgrupo1($mat_campos,$tot_coluna,$totcol_imp) ;
            }
            $retorno.="<tr class='itemHead'   >\n";
            for($i=0;$i<$totcol_imp;$i++) {
               // mostrar titulo total geral
               if($mat_campos[$i]['totalgeral']) {
                  $retorno.="<td  align='".$mat_campos[$i]['align']."' >".$mat_campos[$i]['totalgeral']."</td>"   ;
               }else  {
                   // mostrar total
                   	if($mat_campos[$i]['soma']){
	                   	$conteudo=$tot_coluna[$i]["geral"];
                   		if(substr($mat_formata[$i+1][3],0,1)=='V' ) {       
    	               		$decimal=substr($mat_formata[$i+1][3],1,1);
       			   			$conteudo=number_format($conteudo,$decimal,',','.');
                		}     
                    	
                       $retorno.="<td  ".$mat_campos[$i]['extra_td_geral']. "  align='".$mat_campos[$i]['align']."' >". $conteudo . "</td>"   ;
                    }else{
                       $retorno.="<td> </td>";
                    }
               }

            }
            $retorno.="</tr>";
        }
      }
        $retorno.="</table>";
        $retorno.="</div > ";
        $retorno.="<p align='center' >".$nav->renderNav(4)." </p>" ;
        $retorno.=" <br> <b>Total de Registros: ".$xoopsDB->getRowsNum($result)."</b>" ;

        }
        if($tiporet=='T') {
           echo $retorno;
        }

        if($tiporet=='P') {
           require_once('v_extend_pdf.php');

       // montar matriz TituloReport a ser usada em v_extend_pdf, definindo dados do cabe?alho do relat?rio

        $TituloReport['sintetico']=$sintetico;
        $TituloReport['titulo']=' Emissão:'.date("d/m/Y H:i:s",time()).'   '.$tema   ;
        $TituloReport['tot_col'][1]=$totcol_imp ;   // total de colunas
        $TituloReport['tot_col'][2]=$totcol_cab ;   // total de colunas p/ cabe?alho

        // subgrupo
        $TituloReport['grupo1'][1]=$col_grupo1+1 ;  // indica a coluna que controla o subgrupo de cabe?alho
                                       // 0 indica que não ter? subgrupo
                                       
        $TituloReport['grupo1'][2]= $tot_grupo1 ;// somar grupo1
        $TituloReport['saltagrupo1']=$salta_grupo1 ; // indica para saltar de p?gina no grupo1
        //  fim subgrupo

	  $TituloReport['multicell']=$multicell;
      if(!isset($mat_formata)){
         $mat_formata=gera_mat_formata($TituloReport['tot_col'][1]);	
      }

	  for ($i=0;$i<count($mat_campos);$i++) {
           $it=$i+1 ;
           // verificar fun??o  gera_mat_formata para mais detalhes
          // coluna 1 align  ($pdf->cell) ou $pdf->multicell
          // coluna 2 fill  0 ou 1    ($pdf->cell)
          // coluna 3 formata??o de valores (uso proprio) "V1"- indica valor com 1 casa decimal
          //                                              "V2" - valor com 2 casas decimais e assim por diante
           $mat_formata[$it][1]=align_html_to_pdf($mat_campos[$i]['align']);
           $TituloReport['tit_col'.$it][1]=$mat_campos[$i]['titulo'] ;
            if(isset($mat_campos[$i]['larg']))
               $TituloReport['tit_col'.$it][2]=$mat_campos[$i]['larg'] ;
            else
               $TituloReport['tit_col'.$it][2]=10;


            $TituloReport['tit_col'.$it][4]=$mat_campos[$i]['soma'] ;
            $TituloReport['tit_col'.$it][8]=$mat_campos[$i]['totalgeral'];
            $TituloReport['tit_col'.$it][7]=$mat_campos[$i]['totalgrupo1'] ;
            $TituloReport['tit_col'.$it][10]=$mat_campos[$i]['ln'] ;

        }

        //$pdf=new PDF('P','mm','Letter');
		$pdf=new PDF($orientation,'mm',$paper);        
        $pdf->Open();
        $pdf->AliasNbPages();
        if ($TituloReport['grupo1'][1]==0 or $TituloReport['saltagrupo1']!=1 )   // não usar? totaliza??o de grupo
           $pdf->AddPage();  //ser? chamado na fun??o detalhe

        //$pdf->SetFont($setfont['family'],$setfont['style'],$setfont['size']);
        //$alt_col=4;  // altura da coluna    passada no parâmetro

        while ($cat_data = $xoopsDB->fetcharray($result)) {
            // linha detalhe
           for ($i=0;$i<count($mat_campos);$i++) {
               $it=$i+1;
               $campo='$TituloReport["tit_col'.$it.'"][3]= '.$mat_campos[$i]['campo'].';';
               eval($campo);
//               grvlog('teste-pdf.txt',$campo);
           }
           $pdf->SetFont($setfont['family'],$setfont['style'],$setfont['size']);
           $pdf->detalhe($alt_col);
              // fim da linha detalhe
        }

//          ob_end_flush();
//          var_dump($TituloReport);
//          return;
         $pdf->detalhe($alt_col,1);
         $pdf->Ln(7);
         $pdf->Cell(0,6,'Total de Registros: '.$xoopsDB->getRowsNum($result),1,1);
        
       //  grvlog('upload/testecla.txt','veja extra_relpdf '.$extra_relpdf);
       
       
       //ob_end_flush();
         eval($extra_relpdf);         
//       return;  
         
         
         $pdf->Output();

         } // fecha if =='P'

         
         if($tiporet=='C'){
         	// arquivos tipo CVS (texto) separados pelo $delimiter
         	
         	$l=0;
         	$conteudo_arq="";
         	// gravar 1a linha de cabeçalho

         	if($grava_cabeca){
			$primeiro=1;
         	for($i=0;$i<$totcol_imp;$i++) {
                   $campo='$conteudo="'.$mat_campos[$i]['titulo'].'";';
//					grvlog('upload/testecla.txt',$campo);
                   eval($campo);

                   $conteudo_arq.= ($primeiro ?  '' : $delimiter ); $primeiro=0;
                   $conteudo_arq.=$conteudo;
	         }
			$conteudo_arq.="\n";
         	}
         	while ($cat_data = $xoopsDB->fetcharray($result)) {
				$primeiro=1;
         		for($i=0;$i<$totcol_imp;$i++) {
					if($primeiro){
				      $primeiro=0;
			 		}else{
						$conteudo_arq.=$delimiter;
					}
         			$campo='$conteudo='.$mat_campos[$i]['campo'].';';
                    eval($campo);
                    $conteudo_arq.=$conteudo;
	         	}
				$conteudo_arq.="\n";
         	}
			if(empty($conteudo_arq)){
				error_sai('Não ha dados para gerar o arquivo ');				
			}
         	
			if(headers_sent()){
				error_sai('Alguns dados ja foram enviados ao browser, não é possível enviar o arquivo ');
			}
			/*
         	// gravar arquivo
         	$filename = round(time() / 10 * rand(1,10));
         	$arq_csv=XOOPS_ROOT_PATH.'/cache/'.$filename.'.csv';
         	if(!grvlog($arq_csv,$conteudo_arq)){
         		error_sai("Erro na gravação do arquivo $arq_csv");         		
         	}
         	
        	 $tamanho = filesize($arq_csv);
               //$nome=basename($arq);
            $nome='doc.csv';
//               header("Content-type: Application/unknown");
 			ob_end_clean();
			header("Content-type: Application/save");
               header("Content-length: $tamanho");
               header("Content-Disposition: attachment; filename=$nome");
               header("Content-Description: PHP Generated Data");

              if(readfile($arq_csv)){
    //              header("Location: $HTTP_REFERER");
               } else {
                  error_sai("Ocorreu um erro ao carregar o arquivo $arq_csv ");
               }
               
               unlink($arq_csv);
               
			*/
               
            // teste sem gravar o arquivo 			/*
         	// gravar arquivo
         	
	
         	 $tamanho = strlen($conteudo_arq);
            $nome='doc.csv';
 			ob_end_clean();
			header("Content-type: Application/save");
            header("Content-length: $tamanho");
            header("Content-Disposition: attachment; filename=$nome");
            header("Content-Description: PHP Generated Data");
            print $conteudo_arq;
               
         }
          
         
         if($tiporet=='X'){ // arquivo do tipo xls (excel)
         	$excel= new GeraExcel();
         	$excel->GeraExcel();
			// linha 0 com titulos dos campos
         	for($i=0;$i<$totcol_imp;$i++) {
         		$campo='$conteudo="'.$mat_campos[$i]['titulo'].'";';
         		eval($campo);
         		$excel->MontaConteudo(0,$i,$conteudo);
         	}
         	$j=1;
         	while ($cat_data = $xoopsDB->fetcharray($result)) {
         		for($i=0;$i<$totcol_imp;$i++) {
         			$campo='$conteudo='.$mat_campos[$i]['campo'].';';
         			eval($campo);
         			$excel->MontaConteudo($j,$i,$conteudo);
         		}
         		$j++;
         		$conteudo_arq.="\n";
         	}
			ob_end_clean();
         	$excel->GeraArquivo();
         }
              
   }// fecha function


  
  function browserx_totgrupo1($mat_campos,$tot_coluna,$totcol_imp) {
   // function usada na function browserx,  para totalizar grupo1
   global $mat_formata;
    $resultado="</tr>";
    $resultado.="<tr class='Head' >\n";
    //for($i=0;$i<$totcol_cab;$i++) {
	for($i=0;$i<$totcol_imp;$i++) {    	
       // mostrar titulo total grupo1
       if( isset($mat_campos[$i]['totalgrupo1']) and  $mat_campos[$i]['totalgrupo1']) {
          $resultado.="<td   align='".$mat_campos[$i]['align']."' >".$mat_campos[$i]['totalgrupo1']."</td>"   ;
       }else  {
           // mostrar total
			$def_extra_td_grupo1 =isset($mat_campos[$i]['extra_td_grupo1']) ? $mat_campos[$i]['extra_td_grupo1'] : '';
          
           if(isset($mat_campos[$i]['soma']) and   $mat_campos[$i]['soma']){
            	$conteudo=$tot_coluna[$i]["grupo1"];
                if(substr($mat_formata[$i+1][3],0,1)=='V' ) {       
    	        	$decimal=substr($mat_formata[$i+1][3],1,1);
       			   	$conteudo=number_format($conteudo,$decimal,',','.');
                }     
               $resultado.="<td ".$def_extra_td_grupo1 ." align='".$mat_campos[$i]['align']."' > ".$conteudo . "</td>"   ;
           }else {
               $resultado.="<td> </td>";
           }
       }

    }
    $resultado.="</tr>";

    return $resultado;
  
  }



function align_html_to_pdf($var='') {
 // Objetivo:  receber parametro de alinhamento em html  e converter para parametro de alinhamento
 // classe  fpd conforme abaixo:
 //    L or empty string: left align (default value)
//    C: center
//    R: right align
//  J : =justify

 if(empty($var))
    return '';
     // valores da classe fpdf

  return strtoupper(substr($var,0,1));
} // fecha function


function elimina_parm($param) {
 // Eliminar $param de  ($_SERVER['argv'])
 // retorna uma string
 // ?til para gerar string em barra de navega??o
   global $_SERVER;

//   echo "<pre>";
//   print_r($_SERVER);
//   print_r($GLOBALS);
//   
//   echo "</pre>";
   
   $linha='';

/*   for($i=0;$i<$_SERVER['argc'];$i++) {
   	$linha.=$_SERVER['argv'][$i].'+';
   }

   $arg2=explode("&",$linha );
   for($i=0;$i<count($arg2);$i++) {
   	if(ereg("^$param",$arg2[$i]))  {
   		array_splice($arg2, $i ,1);
   		$i=0;
   	}
   }
*/

 
   	$linha.=$_SERVER['QUERY_STRING'];
  

   $arg2=explode("&",$linha );
   for($i=0;$i<count($arg2);$i++) {
   	if(ereg("^$param",$arg2[$i]))  {
   		array_splice($arg2, $i ,1);
   		$i=0;
   	}
   }
 return implode("&",$arg2);

 }

 function testa_conexao($url,$porta=80) {
    global $timeout_sock;
    $abre_conex = fsockopen($url,$porta,$erro_numero,$erro_string,$timeout_sock);
    if ($abre_conex){
       fclose($abre_conex);
       return true;
    }else {
       return false;
    }
 }

   
function error_sai($mensagem=''){
	$mensagem=htmlspecialchars($mensagem);
	$mensagem=nl2br($mensagem);
	$mensagem=str_replace(array("\r\n", "\n\r", "\n", "\r"), "", $mensagem);

	$mensagem=addslashes($mensagem);
	
	//var_dump($mensagem);
	
	//if($_GET['saida']=='P') {
		ob_end_clean();
	//}		

	echo "<script>
		top.consoleRef=window.open('','myconsole',
  'width=350,height=400'
   +',menubar=0'
   +',toolbar=0'
   +',status=0'
   +',scrollbars=1'
   +',resizable=1')
 top.consoleRef.document.writeln(
  '<html><head><title>Erro</title></head>'
   +'<body bgcolor=white onLoad=\"self.focus()\">'
   +'<h4>Erro:</h4>$mensagem'
   +'</body></html>'
 )
 top.consoleRef.document.close()
		window.history.go(-1);
		</script>";
		
		exit();
	
}


/**
 * Creditos a DzaiaCuck - dzaiacuck@ig.com.br 
 * Rubens A. Monteiro - unplu@hotmail.com 20/09/05
 *
 */
class  GeraExcel{

	// define parametros(init)
	function  GeraExcel(){
		$this->armazena_dados   = ""; // Armazena dados para imprimir(temporario)
		$this->ExcelStart();
	}// fim constructor


	// Monta cabecalho do arquivo(tipo xls)
	function ExcelStart(){

		//inicio do cabecalho do arquivo
		$this->armazena_dados = pack( "vvvvvv", 0x809, 0x08, 0x00,0x10, 0x0, 0x0 );
	}

	// Fim do arquivo excel
	function FechaArquivo(){
		$this->armazena_dados .= pack( "vv", 0x0A, 0x00);
	}


	// monta conteudo
	function MontaConteudo( $excel_linha, $excel_coluna, $value){

		$tamanho = strlen( $value );
		$this->armazena_dados .= pack( "v*", 0x0204, 8 + $tamanho, $excel_linha, $excel_coluna, 0x00, $tamanho );
		$this->armazena_dados .= $value;
	}//Fim, monta Col/Lin

	// Gera arquivo(xls)
	function GeraArquivo(){

		//Fecha arquivo(xls)
		$this->FechaArquivo();
		header("Content-type: application/zip");
		//header("Content-type: text/x-comma-separated-values");
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header("Content-disposition: inline; filename=excel.xls");
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header("Pragma: public");
		print  ( $this->armazena_dados);
	}// fecha funcao
	# Fim da classe que gera excel
}
	
	function conv_data($data,$hora=0,$min=0) {
		$a= ereg('([0-9]{2})([-.\/])([0-9]{2})([-.\/])([0-9]{4})',$data,$datadiv);
		if(!$a) {
//			$a= ereg('([0-9]{2})([-.\/])([0-9]{2})([-.\/])([0-9]{2})',$data,$datadiv);
//            if(!$a)
               return false;
//            else
//    			$datadiv[5]= (int) $datadiv[5]+2000;  // pode dar errado se data for <2000
		}
		return mktime($hora,$min,0,$datadiv[3],$datadiv[1],$datadiv[5]);
	}

	/**
	 * Retornar objeto criteria, com filtro de lista de modulos que o user
	 * tem acesso como administrador.
	 * Se estiver no grupo de admin, retorar null
	 *
	 */
	
	

	function get_criterio_mid(){
		global $xoopsUser,$xoopsDB;
		// pegar a lista dos grupos que o user está
		$gruposdouser=$xoopsUser->getGroups();

		// se o user não for admin do site, pegar a lista de modulos que ele é admin
		// para passar como criteria  no getList

		if(in_array(1,$gruposdouser)){
			// é administrador do site
			$criterio=null;
		}else{
			$sql='select gperm_itemid from '.$xoopsDB->prefix('group_permission').'
       where gperm_name="module_admin" and gperm_groupid in '. '('.implode(',',$gruposdouser).')';

			$result=$xoopsDB->queryf($sql);

			$array_mid=array();
			if(!$result){
				error_sai('Erro no banco ');
				return;
			}else {
				while($cat_data=$xoopsDB->fetcharray($result)){
					$array_mid[]=$cat_data['gperm_itemid'];
				}
				$criterio= new Criteria('mid','('.implode(',',$array_mid).')','IN')	;
			}
		}

		return $criterio;
		
	}

	function entre_aspas($valor){
		return '"'.$valor.'"';	
	}

	
?>