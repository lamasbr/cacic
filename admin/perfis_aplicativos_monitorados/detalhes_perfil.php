<?
 /* 
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informa��es da Previd�ncia Social, Brasil

 Este arquivo � parte do programa CACIC - Configurador Autom�tico e Coletor de Informa��es Computacionais

 O CACIC � um software livre; voc� pode redistribui-lo e/ou modifica-lo dentro dos termos da Licen�a P�blica Geral GNU como 
 publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a, ou (na sua opni�o) qualquer vers�o.

 Este programa � distribuido na esperan�a que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUA��O a qualquer
 MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU para maiores detalhes.

 Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "LICENCA.txt", junto com este programa, se n�o, escreva para a Funda��o do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
session_start();
/*
 * verifica se houve login e tamb�m regras para outras verifica��es (ex: permiss�es do usu�rio)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

require_once('../../include/library.php');
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administra��o
// 2 - Gest�o Central

Conecta_bd_cacic();

if ($_POST['ExcluiAplicativo']) 
	{
	$query = "DELETE 
			  FROM 		perfis_aplicativos_monitorados 
			  WHERE 	id_aplicativo = ".$_POST['id_aplicativo'];
	mysql_query($query) or die('1-Delete PERFIS_APLICATIVOS_MONITORADOS falhou ou sua sess�o expirou!');
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'perfis_aplicativos_monitorados');			
	
	$query = "DELETE 
			  FROM 		aplicativos_monitorados 
			  WHERE 	id_aplicativo = ".$_POST['id_aplicativo'];
	mysql_query($query) or die('2-Delete APLICATIVOS_MONITORADOS falhou ou sua sess�o expirou!');
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'aplicativos_monitorados');			

	$query = "DELETE
			  FROM		aplicativos_redes
			  WHERE		id_aplicativo = ".$_POST['id_aplicativo'];
	$result = mysql_query($query) or die ('3-Delete falhou ou sua sess�o expirou!');				
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'aplicativos_redes');			
		
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/perfis_aplicativos_monitorados/index.php&tempo=1");									 		
	
	}
elseif ($_POST['GravaAlteracoes']) 
	{
		
	$v_nm_aplicativo = $frm_nm_aplicativo;
	if ($frm_in_ativa == 'N')
		{
		$v_nm_aplicativo .= '#DESATIVADO#';
		}
	$query = "UPDATE 	perfis_aplicativos_monitorados 
			  SET 		nm_aplicativo = '$v_nm_aplicativo',  
			  			te_dir_padrao_w9x = '$frm_te_dir_padrao_w9x',
						te_dir_padrao_wnt = '$frm_te_dir_padrao_wnt',			  
			  			cs_car_inst_w9x = '$frm_cs_car_inst_w9x', 
						cs_car_inst_wnt = '$frm_cs_car_inst_wnt', 
			  			te_car_inst_w9x = '$frm_te_car_inst_w9x', 
						te_car_inst_wnt = '$frm_te_car_inst_wnt', 
			  			cs_car_ver_w9x = '$frm_cs_car_ver_w9x', 
						cs_car_ver_wnt = '$frm_cs_car_ver_wnt', 
			  			te_car_ver_w9x = '$frm_te_car_ver_w9x', 
						te_car_ver_wnt = '$frm_te_car_ver_wnt', 
			  			te_arq_ver_eng_w9x = '$frm_te_arq_ver_eng_w9x', 
						te_arq_ver_pat_w9x = '$frm_te_arq_ver_pat_w9x', 			  
			  			te_arq_ver_eng_wnt = '$frm_te_arq_ver_eng_wnt', 
						te_arq_ver_pat_wnt = '$frm_te_arq_ver_pat_wnt', 			  			  
			  			cs_ide_licenca = '$frm_cs_ide_licenca', 
						te_ide_licenca = '$frm_te_ide_licenca', 			  
			  			id_so = '$frm_id_so', 
						te_descritivo = '$frm_te_descritivo', 			  			   			  			  
			  			dt_atualizacao = now(),
			  			in_disponibiliza_info = '$frm_in_disponibiliza_info',
			  			in_disponibiliza_info_usuario_comum = '$frm_in_disponibiliza_info_usuario_comum'			    			  			  
			  WHERE 	id_aplicativo = ".$_POST['id_aplicativo'];

	mysql_query($query) or die('4-Update falhou ou sua sess�o expirou!');
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'perfis_aplicativos_monitorados');		


	$query = "DELETE
			  FROM		aplicativos_redes
			  WHERE		id_aplicativo = ".$_POST['id_aplicativo'];
	$result = mysql_query($query) or die ('5-Delete falhou ou sua sess�o expirou!');				

	$strInsertAplicativosRedes = '';
	for ($i=0; $i < count($_POST['list2']);$i++)
		{
		$dado = explode('_',$_POST['list2'][$i]);
		if ($strInsertAplicativosRedes)
			$strInsertAplicativosRedes .= ',';
		$strInsertAplicativosRedes .= "(".$dado[0].",'".$dado[1]."',".$_POST['id_aplicativo'].")";		
		}
		
	if ($strInsertAplicativosRedes)
		{

		$query = "INSERT 
				  INTO 		aplicativos_redes
				  VALUES 	".$strInsertAplicativosRedes;
		$result = mysql_query($query) or die ('6-Insert falhou ou sua sess�o expirou!');								  
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'aplicativos_redes');				
		}
	
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/perfis_aplicativos_monitorados/index.php&tempo=1");									 		
	
	}
else 
	{
	$query = "SELECT 	* 
			  FROM 		perfis_aplicativos_monitorados 
			  WHERE 	id_aplicativo = ".$_GET['id_aplicativo'];
	$result = mysql_query($query) or die ('7-Select falhou ou sua sess�o expirou!');
	$row = mysql_fetch_array($result);
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?
require_once('../../include/selecao_listbox.js');  
?>

<SCRIPT LANGUAGE="JavaScript">
function SetaDescGrupo(p_descricao,p_destino) 
	{
	document.forms[0].elements[p_destino].value = p_descricao;		
	}

function SetaNomeSistema()
	{
	document.forma.frm_nm_aplicativo.value = document.forma.frm_id_so.options[document.forma.frm_id_so.options.selectedIndex].text;
	}
	
function valida_form() {
	if ( document.forma.frm_nm_aplicativo.value == "" ) 
	{	
		alert("O campo Nome do Aplicativo � obrigat�rio.");
		document.forma.frm_nm_aplicativo.focus();
		return false;
	}
}

</script>
</head>

<body background="../../imgs/linha_v.gif" onLoad="SetaCampo('frm_nm_aplicativo')">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<table width="90%" border="0" align="center">
	<tr> 
    	<td class="cabecalho">Detalhes de Perfil de Sistema Monitorado
		</td>
	</tr>
  	<tr> 
    	<td class="descricao">As informa&ccedil;&otilde;es 
      	abaixo referem-se &agrave;s caracter&iacute;sticas de instala&ccedil;&atilde;o 
      	do sistema a ser monitorado pelos agentes CACIC. &Eacute; necess&aacute;rio 
      	o cuidado especial quanto ao uso de letras mai&uacute;sculas e min&uacute;sculas.
		</td>
  	</tr>
</table>

	<form method="post" ENCTYPE="multipart/form-data" name="forma" onSubmit="return valida_form()">
	<input type="hidden" name="id_aplicativo" value="<? echo $_GET['id_aplicativo'];?>">	
  <tr> 
    <td align="center">
<div align="center"><br>
        <table width="90%" border="0" align="center">
          <tr> 
            <td nowrap class="label">Verifica&ccedil;&atilde;o Ativa?: 
              <select name="frm_in_ativa" id="select16" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?> >
                <option value="N" <? if (strpos($row['nm_aplicativo'], "#DESATIVADO#")>0) echo " selected ";?>>N�o</option>
                <option value="S" <? if (strpos($row['nm_aplicativo'], "#DESATIVADO#")==0) echo " selected ";?>>Sim</option>
              </select></td>
          </tr>
          <tr> 
            <td nowrap class="label">&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="label">Nome do sistema:<br> 
              <? $v_nm_aplicativo = $row['nm_aplicativo']; 
			if (strpos($v_nm_aplicativo, "#DESATIVADO#")>0) 
					{
					$v_nm_aplicativo = substr($row['nm_aplicativo'], 0, strpos($row['nm_aplicativo'], "#DESATIVADO#"));
					}
			?>
              <input name="frm_nm_aplicativo" type="text" id="frm_nm_aplicativo3" size="80" maxlength="100" value="<? echo $v_nm_aplicativo;?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"<? echo ($_SESSION['cs_nivel_administracao']<>1?'readonly':'')?> > 
            </td>
          </tr>
          <tr> 
            <td nowrap class="label">&nbsp;</td>
          </tr>
          <tr> 
            <td width="58%" nowrap class="label">&Eacute; um Sistema Operacional? 
              Qual?<br> <select name="frm_id_so" id="select13" onChange="SetaNomeSistema();" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <option value="0"></option>
                <?
			Conecta_bd_cacic();
			$query = "SELECT id_so,te_desc_so
			          FROM   so
					  WHERE  id_so <> '0'
					  ORDER  BY te_desc_so";
			mysql_query($query) or die('8-Select falhou ou sua sess�o expirou!');
		    $sql_result=mysql_query($query);			
		while ($row_so=mysql_fetch_array($sql_result))
			{ 
			echo "<option value=\"" . $row_so["id_so"] . "\"";
			if ($row_so['id_so'] == $row["id_so"]) echo " selected ";
			echo ">" . $row_so["te_desc_so"] . "</option>";
		   	} 			
			?>
              </select> </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="label">Disponibilizar Informa&ccedil;&otilde;es 
              no Systray? (&iacute;cone na bandeja da esta&ccedil;&atilde;o):<br> 
              <select name="frm_in_disponibiliza_info" id="frm_in_disponibiliza_info" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"<? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?> >
                <option value="N" <? if ($row['in_disponibiliza_info'] == "N") echo " selected ";?>>N�o</option>
                <option value="S" <? if ($row['in_disponibiliza_info'] == "S") echo " selected ";?>>Sim</option>
              </select> </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="label">Disponibilizar Informa&ccedil;&otilde;es 
              ao Usu&aacute;rio Comum? (diferente de Administrador):<br> <select name="frm_in_disponibiliza_info_usuario_comum" id="frm_in_disponibiliza_info_usuario_comum" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"<? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?> >
                <option value="N" <? if ($row['in_disponibiliza_info_usuario_comum'] == "N") echo " selected ";?>>N�o</option>
                <option value="S" <? if ($row['in_disponibiliza_info_usuario_comum'] == "S") echo " selected ";?>>Sim</option>
              </select> </td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="label"> Descri&ccedil;&atilde;o:</td>
          </tr>
          <tr> 
            <td nowrap> <textarea name="frm_te_descritivo" <? echo ($_SESSION['cs_nivel_administracao']<>1?'readonly':'')?> cols="60" rows="3" id="textarea" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ><? echo $row['te_descritivo'];?></textarea> 
            </td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="label">Identificador de Licen&ccedil;a:</td>
          </tr>
          <tr> 
            <td nowrap> <select name="frm_cs_ide_licenca" id="select6" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda1')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
                <option value="0" id=""></option>
                <option value="1" <? if ($row['cs_ide_licenca']=='1') echo 'selected';?>  id="Ex.:  HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao">Caminho\Chave\Valor 
                em Registry</option>
                <option value="2" <? if ($row['cs_ide_licenca']=='2') echo 'selected';?>  id="Ex.:  Arquivos de Programas\Cacic\Cacic2.ini/Patrimonio/nu_CPU">Nome/Se&ccedil;&atilde;o\Chave 
                de Arquivo INI</option>
              </select> <br> <input name="frm_te_ide_licenca" type="text" id="frm_te_ide_licenca" value="<? echo $row['te_ide_licenca'];?>" size="80" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_SESSION['cs_nivel_administracao']<>1?'readonly':'')?>> 
              <br> <input name="Ajuda1" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
              <br> <input name="Ajuda11" type="text" style="border:0" size="80" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
              <div align="left"> <br>
              </div></td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="cabecalho_secao"><u>Caracter&iacute;sticas em ambientes 
              Windows 9x/Me</u></td>
          </tr>
          <tr> 
            <td nowrap class="label">Identificador de Instala&ccedil;&atilde;o:</td>
          </tr>
          <tr> 
            <td nowrap><select name="frm_cs_car_inst_w9x" id="select17" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda2')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
                <option value="0" id=""></option>
                <option value="1" <? if ($row['cs_car_inst_w9x']=='1') echo 'selected';?> id="Ex.:  Arquivos de Programas\Cacic\Programas\cacic.exe">Nome 
                de Execut&aacute;vel</option>
                <option value="2" <? if ($row['cs_car_inst_w9x']=='2') echo 'selected';?> id="Ex.:  Arquivos de Programas\Cacic\Dados\config.ini">Nome 
                de Arquivo de Configura&ccedil;&atilde;o</option>
                <option value="3" <? if ($row['cs_car_inst_w9x']=='3') echo 'selected';?> id="Ex.:  HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao">Caminho\Chave\Valor 
                em Registry</option>
              </select> <br> <input name="frm_te_car_inst_w9x" type="text" id="frm_te_car_inst_w9x3" size="80" maxlength="100" value="<? echo $row['te_car_inst_w9x'];?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_SESSION['cs_nivel_administracao']<>1?'readonly':'')?>> 
              <br> <input name="Ajuda2" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200"> 
              <br> <input name="Ajuda22" type="text" style="border:0" size="80"></td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="label">Identificador de Vers&atilde;o/Configura&ccedil;&atilde;o:</td>
          </tr>
          <tr> 
            <td nowrap> <select name="frm_cs_car_ver_w9x" id="select18" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda3')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
                <option value="0" id=""></option>
                <option value="1"<? if ($row['cs_car_ver_w9x']=='1') echo 'selected';?> id="Ex.:  Arquivos de Programas\Cacic2\Programas\ger_cols.exe">Data 
                de Arquivo</option>
                <option value="2"<? if ($row['cs_car_ver_w9x']=='2') echo 'selected';?> id="Ex.:  HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao">Caminho\Chave\Valor 
                em Registry</option>
                <option value="3"<? if ($row['cs_car_ver_w9x']=='3') echo 'selected';?> id="Ex.:  Arquivos de Programas\Cacic\Cacic2.ini/Patrimonio/nu_CPU">Nome/Se&ccedil;&atilde;o/Chave 
                de Arquivo INI</option>
                <option value="4"<? if ($row['cs_car_ver_w9x']=='4') echo 'selected';?> id="Ex.:  Cacic\modulos\col_moni.exe">Vers�o 
                de Execut�vel</option>
              </select> <br> <input name="frm_te_car_ver_w9x" type="text" id="frm_te_car_ver_w9x3" size="80" maxlength="100" value="<? echo $row['te_car_ver_w9x'];?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_SESSION['cs_nivel_administracao']<>1?'readonly':'')?>> 
              <br> <input name="Ajuda3" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200"> 
              <br> <input name="Ajuda33" type="text" style="border:0" size="80"></td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="cabecalho_secao"><u>Caracter&iacute;sticas em ambientes 
              Windows NT/2000/XP/2003</u></td>
          </tr>
          <tr> 
            <td nowrap class="label">Identificador de Instala&ccedil;&atilde;o:</td>
          </tr>
          <tr> 
            <td nowrap> <select name="frm_cs_car_inst_wnt" id="select19" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda4')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
                <option value="0" id=""></option>
                <option value="1" <? if ($row['cs_car_inst_wnt']=='1') echo 'selected';?> id="Ex.:  Arquivos de Programas\Cacic2\Programas\ger_cols.exe">Nome 
                de Execut&aacute;vel</option>
                <option value="2" <? if ($row['cs_car_inst_wnt']=='2') echo 'selected';?> id="Ex.:  Arquivos de Programas\Cacic\Dados\config.ini">Nome 
                de Arquivo de Configura&ccedil;&atilde;o</option>
                <option value="3" <? if ($row['cs_car_inst_wnt']=='3') echo 'selected';?> id="Ex.:  HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao">Caminho\Chave\Valor 
                em Registry</option>
              </select> <br> <input name="frm_te_car_inst_wnt" type="text" id="frm_te_car_inst_wnt3" size="80" maxlength="100" value="<? echo $row['te_car_inst_wnt'];?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_SESSION['cs_nivel_administracao']<>1?'readonly':'')?>> 
              <br> <input name="Ajuda4" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200"> 
              <br> <input name="Ajuda44" type="text" style="border:0" size="80"></td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="label">Identificador de Vers&atilde;o/Configura&ccedil;&atilde;o:</td>
          </tr>
          <tr> 
            <td nowrap> <select name="frm_cs_car_ver_wnt" id="select20" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda5')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
                <option value="0" id=""></option>
                <option value="1"<? if ($row['cs_car_ver_wnt']=='1') echo 'selected';?> id="Ex.:  Arquivos de Programas\Cacic2\Programas\ger_cols.exe">Data 
                de Arquivo</option>
                <option value="2"<? if ($row['cs_car_ver_wnt']=='2') echo 'selected';?> id="Ex.:  HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao">Caminho\Chave\Valor 
                em Registry</option>
                <option value="3"<? if ($row['cs_car_ver_wnt']=='3') echo 'selected';?> id="Ex.:  Arquivos de Programas\Cacic\Cacic2.ini/Patrimonio/nu_CPU">Nome/Se&ccedil;&atilde;o/Chave 
                de Arquivo INI</option>
                <option value="4"<? if ($row['cs_car_ver_wnt']=='4') echo 'selected';?> id="Ex.:  Cacic\modulos\col_moni.exe">Vers�o 
                de Execut�vel</option>
              </select> <br> <input name="frm_te_car_ver_wnt" type="text" id="frm_te_car_ver_wnt3" size="80" maxlength="100" value="<? echo $row['te_car_ver_wnt'];?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_SESSION['cs_nivel_administracao']<>1?'readonly':'')?>> 
              <br> <input name="Ajuda5" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200"> 
              <br> <input name="Ajuda55" type="text" style="border:0" size="80"></td>
          </tr>
    <tr> 
      <td nowrap>&nbsp;</td>
    </tr>
	
    <tr> 
      <td nowrap class="cabecalho_secao"><u>Sele&ccedil;&atilde;o de redes para  aplica&ccedil;&atilde;o desta coleta de informa&ccedil;&otilde;es</u></td>
    </tr>
	
	<tr>
	<td>
	<?
	$boolDetalhes = 'OK';
	include_once "../../include/selecao_redes_perfil_inc.php";	
	?>
	</td>	
	</tr>
		  
        </table>
          
        <br>
      </div></td>
	  
    </tr>
	
  </table>


  <p align="center"> 
    <input name="GravaAlteracoes" type="submit" id="GravaAlteracoes" value="  Gravar Altera&ccedil;&otilde;es  " onClick="SelectAll(this.form.elements['list2[]']),return Confirma('Confirma Informa��es para Perfil de Sistema Monitorado?') " <? echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
    &nbsp; &nbsp; 
    <input name="ExcluiAplicativo" type="submit" value="Excluir Perfil de Sistema Monitorado" onClick="return Confirma('Confirma Exclus�o de Perfil de Sistema Monitorado?');" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
  </p>
  </form>
</body>
</html>
<?
}
?>