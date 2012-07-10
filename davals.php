<?php
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
?>
<html>
<head>
<title>Tutoria</title>
<?
@include("linkbd.inc.php");
@include("comu.php");
@include("comu.js.php");
panyacces("Administrador");
?>

<script language='JavaScript'>
function editaNotes(aval)
{
 var finestra;
 window.focus();
 opt = "status=0,resizable=1,scrollbars=1,width=500,height=600,left=15,top=20";
 finestra=window.open("editanotes.php?idsess=<?=$idsess?>&aval="+aval, "", opt);
 finestra.focus();
}
function calendariEscriuDia(di, i, mes, any) {
 var cad;
 if(di=='Avui') {
   var avui= new Date(<?print(1000*mktime(date('H'),date('i'),date('s'),date('n'),date('j'),date('Y'),-1));?>);
   di="<?print($nomDiaSem[date('w')]);?>";
   if(di!='Ds' && di!='Dg') cad="<a href='' onClick='document.eaval.editaavaluaciodata.value=\""+di+", "+avui.getDate()+"-"+(avui.getMonth()+1)+"-"+avui.getFullYear()+"\"; tancaCapaFlotDreta(); return false;'>Avui</a>";
   else cad='Avui';
   return cad;
 }
 if(di=='ICurs') {
   di="<?=$nomDiaSem[date('w',$datatimestampIniciCurs)]?>";
   if(di!='Ds' && di!='Dg') cad="<a href='' onClick='document.eaval.editaavaluaciodata.value=\"<?=$nomDiaSem[date('w',$datatimestampIniciCurs)].", ".date('j-n-Y',$datatimestampIniciCurs)?>\"; tancaCapaFlotDreta(); return false;'>Inici Curs</a>";
   else cad='Inici Curs';
   return cad;
 }
 if(di!='Ds' && di!='Dg') cad="<a href='' onClick='document.eaval.editaavaluaciodata.value=\"" +di+", " + i +"-"+ (mes+1) +"-"+ any +"\"; tancaCapaFlotDreta(); return false;'>" + i + "</a>";
 else cad=i;
 return cad;
}

</script>

<?
if(isset($tancaavaluacio) && $tancaavaluacio!='') {
	     
    $consulta="SELECT numero_mat, curs, grup, pla_estudi FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE ";
    $consulta1="SELECT curs, grup, pla_estudi FROM $bdtutoria.$tbl_prefix"."avaluacions WHERE refaval='$tancaavaluacio' LIMIT 1";
    $conjunt_resultant1=mysql_query($consulta1, $connect);
    $fila1=mysql_fetch_row($conjunt_resultant1);
    $fcurs=$fila1[0]; $fgrup=$fila1[1]; $fpla_estudi=$fila1[2];
    $aux1="";
    if($fcurs!='Tots') {
	    $fcurs=explode('|', $fcurs);
	    for($i=0; $i<count($fcurs); ++$i) $aux1.=(($aux1!="")?" or ":"")."curs='$fcurs[$i]'";
    } else $aux1="1";
    $aux2="";
    if($fgrup!='Tots') {
	    $fgrup=explode('|', $fgrup);
	    for($i=0; $i<count($fgrup); ++$i) $aux2.=(($aux2!="")?" or ":"")."grup='$fgrup[$i]'";
    } else $aux2="1";
    $aux3="";
    if($fpla_estudi!='Tots') {
	    $fpla_estudi=explode('|', $fpla_estudi);
	    for($i=0; $i<count($fpla_estudi); ++$i) $aux3.=(($aux3!="")?" or ":"")."pla_estudi='$fpla_estudi[$i]'";
    } else $aux3="1"; 
    $consulta.="($aux1) and ($aux2) and ($aux3)";
    $conjunt_resultant=mysql_query($consulta, $connect);
    while($fila=mysql_fetch_row($conjunt_resultant)) { 
		$consulta1="SELECT DISTINCT h.assign ";
		$consulta1.="FROM $bdtutoria.$tbl_prefix"."horariprofs h, $bdtutoria.$tbl_prefix"."subgrups s ";
		$consulta1.="WHERE h.assign<>'' and (h.grup like '%".rawurlencode($fila[1]." ".$fila[2]." ".$fila[3])."%' or ";
		$consulta1.="(s.alumnes like '%$fila[0]%' and h.grup like concat('%', s.ref_subgrup, '%20', s.nom, '%')))";
		$conjunt_resultant1=mysql_query($consulta1, $connect);
		while($fila1=mysql_fetch_row($conjunt_resultant1)) { 
			$consulta2="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."notes WHERE ref_aval='$tancaavaluacio' and ref_alum='$fila[0]' and ref_credit='$fila1[0]'";
			$conjunt_resultant2=mysql_query($consulta2, $connect);
			if(mysql_result($conjunt_resultant2, 0, 0)==0) {
				$consulta3="INSERT INTO $bdtutoria.$tbl_prefix"."notes SET ref_aval='$tancaavaluacio', ref_alum='$fila[0]', ref_credit='$fila1[0]'";
				mysql_query($consulta3, $connect);	
			} 	
		}
    }
    
    $consulta="UPDATE $bdtutoria.$tbl_prefix"."avaluacions SET modificable='no', estat='tancada' WHERE refaval='$tancaavaluacio'";
    mysql_query($consulta, $connect);	
}

if(isset($eliminaavaluacio)&& $eliminaavaluacio!='') {
 $consulta="DELETE FROM $bdtutoria.$tbl_prefix"."avaluacions where id='$eliminaavaluacio' limit 1";
 mysql_query($consulta, $connect);	
}
if (isset($afegiravaluacio) && $afegiravaluacio=='si') {
 $consulta="insert into $bdtutoria.$tbl_prefix"."avaluacions set refaval='$refaval', nomaval='', nitems='1', nomitems='G->Nota Global', valors='1|2|3|4|5|6|7|8|9|10', data='$datatimestamp',modificable='no',visiblepares='no',curs='Tots', grup='Tots',pla_estudi='Tots',observacions='',estat='oberta'";
 mysql_query($consulta, $connect);	
}
if (isset($actualitzaavaluacio) && $actualitzaavaluacio!='') {
 $dat=preg_split('/ /', $editaavaluaciodata);
 $da=preg_split('/-/', $dat[1]);
 $dattistamp=mktime(0,0,0,$da[1],$da[0],$da[2],-1);
 $consulta="update $bdtutoria.$tbl_prefix"."avaluacions set nomaval='".rawurlencode(stripslashes($editaavaluacionomaval))."', nitems='$editaavaluacionitems', nomitems='$editaavaluacionomitems', valors='$editaavaluaciovalors', data='$dattistamp', modificable='$editaavaluaciomodificable', visiblepares='$editaavaluaciovisiblepares', curs='$editaavaluaciocurs', grup='$editaavaluaciogrup', pla_estudi='$editaavaluaciopla_estudi', observacions='".rawurlencode(stripslashes($editaavaluacioobservacions))."' WHERE id='$actualitzaavaluacio' limit 1";	
 mysql_query($consulta, $connect);
}
?>
</head>
<body  bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="horaAra();">
<?

print("
<div align='right'>
<table border='0'>
<tr><td><font size='6'>Definir Avaluacions&nbsp; &nbsp; </font></td>
</tr></table></div><hr>");
print("<table width='100%' border='0'><tr>
<td>");
    $consulta="SELECT id, refaval, nomaval, nitems, nomitems, valors, data, modificable, visiblepares, curs, grup, pla_estudi, observacions, estat FROM $bdtutoria.$tbl_prefix"."avaluacions ORDER BY data desc, refaval desc";
    $conjunt_resultant=mysql_query($consulta, $connect);
    if(!isset($editaavaluacio)) {
	    print("<table width='100%' border='0'><tr><td align='left'>");
	    print("<a id='linknovaaval' href='' onClick='var refaval=prompt(\"Referència de la nova avaluació (màx. 10 caracters, A-Za-z0-9._ , sense espais, la referència d´avaluació no pot estar repetida.)\",\"--- Introdueix aqui la referència de la nova avaluació ---\");   if(refaval!=\"\" && refaval!=null && refaval==refaval.match(/[a-z0-9._]+/gi  ) ) {document.getElementById(\"linknovaaval\").href=\"$PHP_SELF?idsess=$idsess&afegiravaluacio=si&refaval=\"+refaval; return confirm(\"Es crearà una nova avaluació de nom: \"+refaval+\" . Amb valors dels paràmetres per defecte que es podran editar posteriorment.\");} else {if(refaval!=null) alert(\"Referència incorrecta.\"); return false;}  '>Afegir nova avaluaci&oacute;</a><br><br>");
		print("</td><td align='right'>");
		print("Informaci&oacute;: ".ajudaContextual(12,2)."&nbsp; &nbsp; &nbsp;");
		print("</td></tr></table>"); 
	}
	while($fila=mysql_fetch_row($conjunt_resultant)) {
	    if(isset($editaavaluacio)&& $editaavaluacio==$fila[0]) {
		    print("<table border='0' bgcolor='#aacccc' width='100%'>
		    <script language='JavaScript'>alert('AVÍS: Modificar els paràmetres de l´avaluació, com ara, Nº Items, Valors, Curs, Grup, Pla Estudi, quan ja han estat introduides dades d´avaluació, segons quina sigui la modificació, pot fer que les dades ja introduïdes siguin inconsistents i s´hagin de tornar a revisar!');</script>
		    <form name='eaval' method='post' action='$PHP_SELF?idsess=$idsess&actualitzaavaluacio=$fila[0]'>
		    <tr>
		    <td width='25%'> <a href='' title='Desa els canvis' onClick='document.forms.eaval.submit(); return false;'>Desa</a>
		    <a href='$PHP_SELF?idsess=$idsess' title='Descarta els canvis' onClick='return confirm(\"Segur que vols cancelar els canvis?\");'>Cancela</a></td>
		    <td width='25%'><font size='+1' color='#0000ff'>$fila[1]</font></td>
		    <td width='15%'>&nbsp;</td><td width='35%'><b>Estat:</b>".ajudaContextual(11,1)." ".(($fila[13]=="tancada")?"Avaluaci&oacute; tancada":"Avaluaci&oacute; oberta")."</td>
		    </tr>
		    <tr>
		    <td colspan='4'><b>Nom Avaluaci&oacute;:</b>".ajudaContextual(0,1)." <input type='text' name='editaavaluacionomaval' maxlength='50' size='60' value=''><script language='JavaScript'>document.forms.eaval.editaavaluacionomaval.value='".addslashes(rawurldecode($fila[2]))."';</script></td>
		    </tr>
		    <tr>
		    <td colspan='2'><b>Nº items:</b>".ajudaContextual(1,1)." <select name='editaavaluacionitems' onChange='actualitzaNomItems();'><option".(($fila[3]=="1")?" selected":"").">1</option><option".(($fila[3]=="2")?" selected":"").">2</option><option".(($fila[3]=="3")?" selected":"").">3</option><option".(($fila[3]=="4")?" selected":"").">4</option><option".(($fila[3]=="5")?" selected":"").">5</option><option".(($fila[3]=="6")?" selected":"").">6</option></select></td>
		    <td colspan='2'><b>Nom items:</b>".ajudaContextual(2,1)." <span id='eliminaItems'></span><br><input type='hidden' name='editaavaluacionomitems' value='$fila[4]'>
		    <span id='cNomItems'></span>
		    <script language='JavaScript'>
		    function actualitzaNomItems() {
			  var nitems=document.forms.eaval.editaavaluacionitems.options[document.forms.eaval.editaavaluacionitems.selectedIndex].text;
			  var vals='';
			  for(var i=0; i<nitems; ++i) {
				  if(i!=(nitems-1)) vals+=((vals!='')?'|':'')+String.fromCharCode(97+i)+'->Item no. '+(i+1);
				  else vals+=((vals!='')?'|':'')+'G->Nota Global';
			  }
			  document.forms.eaval.editaavaluacionomitems.value=vals;
			  deCampACapaN();
			       
		    }
		    function canviItem(item, de) {
				if(de==0) {
					var nSigla=prompt('Introdueix la nova sigla (màx. 1 caràcter):','');
					if (nSigla==null) return;
					nSigla=nSigla.replace(/^ +| +$/g,'');
			 	    if(nSigla!=nSigla.match(/[a-z0-9]?/i)) {
				 	    alert('Valor erròni');
				 	    return;
			 	    }
			 	    if(nSigla=='') nSigla='&nbsp;';
				}
				if(de==1) {
					var nNom=prompt('Introdueix el nou nom:','');
					if (nNom==null) return;
				}
				var vals=(document.forms.eaval.editaavaluacionomitems.value).split('|');
				var aux='';
				for(var i=0; i<vals.length; ++i) {
					var va=vals[i].split('->');
					aux+=((aux!='')?'|':'')+((i==item&&de==0)?nSigla:va[0])+'->'+((i==item&&de==1)?nNom:va[1]);					
				}
				document.forms.eaval.editaavaluacionomitems.value=aux;
				deCampACapaN();	
		    }
		    function deCampACapaN() {
				var aux='';
				var vals=(document.forms.eaval.editaavaluacionomitems.value).split('|');
				if(vals!='') escriuACapa('eliminaItems','<a href=\"\" title=\"Elimina tots els noms i sigles dels items.\" onClick=\"eliminaItems(); return false;\">Elimina Items</a>');
				else escriuACapa('eliminaItems','<a href=\"\" title=\"Introdueix tots els noms i sigles dels items, amb els valors per defecte.\" onClick=\"actualitzaNomItems(); return false;\">Introdueix Items</a>');
				if(vals!='') {
					aux+='<table border=\"0\">';
					for(var i=0; i<vals.length; ++i) {
						var va=vals[i].split('->');
						aux+='<tr><td> &nbsp; Item '+(i+1)+':</td><td>&nbsp; &nbsp; <a href=\"\" title=\"Canvi Sigla item\" onClick=\"canviItem('+i+',0); return false;\">'+((va[0]!='')?va[0]:'&nbsp;')+'</a></td><td> &rarr; </td><td><a href=\"\" title=\"Canvi Nom item\" onClick=\"canviItem('+i+',1); return false;\">'+((va[1]!='')?va[1]:'&nbsp;')+'</a></td></tr>';
					}
					aux+='</table>'
					escriuACapa('cNomItems', aux);
				}
				else escriuACapa('cNomItems', '');   
		    }
		    function eliminaItems() {
			   document.forms.eaval.editaavaluacionomitems.value='';
			   deCampACapaN(); 
		    }    
		    deCampACapaN(); 
		    </script>
		    </td>
		    </tr>
		    <tr>
		    <td><b>Modificable:</b>".ajudaContextual(3,1)." <select name='editaavaluaciomodificable'><option".(($fila[7]=="si")?" selected":"").">si</option><option".(($fila[7]=="no")?" selected":"").">no</option></select></td>
		    <td><b>Visible pares:</b>".ajudaContextual(4,1)." <select name='editaavaluaciovisiblepares'><option".(($fila[8]=="si")?" selected":"").">si</option><option".(($fila[8]=="no")?" selected":"").">no</option></select></td>
		    <td><b>Valors:</b>".ajudaContextual(5,1)."<br>
		    <input type='hidden' name='editaavaluaciovalors' value='$fila[5]'>
		    <span id='cValors'></span>
		    <script language='JavaScript'>
		    function deCampACapaV() {
			 var aux='';
			 var vals=(document.forms.eaval.editaavaluaciovalors.value).split('|');
			 for (var i=0; i<vals.length; ++i) {
				aux+=((aux!='')?', ':'')+'<a href=\"\" title=\"Elimina '+vals[i]+'\" onClick=\"eliminaValorV(\''+vals[i]+'\'); return false;\">'+vals[i]+'</a>';	 
			 }
			 if(vals.length<25) aux+=' <a href=\"\" title=\"Afegir un nou valor.\" onClick=\"afegirValorV(); return false;\">Afegir</a>';
			 escriuACapa('cValors',aux);   
		    }
		    function eliminaValorV(val) {
			 var aux='';
			 var vals=(document.forms.eaval.editaavaluaciovalors.value).split('|');
			 for (var i=0; i<vals.length; ++i) {
				if(vals[i]!=val) aux+=((aux!='')?'|':'')+vals[i];	 
			 }
			 document.forms.eaval.editaavaluaciovalors.value=aux;
			 deCampACapaV();   
		    }
		    function afegirValorV() {
			 var val=prompt('Introdueix el nou valor (1 o 2 caracters, A-Z,a-y,0-9):','');
			 if(val!=null) {
			 	val=val.replace(/^ +| +$/g,'');
			 	if(val== val.match(/[A-Za-y0-9]{1,2}/g)) {
				 	document.forms.eaval.editaavaluaciovalors.value+=((document.forms.eaval.editaavaluaciovalors.value!='')?'|':'')+val;
				 	deCampACapaV();
			 	}
			 	else alert('Valor incorrecte'); 	 
		 	 }   
		    }
		    deCampACapaV();
		    </script>
		    </td>
		    <td><b>Data avaluaci&oacute;:</b>".ajudaContextual(6,1)." <input type='text' name='editaavaluaciodata' size='13' value='".$nomDiaSem[date('w',$fila[6])].", ".date('j-n-Y',$fila[6])."' onClick=' blur(); obreCalendari(".date('Y',$fila[6]).",".date('n',$fila[6]).",0);'></td>
			</tr>
			<tr>
		    <td>
		    <b>Cursos:</b>".ajudaContextual(7,1)."<br><input type='hidden' name='editaavaluaciocurs' value='$fila[9]'>
		    <select name='eac' size='4' multiple onChange='var cad=\"\"; for (var i=0; i<document.eaval.eac.options.length; ++i) {if(document.eaval.eac.options[i].selected) {if(document.eaval.eac.options[i].text==\"Tots\"){cad=\"Tots\";break;} else cad += ((cad!=\"\")?\"|\":\"\")+document.eaval.eac.options[i].text;}} document.eaval.editaavaluaciocurs.value=cad;'>
		    <option".(($fila[9]=="Tots")?" selected":"").">Tots</option>");
		    $f9=explode('|', $fila[9]);
		    $consulta1="SELECT DISTINCT curs FROM $bdalumnes.$tbl_prefix"."Estudiants ORDER BY curs";
		    $conjunt_resultant1=mysql_query($consulta1, $connect);
		    while($fila1=mysql_fetch_row($conjunt_resultant1)) {
			    $t=false;
			    for($i=0; $i<count($f9); ++$i) {if($f9[$i]==$fila1[0]) {$t=true; break;}}
			 	print("<option".(($t)?" selected":"").">$fila1[0]</option>");   
		    }
		    mysql_free_result($conjunt_resultant1);
		    print("</select> 
		    </td>
		    <td><b>Grups:</b>".ajudaContextual(8,1)."<br><input type='hidden' name='editaavaluaciogrup' value='$fila[10]'>
		    <select name='eag' size='4' multiple onChange='var cad=\"\"; for (var i=0; i<document.eaval.eag.options.length; ++i) {if(document.eaval.eag.options[i].selected) {if(document.eaval.eag.options[i].text==\"Tots\"){cad=\"Tots\";break;} else cad += ((cad!=\"\")?\"|\":\"\")+document.eaval.eag.options[i].text;}} document.eaval.editaavaluaciogrup.value=cad;'>
		    <option".(($fila[10]=="Tots")?" selected":"").">Tots</option>");
		    $f10=explode('|', $fila[10]);
		    $consulta1="SELECT DISTINCT grup FROM $bdalumnes.$tbl_prefix"."Estudiants ORDER BY grup";
		    $conjunt_resultant1=mysql_query($consulta1, $connect);
		    while($fila1=mysql_fetch_row($conjunt_resultant1)) {
			    $t=false;
			    for($i=0; $i<count($f10); ++$i) {if($f10[$i]==$fila1[0]) {$t=true; break;}}
			 	print("<option".(($t)?" selected":"").">$fila1[0]</option>");   
		    }
		    mysql_free_result($conjunt_resultant1);
		    print("</select>
		    </td>
		    <td colspan='2'><b>Pla Estudis:</b>".ajudaContextual(9,1)."<br><input type='hidden' name='editaavaluaciopla_estudi' value='$fila[11]'>
		    <select name='eap' size='4' multiple onChange='var cad=\"\"; for (var i=0; i<document.eaval.eap.options.length; ++i) {if(document.eaval.eap.options[i].selected) {if(document.eaval.eap.options[i].text==\"Tots\"){cad=\"Tots\";break;} else cad += ((cad!=\"\")?\"|\":\"\")+document.eaval.eap.options[i].text;}} document.eaval.editaavaluaciopla_estudi.value=cad;'>
		    <option".(($fila[11]=="Tots")?" selected":"").">Tots</option>");
		    $f11=explode('|', $fila[11]);
		    $consulta1="SELECT DISTINCT pla_estudi FROM $bdalumnes.$tbl_prefix"."Estudiants ORDER BY pla_estudi desc";
		    $conjunt_resultant1=mysql_query($consulta1, $connect);
		    while($fila1=mysql_fetch_row($conjunt_resultant1)) {
			    $t=false;
			    for($i=0; $i<count($f11); ++$i) {if($f11[$i]==$fila1[0]) {$t=true; break;}}
			 	print("<option".(($t)?" selected":"").">$fila1[0]</option>");   
		    }
		    mysql_free_result($conjunt_resultant1);
		    print("</select>
		    </td>
		    </tr>
		    <tr>
		    <td colspan='4'><b>Observacions:</b>".ajudaContextual(10,1)." <input type='text' name='editaavaluacioobservacions' size='90' value=''><script language='JavaScript'>document.forms.eaval.editaavaluacioobservacions.value='".addslashes(rawurldecode($fila[12]))."';</script></td>
		    </tr>
		    </form>
		    </table>");
	    } else if(!isset($editaavaluacio)){
		    print("<table border='0' bgcolor='".(($fila[13]=="tancada")?"#c0c0c0":"#aacccc")."' width='100%'>
		    <tr><td width='25%'> ".(($fila[13]=="tancada")?"Editar Eliminar":"<a title='Modifica els par&agrave;metres d´aquesta avaluaci&oacute;' href='$PHP_SELF?idsess=$idsess&editaavaluacio=$fila[0]'>Editar</a> <a title='Elimina aquesta avaluaci&oacute;' href='$PHP_SELF?idsess=$idsess&eliminaavaluacio=$fila[0]' onClick='return confirm(\"Segur que vols eliminar aquesta avaluació? Si l´esborres, totes les dades que s´hagin introduït quedaran desenllaçades!\");'>Eliminar</a>")."</td>
		     <td width='25%'><font size='+1' color='#0000ff'>$fila[1]</font></td>
		     <td width='15%'><a href='' title='Edita les notes d´aquesta avaluaci&oacute;' onClick='editaNotes(\"$fila[1]\"); return false;'>Edita Notes</a></td>
		     <td width='35%'><b>Estat:</b>".ajudaContextual(11,1)." ".(($fila[13]=="tancada")?"Avaluaci&oacute; tancada":"Avaluaci&oacute; oberta <a title='Modifica l´estat de l´avaluaci&oacute; fent-ne el tancament' href='$PHP_SELF?idsess=$idsess&tancaavaluacio=$fila[1]' onClick='return confirm(\"Segur que vols tancar l´avaluació? Només s´ha de tancar una avaluació quan ja s´ha posat totes les notes, previament a canviar l´associació d´assignatures i grups en els horaris de professors. Aquest procés és irreversible i dura una estona llarga.! Un cop tancada, els professors ja no podran posar notes i aquestes solament es podran canviar des de Edita Notes per part dels Administradors\");'>**Tanca-la**</a>")."</td></tr>
		    <tr><td colspan='4'><b>Nom Avaluaci&oacute;:</b> ".rawurldecode($fila[2])."</td></tr>
		    <tr><td colspan='2'><b>Nº items:</b> $fila[3]</td><td colspan='2'><b>Nom items:</b><br>");
		    $aux="";
		    if($fila[4]!="") {
			    $fi4=explode("|", $fila[4]);
			    $aux.="<table border='0'>";
			    for($i=0; $i<count($fi4); ++$i) {
				  $f4=explode("->",$fi4[$i]);
				  $aux.='<tr><td>Item '.($i+1).': </td><td> &nbsp; &nbsp; '.$f4[0]."</td><td>&rarr;</td><td>".$f4[1]."</td></tr>";   
			    }
			    $aux.="</table>";
		    }
		    print("$aux</td></tr>
		    <tr><td><b>Modificable:</b> $fila[7]</td><td><b>Visible pares:</b> $fila[8]</td><td><b>Valors:</b> ".strtr($fila[5], array("|"=>", "))."</td><td><b>Data avaluaci&oacute;:</b> ".$nomDiaSem[date('w',$fila[6])].", ".date('j-n-Y',$fila[6])."</td></tr>		    
		    <tr><td><b>Cursos:</b> ".strtr($fila[9], array("|"=>", "))."</td><td><b>Grups:</b> ".strtr($fila[10], array("|"=>", "))."</td><td colspan='2'><b>Pla Estudis:</b> ".strtr($fila[11], array("|"=>", "))."</td></tr>
		    <tr><td colspan='4'><b>Observacions:</b> ".rawurldecode($fila[12])."</td></tr>
		    </table><hr>");
	    }
    }
    mysql_free_result($conjunt_resultant);
	
print("</td></tr></table>");

?>
</body>
</html>