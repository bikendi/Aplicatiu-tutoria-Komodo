<?
/*
    Aplicatiu Tutoria Komodo v.0.1
    Aplicació web per a la gestió de la tasca tutorial.
    Copyright (C) 2002-2007  Artur Guillamet Sabaté <aguillam(a)xtec.net>
    Copyright (C) 2012 ßingen Eguzkitza <beguzkit@xtec.cat>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as
    published by the Free Software Foundation, either version 3 of the
    License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

@include("linkbd.inc.php");
$pda='';
@include("comu.php");
@include("comu.js.php");
panyacces("Privilegis");
$maxpaginador=12;
if(isset($tots)) $maxpaginador=10000;

print("<html><head><title>Tutoria</title>");
if(isset($assign) && $assign!='' and (isset($grup) or isset($subgrup))) {
  $quantitatpost=count($HTTP_POST_VARS);
  for($i=0; $i<$quantitatpost; ++$i) {
    $key=key($HTTP_POST_VARS);
    $noms=split('_', $key);
    if($noms[0]=='not') {
      if($noms[1]==0) { 
        if(current($HTTP_POST_VARS)!='') { 
	  $memo="";
	  $aux="t_0_".$noms[2]."_".$noms[3]."_0";
	  eval("\$memo=\$$aux;");
	  $aux1=explode(' ',$assign);
      $codassign=$aux1[0];
	  $consulta="insert into $bdtutoria.$tbl_prefix"."notes SET ref_aval='".$noms[3]."', ref_alum='".$noms[2]."', ref_credit='$codassign', valor='".current($HTTP_POST_VARS)."', usuari='$sess_user', memo='".$memo."'";
	  mysql_query($consulta, $connect);
	}
      }
      else { 
        if(current($HTTP_POST_VARS)!=$noms[4]) {
	  if(current($HTTP_POST_VARS)=="") { 
	    $consulta="delete from $bdtutoria.$tbl_prefix"."notes where id='".$noms[1]."' LIMIT 1";
	    mysql_query($consulta, $connect);
	  }
	  else { 
	    $consulta="update $bdtutoria.$tbl_prefix"."notes SET valor='".current($HTTP_POST_VARS)."', usuari='$sess_user' WHERE id='".$noms[1]."' LIMIT 1";
	    mysql_query($consulta, $connect);
	  }
	}
      }
    }
    if($noms[0]=='t' and $noms[1]!='0') {
      $memo=current($HTTP_POST_VARS);
      $sum=0;
      for($j=0;$j<strlen($memo);++$j) {
        $sum+=(ord(substr($memo,$j,1))*($j+1));
      }
      if ($sum!=intval($noms[4])) {
        $consulta="update $bdtutoria.$tbl_prefix"."notes SET memo='".$memo."' WHERE id='".$noms[1]."' LIMIT 1";
	mysql_query($consulta, $connect);
      }
    }
    next($HTTP_POST_VARS);
  }
}

print("</head><body bgcolor='#ccdd88' text='#000000' leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>");
print("<center><a href='menu_pda.php?pda=&idsess=$idsess'>Tornar</a> &nbsp; &nbsp; Usuari: <font color='#0000ff'>$sess_nomreal</font></center>");


print("
<form name='introd1' method='post' action='$PHP_SELF?pda=&idsess=$idsess'>
<div align='right'>
<table border='0'>
<tr>
<td align='left'>");
if ( (isset($grup)&&$grup=='Subgrups') || (isset($subgrup)&&$subgrup!='Grups')) {
   print("Subgrup: <select width='10' name='subgrup' onChange='if(document.introd1.paginadoranterior) document.introd1.paginadoranterior.value=\"-1\"; if(document.introd1.paginadorseguent) document.introd1.paginadorseguent.value=\"-1\"; document.introd1.submit();'>
   <option></option><option>Grups</option>");
   do {
     $permis=privilegis('X', 'X',current($llista_subgrups))||privilegis('Y', 'Y',current($llista_subgrups));
     if($permis) print("<option".((stripslashes($subgrup)==rawurldecode(current($llista_subgrups)))?" selected":"").">".rawurldecode(current($llista_subgrups))."</option>");
   } while(next($llista_subgrups));
   if($grup=='Subgrups') $grup='';
}
else {
   print("Grup: <select name='grup' onChange='if(document.introd1.paginadoranterior) document.introd1.paginadoranterior.value=\"-1\"; if(document.introd1.paginadorseguent) document.introd1.paginadorseguent.value=\"-1\"; document.introd1.submit();'>
   <option></option><option>Subgrups</option>");
   do {
	   $permis=privilegis('X', 'X',current($llista_grups))||privilegis('Y', 'Y',current($llista_grups));
	   if($permis) print("<option".(($grup==current($llista_grups))?" selected":"").">".current($llista_grups)."</option>");
   } while(next($llista_grups));
   if($subgrup=='Grups') $subgrup='';
}
print("</select>");

if((isset($grup)&&$grup!="") || (isset($subgrup)&&$subgrup!="")) {
  print("<br>Assign./Credit: <select name='assign' onChange='document.introd1.submit();'><option></option>");
  if(isset($grup)&&$grup!="") $aux=rawurlencode($grup);
  else $aux=rawurlencode($subgrup);
  $consulta="SELECT DISTINCT l.codi, l.nomcredit FROM $bdtutoria.$tbl_prefix"."llistacredits l, $bdtutoria.$tbl_prefix"."horariprofs h WHERE l.codi=h.assign and h.grup like '%$aux%'";
  if(preg_match("/Administrador/", $sess_privilegis)) $permis=true; else $permis=false;
  if(!$permis) $consulta.=" and h.idprof='$sess_user'";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $hihaassignselected=false;
  while($fila=mysql_fetch_row($conjunt_resultant)) {
	  if($assign=="$fila[0] ".rawurldecode($fila[1])) $hihaassignselected=true;
	  print("<option".(($assign=="$fila[0] ".rawurldecode($fila[1]))?" selected":"").">$fila[0] ".rawurldecode($fila[1])."</option>");	
  }
  print("</select>");
}
if(((isset($grup)&&$grup!="") || (isset($subgrup)&&$subgrup!=""))&& isset($assign) && $assign!="" && $hihaassignselected) {
  print("<br>Avaluació:  <select name='aval' onChange='document.introd1.submit();'><option></option>");
  $consulta="select refaval, nomaval from $bdtutoria.$tbl_prefix"."avaluacions"; // where modificable='si'";
  $conjunt_resultant=mysql_query($consulta, $connect);
  while($fila=mysql_fetch_row($conjunt_resultant)) {
      print("<option".(($aval=="$fila[0] ".rawurldecode($fila[1]))?" selected":"").">$fila[0] ".rawurldecode($fila[1])."</option>");	
  }
  print("</select>");
}
print("</td></tr></table></div><hr>");


if ($grup!="" || $subgrup!="") {

  $paginadoractual=0;
  if(!isset($paginadoranterior)) $paginadoranterior=-1;
  if(!isset($paginadorseguent)) $paginadorseguent=-1;
  print("<input type='hidden' name='paginadorseguent' value='$paginadorseguent'>");
  print("<input type='hidden' name='paginadoranterior' value='$paginadoranterior'>");
  if($grup!='') {
   $gru=split(' ',$grup);
   $consulta="SELECT count(*) FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE curs='".$gru[0]."' AND grup='".$gru[1]."' AND pla_estudi='".$gru[2]."'";
   $conjunt_resultant=mysql_query($consulta, $connect);
   $nregs=mysql_result($conjunt_resultant, 0,0);
   mysql_free_result($conjunt_resultant);
  }
  else {
   $subgru=split(' ',$subgrup);
   $consulta="SELECT alumnes FROM $bdtutoria.$tbl_prefix"."subgrups WHERE ref_subgrup='$subgru[0]' limit 1";
   $conjunt_resultant=mysql_query($consulta, $connect);
   $alssubgrup=split(',',mysql_result($conjunt_resultant, 0,0));
   if(''==mysql_result($conjunt_resultant, 0,0)) $nregs=0; 
   else $nregs=count($alssubgrup);
   mysql_free_result($conjunt_resultant);
  }
  
  if($nregs>$maxpaginador) {
   if($paginadorseguent!=-1) {
     $paginadoractual=$paginadorseguent;
   }
   if($paginadoranterior!=-1) {
     $paginadoractual=$paginadoranterior;
   }
  }
  if(($paginadoractual-$maxpaginador) >= 0) $paginadorenrere=true; else $paginadorenrere=false;
  if(($paginadoractual+$maxpaginador) < $nregs) $paginadorendavant=true; else $paginadorendavant=false;
  $paginador = ($paginadorenrere)?"<a href='' onClick='document.introd1.paginadorseguent.value=\"-1\"; document.introd1.paginadoranterior.value=\"0\"; document.introd1.submit(); return false;'>":"";
  $paginador.= "<<";
  $paginador.= ($paginadorenrere)?"</a>":"";
  $paginador.= "&nbsp; ";
  $paginador.= ($paginadorenrere)?"<a href='' onClick='document.introd1.paginadorseguent.value=\"-1\"; document.introd1.paginadoranterior.value=\"".($paginadoractual-$maxpaginador)."\"; document.introd1.submit(); return false;'>":"";
  $paginador.= "<";
  $paginador.= ($paginadorenrere)?"</a>":"";
  $paginador.= "&nbsp; &nbsp; ".(($nregs!=0)?($paginadoractual+1):0)." - ".((($paginadoractual+$maxpaginador)<=$nregs)?($paginadoractual+$maxpaginador):$nregs)."&nbsp; &nbsp; ";
  $paginador.= ($paginadorendavant)?"<a href='' onClick='document.introd1.paginadoranterior.value=\"-1\"; document.introd1.paginadorseguent.value=\"".($paginadoractual+$maxpaginador)."\"; document.introd1.submit(); return false;'>":"";
  $paginador.= ">";
  $paginador.= ($paginadorendavant)?"</a>":"";
  $paginador.= "&nbsp; ";
  $paginadorsup=(($maxpaginador*(floor($nregs/$maxpaginador))));
  if($paginadorsup==$nregs) $paginadorsup=$nregs-1;
  if(($nregs%$maxpaginador)==0) $paginadorsup=$nregs-$maxpaginador;
  $paginador.= ($paginadorendavant)?"<a href='' onClick='document.introd1.paginadoranterior.value=\"".$paginadorsup."\"; document.introd1.submit(); return false;'>":"";
  $paginador.= ">>";
  $paginador.= ($paginadorendavant)?"</a>":"";
  $paginador.= "&nbsp; de $nregs";
  if($nregs!=0) print("<input type='submit' value='Gravar'>&nbsp; $paginador <input type='checkbox' name='tots'".((isset($tots))?" checked":"")." onClick='document.introd1.submit();'> Tots");
  else print("Aquest subgrup no t&eacute; alumnes.");
  if($nregs!=0) {
    if($grup!='') {
      $gru=split(' ',$grup);
      $consulta="SELECT numero_mat, concat(cognom_alu,' ',cognom2_al,', ',nom_alum), curs, grup, pla_estudi FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE curs='".$gru[0]."' AND grup='".$gru[1]."' AND pla_estudi='".$gru[2]."' ORDER BY cognom_alu, cognom2_al ASC LIMIT $paginadoractual,$maxpaginador";
    }
    else {
     $consulta ="SELECT numero_mat, concat(cognom_alu,' ',cognom2_al,', ',nom_alum), curs, grup, pla_estudi ";
     $consulta.="FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE ";
     $cons='';
     foreach($alssubgrup as $nal) {
       if ($cons!='') $cons.='or ';
       $cons.="numero_mat='$nal' ";
     }
     $consulta.= $cons;
     $consulta.="ORDER BY cognom_alu, cognom2_al ASC ";
     $consulta.="LIMIT $paginadoractual,$maxpaginador";
    }
    $conjunt_resultant=mysql_query($consulta, $connect);
  
    print("<table border='0'>");

    $consulta1="SELECT DISTINCT n.ref_aval, a.nomaval ";
    $consulta1.="FROM $bdtutoria.$tbl_prefix"."notes n, $bdtutoria.$tbl_prefix"."avaluacions a WHERE n.ref_aval=a.refaval and (";
    $aux1="";
    while($fila=mysql_fetch_row($conjunt_resultant)) {
      $aux1.=(($aux1!="")?" or ":"")."n.ref_alum='$fila[0]'";
    }
    $conjunt_resultant=mysql_query($consulta, $connect); 
    $consulta1.=$aux1.") ";
    $aux1=explode(' ',$assign);
    $codassign=$aux1[0];
    $consulta1.="and n.ref_credit='$codassign' ";
  
    $consulta1.="ORDER BY a.data asc";
    $capcal="<tr bgcolor='#0088cc'><td>&nbsp;</td><td align='right'><b>Avaluaci&oacute;:</b>&nbsp;</td>";
    $avals=array();
    $conjunt_resultant1=mysql_query($consulta1, $connect);
    while($fila1=mysql_fetch_row($conjunt_resultant1)) {
    }
    if($aval!='' && $assign!='' && $hihaassignselected) {
    	$aux=explode(' ', $aval);
    	$refavaluacionova=$aux[0];
    	$trobat=false;
    	for($a=0; $a<count($avals); ++$a) {
  	  	if($avals[$a]==$refavaluacionova) $trobat=true;
    	}
      if(!$trobat) {
	      
	      	$consulta2="select nomitems, nomaval, data from $bdtutoria.$tbl_prefix"."avaluacions where refaval='$refavaluacionova' limit 1";
	    	$conjunt_resultant2=mysql_query($consulta2, $connect);
	    	$fila2=mysql_fetch_row($conjunt_resultant2);
	    	$aux=explode('|', $fila2[0]);	    
    		$capcal .="<td align='center' title='".$nomDiaSem[date('w',$fila2[2])].", ".date('j-n-Y',$fila2[2])." - ".rawurldecode($fila2[1])."'><b>".$codassign."<br>".$refavaluacionova."</b>";
			if($fila2[0]!='') {
				$capcal .= "<table border='0' width='100%'><tr>";
				for($i=0; $i<count($aux); ++$i) {
					$au=explode('->', $aux[$i]);
					$capcal.=("<td align='center' title='$au[1]'>$au[0]</td>");	
				}		
				$capcal .= "</tr></table>";
			}
			mysql_free_result($conjunt_resultant2);    		
    		
    		$capcal.="</td>";
      	$avals[]=$refavaluacionova;
  	  }
    }
    $capcal .="</tr>";
    mysql_free_result($conjunt_resultant1);

    $compt_capcal=0;
    while ($fila=mysql_fetch_row($conjunt_resultant)) {
      if($compt_capcal%5==0) print($capcal);
      ++$compt_capcal;
      if(file_exists("$dirfotos/$fila[0].jpg")) $foto = "./foto.php?idsess=$idsess&foto=$fila[0]";
      else $foto = "./imatges/fot0.jpg";
      $linkfil="<a href='$foto'><img src='$foto' width='25' height='34' border='0'></a>";
      print("<tr bgcolor='#aacccc'><td>".$linkfil."</td><td>".$fila[1]);
      if($subgrup!='') {
       print(" <font size='-2'>($fila[2] $fila[3] $fila[4])</font>");
      }
      print("</td>");
      for($i=0; $i<count($avals); ++$i) {
        $idreg=0;
        $nota='-1';
        $memo="";
        $consulta1="SELECT id, valor, memo FROM $bdtutoria.$tbl_prefix"."notes WHERE ref_alum='".$fila[0]."' AND ref_aval='".$avals[$i]."' AND ref_credit='".$codassign."' limit 1";
        $conjunt_resultant1=mysql_query($consulta1, $connect);
        $nfiles=mysql_num_rows($conjunt_resultant1);
        if ($nfiles==1) {
         $fila1=mysql_fetch_row($conjunt_resultant1);
         $idreg=$fila1[0];
         $nota=$fila1[1];
         $memo=$fila1[2];
        }
        mysql_free_result($conjunt_resultant1);
        print("<td align='center'>");
        
        
  	  $consulta1="select modificable, pla_estudi, valors, curs, grup, nitems from $bdtutoria.$tbl_prefix"."avaluacions where refaval='$avals[$i]' limit 1";
  	  $conjunt_resultant1=mysql_query($consulta1, $connect);
  	  $numitems=mysql_result($conjunt_resultant1, 0,5);
  	  if(mysql_result($conjunt_resultant1, 0,0)=='si') $modificable=true; else $modificable=false;
  	  if (preg_match('/'. $fila[4] .'/', mysql_result($conjunt_resultant1, 0,1)) ||mysql_result($conjunt_resultant1, 0,1)=='Tots') $etapaOK=true; else $etapaOK=false;
  	  if (preg_match('/'. $fila[2] .'/', mysql_result($conjunt_resultant1, 0,3)) ||mysql_result($conjunt_resultant1, 0,3)=='Tots') $cursOK=true; else $cursOK=false;
       if (preg_match('/'. $fila[3] .'/', mysql_result($conjunt_resultant1, 0,4)) ||mysql_result($conjunt_resultant1, 0,4)=='Tots') $grupOK=true; else $grupOK=false;
       if($etapaOK && $cursOK && $grupOK) {
	  	if ($nota=='-1') $nota='';
	  	$not=explode('z',$nota);
  	    if($modificable) {
		  $vals=mysql_result($conjunt_resultant1, 0,2);
		  $val=explode('|',$vals);
		  print("<input type='hidden' name='not_".$idreg."_".$fila[0]."_".$avals[$i]."_".$nota."' value='$nota'>");
	  	  print("<table border='0' width='100%'><tr>");
	  	  for($j=0; $j<$numitems; ++$j) {
  	      	print("<td align='center'><select name='not".$j."_".$idreg."_".$fila[0]."_".$avals[$i]."_".$nota."' onChange='var aux=\"\"; for(var i=0; i<$numitems; ++i) {aux+=((aux!=\"\"||i!=0)?\"z\":\"\")+eval(\"document.forms.introd1.not\"+i+\"_".$idreg."_$fila[0]_$avals[$i]_$nota.options[document.forms.introd1.not\"+i+\"_".$idreg."_$fila[0]_$avals[$i]_$nota.selectedIndex].text\");} if(aux==aux.match(/z+/g)) aux=\"\"; document.forms.introd1.not_".$idreg."_$fila[0]_$avals[$i]_$nota.value=aux;'>");
          	print("<option></option>");
		  	foreach($val as $valors) print("<option".(($not[$j]==$valors)?" selected":"").">$valors</option>");   
          	print("</select></td>");
      	  }
  	      print("</tr></table>");
	    } else {
		    print("<table border='0' width='100%'><tr>");
		    for($j=0; $j<$numitems; ++$j) {
		     if($not[$j]!='') print("<td align='center'>".(($j==($numitems-1))?"<b>":"").((($not[$j]=='I'||$not[$j]=='1'||$not[$j]=='2'||$not[$j]=='3'||$not[$j]=='4')&&$j==($numitems-1))?"<font color='#ff0000'>":"")."$not[$j]".((($not[$j]=='I'||$not[$j]=='1'||$not[$j]=='2'||$not[$j]=='3'||$not[$j]=='4')&&$j==($numitems-1))?"<font color='#000000'>":"").(($j==($numitems-1))?"</b>":"")."</td>");
		     else print("<td>&nbsp;</td>");
	    	}
			if($nota=='') print("<td>&nbsp;</td>");
		  	print("</tr></table>");   
	    }
  	    mysql_free_result($conjunt_resultant1);  	  

  	    $sum=0;
        for($j=0;$j<strlen($memo);++$j) {
          $sum+=(ord(substr($memo,$j,1))*($j+1));
        }
        print("<input type='hidden' name='t_".$idreg."_".$fila[0]."_".$avals[$i]."_".$sum."' value='".$memo."'>");
  	    if($modificable) {
          print("<a href='' title='Text explicatiu' onClick='javascript:var pr=prompt(\"Introdueix el text explicatiu:\",unescape(document.introd1.t_".$idreg."_".$fila[0]."_".$avals[$i]."_".$sum.".value)); if(pr!=null) document.introd1.t_".$idreg."_".$fila[0]."_".$avals[$i]."_".$sum.".value=escape(pr); return false;'>".(($memo!="")?"<b>":"")."T".(($memo!="")?"</b>":"")."</a>");
        }
        else {
	      if($nota!='') print("<a href='' title='Text explicatiu' onClick='alert(unescape(document.introd1.t_".$idreg."_".$fila[0]."_".$avals[$i]."_".$sum.".value)); return false;'>".(($memo!="")?"<b>":"")."T".(($memo!="")?"</b>":"")."</a>");
	      else print("&nbsp;");	
        }
      } else print("&nbsp;");
      print("</td>");
      }
      print("</tr>");
    }
    mysql_free_result($conjunt_resultant);
    print("</table>");
  }
  if($nregs!=0) print("<input type='submit' value='Gravar'>&nbsp; ".$paginador);
  print("</form><hr>");
}



print("</body></html>");
?>