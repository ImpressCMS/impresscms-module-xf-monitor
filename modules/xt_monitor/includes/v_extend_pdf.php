<?php
/* Matriz  $TituloReport deve ser definida no script anterior
  $TituloReport['titulo'] ->  Conter cabeçalho principal a ser exibido no início da página
  $TituloReport['tot_col'][1] -> total de colunas a serem impressas 
  $TituloReport['tot_col'][2] -> total de colunas a serem impressas no cabeçalho
                                 se for vazio pegará do $TituloReport['tot_col'][1]

  tit_colX -> onde o X será um número iniciando com 1 representando as colunas do relatório
  $TituloReport['sintetico'] ->   1 - sintético  (não imprime linha detalhe)

                              
  $TituloReport['tit_colX'][1] ->  cabeçalho da coluna X
  $TituloReport['tit_colX'][2]  ->  tamanho (largura) da coluna X na medida definida (padrão mm)
  $TituloReport['tit_colX'][3]  -> conteúdo da coluna X
  $TituloReport['tit_colX'][4]  -> indica para somar a coluna 0- não  1 - sim
  $TituloReport['tit_colX'][5]  ->  quando somar guardar soma do grupo1
  $TituloReport['tit_colX'][6]  ->  quando somar guardar soma geral

  $TituloReport['tit_colX'][7]  ->  quando somar tema para total do grupo1
  $TituloReport['tit_colX'][8]  ->  quando somar tema para total  geral    


  $TituloReport['tit_colX'][9]  ->  indicará se usará cell ou multicell no relatório
                                    0 -> default usará cell
                                    1-> usará multicell
                                    
                                    13/10/2005  DESATIVADA , não da certo definir em cada coluna
                                    É necessário definir para o relatório como um todo, usar 
                                     $TituloReport['multicell'] = 1 ou 0
                                                                                               
                                    
                                    
  $TituloReport['tit_colX'][10]  ->  indicará o ln da função $pdf->cell

                                   0: to the right
                                   1: to the beginning of the next line
                                   2: below

  $alt_col -> altura da coluna na unidade definida (padrão mm)  
  
  $TituloReport['footer']  1  indica para não imprimi-lo
  $TituloReport['header']  1  indica para não imprimi-lo    
  $TituloReport['grupo1'][1]= X;   // indica a coluna que controla o subgrupo de cabeçalho
                                       // 0 indica que não terá subgrupo
  $TituloReport['grupo1'][2]= X  // indica se irá totalizar o grupo1 ou não

  $TituloReport['saltagrupo1'] 1; // indica para saltar de página no grupo1

  $TituloReport['temsoma']  1 indica que tem alguma coluna solicitando soma
                            0  não tem nehuma coluna solicitando soma
                            calculado automaticamente na função  detalhe

  $TituloReport['multicell'] 1 - indica que uma das colunas pode ter mais de uma linha e a função irá gerenciar isso
                             0 - indica que não tem multilinhas em nehuma coluna 
                            
                            */

// inserido após separar do comuns

define('FPDF_FONTPATH','font/');
  require_once("fpdf.php");
//

class PDF extends FPDF
{

	var $widths;
	var $aligns;

	
	//Page header
function Header()
{
    global  $TituloReport,$setfont_head;
    if ($TituloReport["header"]!=1) {
    //Logo
    //    $this->Image('logocobal.jpeg',10,8,33);
    $this->SetFont('Arial','B',9);
    $this->Cell(0,10,$TituloReport['titulo'],1,1,'C');
    $this->ln(5);
    //$this->SetFont('Arial','B',8);   foi parâmetrizado
    
    $this->SetFont($setfont_head['family'],$setfont_head['style'],$setfont_head['size']);
    if (empty($TituloReport['tot_col'][2]))
        $TituloReport['tot_col'][2]=$TituloReport['tot_col'][1];

//     for ($i=1;$i<=$TituloReport['tot_col'][2];$i++)
 //       $this->Cell($TituloReport['tit_col'.$i][2],6,$TituloReport['tit_col'.$i][1],1,0,'C');
//    $this->Cell($TituloReport['tit_col'.$i][2],6,$TituloReport['tit_col'.$i][1],1,0,'C');

        $multicell= $TituloReport['multicell']==1 ;
  		if($multicell) {
			$array_widths=array();
			$array_dados=array();
			$array_aligns=array();

			for ($c2=1;$c2<=$TituloReport['tot_col'][2];$c2++) {
				$array_aligns[]='C' ;
				$array_widths[]=$TituloReport['tit_col'.$c2][2];
				$array_dados[]= $TituloReport['tit_col'.$c2][1];
			}
			$this->SetAligns($array_aligns);
			$this->SetWidths($array_widths);
			$this->Row($array_dados,5);
		} else {
           for ($i=1;$i<=$TituloReport['tot_col'][2];$i++) {
              $this->Cell($TituloReport['tit_col'.$i][2],6,$TituloReport['tit_col'.$i][1],1,0,'C');
           }
        }


    //Line break
    $this->Ln(7);
    }
}
 
//Page footer
function Footer()
{
   global  $TituloReport;
   if ($TituloReport["footer"]!=1) {  
    
        //Position at 1.5 cm from bottom
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
 }
}

function detalhe($alt_col=4,$totaliza=0,$borda='LR'){
    global  $TituloReport ,$mat_formata;
    // $totaliza  0 -não  1- sim  indica que acabou o relatorio, deve totalizar o grupo e geral
    if($totaliza) {
       // grupo1
       if( $TituloReport['grupo1'][2]==1) {
          $this->imprime_total(5,$alt_col,$TituloReport);
       }
       $this->ln($alt_col);
       $this->imprime_total(6,$alt_col,$TituloReport,8);          
       return ;
    }


    // $grupo1 pega o conteúdo da coluna  definida em  $TituloReport['grupo1'][1]
    static $grupo1; 
    static $primeira;
    if(!isset($primeira) ) {
       $primeira=true;
       // trecho executa so na primeira vez
       // identificar se existe alguma coluna que pede totalização
       $TituloReport['temsoma']==0;
        for ($c=1;$c<=$TituloReport['tot_col'][1];$c++) {
                if( $TituloReport['tit_col'.$c][4]==1) {
                    $TituloReport['temsoma']=1;
                    break;
                }
        }

    }
    if ( $TituloReport['grupo1'][1]==0 or  $grupo1==$TituloReport['tit_col'.$TituloReport['grupo1'][1]][3] ){
     for ($c=1;$c<=$TituloReport['tot_col'][1];$c++) {
        $multicell= $TituloReport['multicell']==1 ; 
     	$conteudo=$TituloReport['tit_col'.$c][3];

        if($TituloReport['tit_col'.$c][4]==1 and !$multicell ) { // deve somar
           $TituloReport['tit_col'.$c][5]+=$conteudo ;        
           $TituloReport['tit_col'.$c][6]+=$conteudo ;

        }
        // linha detalhe
        // se for multicell, entrar em for para usar função especial para multicell e sair deste for
        
		if($multicell) {
			$array_widths=array();
			$array_dados=array();
			$array_aligns=array();
			
			for ($c2=1;$c2<=$TituloReport['tot_col'][1];$c2++) {
				$array_aligns[]=$mat_formata[$c2][1]   ;
				$array_widths[]=$TituloReport['tit_col'.$c2][2];
				$conteudo=$TituloReport['tit_col'.$c2][3];
				if(substr($mat_formata[$c2][3],0,1)=='V' ) {       
                   $decimal=substr($mat_formata[$c2][3],1,1);
       			   $conteudo=number_format($conteudo,$decimal,',','.');
                }        
				
				$array_dados[]= $conteudo;
				if($TituloReport['tit_col'.$c2][4]==1) { // deve somar
					 $conteudo=$TituloReport['tit_col'.$c2][3];
					$TituloReport['tit_col'.$c2][5]+=$conteudo ;
					$TituloReport['tit_col'.$c2][6]+=$conteudo ;
				}
	
				
			}
			$this->SetAligns($array_aligns);
			$this->SetWidths($array_widths);
			$this->Row($array_dados,$alt_col);
			break;	
		}  
         
        
        if(!isset($TituloReport['sintetico'])  or $TituloReport['sintetico']!=1 ) {
          $this->imprime_linha($conteudo,$alt_col,$borda,$c);
        }
        
      }
       if(!isset($TituloReport['sintetico'])  or $TituloReport['sintetico']!=1 ) {
         $this->ln($alt_col);
       }

      $grupo1=$TituloReport['tit_col'.$TituloReport['grupo1'][1]][3];    

    }
    else {
       if( !$primeira  and   $TituloReport['temsoma']==1 ) {
          // imprimir total do grupo
            if( $TituloReport['grupo1'][2]==1) {
               $this->imprime_total(5,$alt_col,$TituloReport);
            }
            for ($c=1;$c<=$TituloReport['tot_col'][1];$c++) {
                 $TituloReport['tit_col'.$c][5]=0.00;
            }

          $this->ln($alt_col);
          // fim da impressão do total
       }
       $primeira=false;
     if ($TituloReport['saltagrupo1']==1 ) {
        $this->AddPage();
     }       
     $grupo1=$TituloReport['tit_col'.$TituloReport['grupo1'][1]][3];    
     if($primeira) {
        $this->header_grupo2($alt_col);
     }
     $this->header_grupo1($alt_col);

    }

 } // fecha função

 
function detalhe_NAOFUNCIONA($alt_col=4,$qry){
// Problemas:  1- a variavel $str é avaliada no inicio do for e não altera o valor
// deixando todos as colunas com o mesmo valor da primeira
//             2- quando a expressão que irá em $str for complexa ou dois campos concatenados
//               complica muito a sintaxe e não consegui fazer funcionar

    global  $TituloReport ;
   for ($i=0;$i<=$qry->nrw;$i++) {
      $qry->navega($i);
      for ($c=1;$c<=$TituloReport['tot_col'][1];$c++) {
//        unset($str);
        eval ('$str = "{' . $TituloReport['tit_col'.$c][3] . '}";');
        $this->Cell($TituloReport['tit_col'.$c][2],$alt_col,$str,0,0,'C');
      }
      $this->ln($alt_col);
   }
   $this->Output();
}

function deta_teste($alt_col=4,$qry) {
   global $TituloReport ;
//   $i=0; 
  for ($i=0;$i<=$qry->nrw;$i++) {
     $qry->navega($i);
     for ($c=1;$c<=$TituloReport['tot_col'][1];$c++) {
        eval ('$str = "{' . $TituloReport['tit_col'.$c][3] . '}";');
        echo ($str);
        echo ('<br>');
     }
  }
}

function header_grupo1($alt_col){
     global $TituloReport;
      if ($TituloReport['grupo1'][1]>0 ) {
      if(($TituloReport['grupo1'][2]==1 and $TituloReport['sintetico']) or (!$TituloReport['sintetico']) ) {
      
        $this->ln();
        // imprime titulo da coluna e conteúdo (ref. coluna do grupo1)
        $this->MultiCell( $TituloReport['tit_col'.$TituloReport['grupo1'][1]][2] ,$alt_col,$TituloReport['tit_col'.$TituloReport['grupo1'][1]][1]  .$TituloReport['tit_col'.$TituloReport['grupo1'][1]][3],1,1);
        $this->ln();
      }
        $this->detalhe($alt_col);
    }
} // fecha função 

function header_grupo2($alt_col){
     global $TituloReport;
      if ($TituloReport['grupo2'][1]>0) {
        $this->ln();
        // imprime titulo da coluna e conteúdo (ref. coluna do grupo1)
        $this->MultiCell( $TituloReport['tit_col'.$TituloReport['grupo2'][1]][2] ,$alt_col,$TituloReport['tit_col'.$TituloReport['grupo2'][1]][1]  .$TituloReport['tit_col'.$TituloReport['grupo2'][1]][3],1,1);
//        $this->ln();
//        $this->detalhe($alt_col);
    }
} // fecha função





function imprime_total($col,$alt_col,$TituloReport,$col2=7) {
     // imprimir total do grupo
     // $col = coluna que contém a totalização
     //$col2 = quando não imprime a totalização imprime o conteúdo da coluna $col2
     //   objetivo  mostrar descrição da totalização
	if($alt_col<4)
	   $alt_col=4;
     
     
     if ($TituloReport['temsoma']==1   ) {
          $this->ln($alt_col);
          for ($c=1;$c<=$TituloReport['tot_col'][2];$c++) {
             if($TituloReport['tit_col'.$c][4]==1)
                $this->imprime_linha($TituloReport['tit_col'.$c][$col],$alt_col,1,$c);
              else
                $this->imprime_linha($TituloReport['tit_col'.$c][$col2],$alt_col,1,$c);
          }
      }
}

function imprime_linha($conteudo,$alt_col,$borda=0,$c) {
    global $mat_formata,$TituloReport;
    if(substr($mat_formata[$c][3],0,1)=='V' ) {       
       $decimal=substr($mat_formata[$c][3],1,1);
       $conteudo=number_format($conteudo,$decimal,',','.');
    }        
  //  $multicell= $TituloReport['tit_col'.$c][9]==1 ;
    // definir var se pula ou não $TituloReport['tit_col'.$c][10]
    $ln= $TituloReport['tit_col'.$c][10];
 //   if(!$multicell ) {
       $this->Cell($TituloReport['tit_col'.$c][2],$alt_col,$conteudo,$borda,$ln,$mat_formata[$c][1],$mat_formata[$c][2]);
   // }else {
     //  $this->MultiCell($TituloReport['tit_col'.$c][2],$alt_col,$conteudo,$borda,$mat_formata[$c][1],$mat_formata[$c][2]);
   // }

}

// funções inclusas em  11/10/2005 para imprimir colunas  com multilinhas
function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data,$alt_col)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    //$h=$alt_col*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        $this->MultiCell($w,5,$data[$i],0,$a);
    //    $this->MultiCell($w,$alt_col,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln(($h-5)+$alt_col)  ;
    
}

function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}



}  // fecha classe
?>