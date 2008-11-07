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
include_once "../../include/library.php";
AntiSpy('1,2'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administra��o
// 2 - Gest�o Central


if($_POST['submit']<>'' && $_SESSION['cs_nivel_administracao']==1) 
	{
	Conecta_bd_cacic();
	
	$query = "SELECT 	* 
			  FROM 		dominios 
			  WHERE 	nm_dominio = '".$_POST['frm_nm_dominio']."'";
	$result = mysql_query($query) or die ('1-Select falhou ou sua sess�o expirou!');
	
	if (mysql_num_rows($result) > 0) 
		{
		header ("Location: ../../include/registro_ja_existente.php?chamador=../admin/dominios/index.php&tempo=1");									 							
		}
	else 
		{
		$query = "INSERT 
				  INTO 		dominios 
				  			(nm_dominio, 
				  			 te_ip_dominio,
							 id_tipo_protocolo,
							 nu_versao_protocolo,
							 te_string_DN,
							 te_observacao) 
				  VALUES 	('".$_POST['frm_nm_dominio']."', 
						  	 '".$_POST['frm_te_ip_dominio']."',									  
							 '".$_POST['frm_id_tipo_protocolo']."',
							 '".$_POST['frm_nu_versao_protocolo']."',
							 '".$_POST['frm_te_string_DN']."',							 
							 '".$_POST['frm_te_observacao']."')";							 									  						  
		$result = mysql_query($query) or die ('2-Falha na Inser��o em Dominios ou sua sess�o expirou!');
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'dominios');			
		
	    header ("Location: index.php");		
		}
	}
else 
	{
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
	<title>Inclus&atilde;o de Dom&iacute;nio</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<SCRIPT LANGUAGE="JavaScript">
    
    function valida_form() 
        {
    
        if ( document.form.frm_nm_dominio.value == "" ) 
            {	
            alert("O nome � obrigat�rio.");
            document.form.frm_nm_dominio.focus();
            return false;
            }		
        else if ( document.form.frm_te_ip_dominio.value == "" ) 
            {	
            alert("O IP � obrigat�rio.");
            document.form.frm_te_ip_dominio.focus();
            return false;
            }
        else if ( document.form.frm_id_tipo_protocolo.value == "" ) 
            {	
            alert("Selecione o Tipo de Protocolo.");
            document.form.frm_id_tipo_protocolo.focus();
            return false;
            }
            
        return true;		
        }
    </script>
    <script language="JavaScript" type="text/JavaScript">
    <!--
    function MM_reloadPage(init) {  //reloads the window if Nav4 resized
      if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
        document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
      else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
    }
    MM_reloadPage(true);
    //-->
    </script>
    <style type="text/css">
<!--
.style2 {
	font-size: 9px;
	color: #000099;
}
-->
    </style>
    </head>
    
    <body background="../../imgs/linha_v.gif" onLoad="SetaCampo('frm_nm_dominio');">
    <script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
    <table width="90%" border="0" align="center">
      <tr> 
        <td class="cabecalho">Inclus&atilde;o 
          de Dom&iacute;nio</td>
      </tr>
      <tr> 
        <td class="descricao">As informa&ccedil;&otilde;es que dever&atilde;o ser 
          cadastradas abaixo referem-se a um dom&iacute;nio a ser utilizado na autentica&ccedil;&atilde;o de usu&aacute;rios do suporte remoto seguro. </td>
      </tr>
    </table>
    <form action="incluir_dominio.php"  method="post" ENCTYPE="multipart/form-data" name="form" onSubmit="return valida_form()">
      <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td class="label"><br>
            Nome do Dom&iacute;nio:</td>
          <td nowrap class="label"><br>
          Endere&ccedil;o IP do Dom&iacute;nio:</td>
        </tr>
        <tr> 
          <td height="1" bgcolor="#333333" colspan="3"></td>
        </tr>
        <tr> 
          <td class="label_peq_sem_fundo"> <input name="frm_nm_dominio" type="text" size="60" maxlength="60" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          &nbsp;&nbsp;</td>
          <td class="label_peq_sem_fundo"><input name="frm_te_ip_dominio" type="text" size="30" maxlength="15" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_ip_dominio" ></td>
        </tr>
        <tr> 
          <td class="label"><div align="left"><br>
            Protocolo:</div></td>
          <td class="label"><div align="left"><br>
            Vers&atilde;o:</div></td>
        </tr>
        <tr> 
          <td height="1" bgcolor="#333333" colspan="3"></td>
        </tr>
        <tr> 
          <td nowrap><label>
            <select name="frm_id_tipo_protocolo" class="opcao_tabela" id="frm_id_tipo_protocolo">
              <option value="LDAP" selected>LDAP</option>
              <option value="Open LDAP">Open LDAP</option>
            </select>
          </label></td>
            <td class="label"><div align="left"><span class="label_peq_sem_fundo">
              <input name="frm_nu_versao_protocolo" type="text" size="30" maxlength="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_nu_versao_protocolo" >
            </span></div></td>
        </tr>
        <tr> 
          <td class="label"><br>
            String de Pesquisa: <span class="normal style2">(Ex.: o=dominio.com.br / DC=dominio, DC=com, DC=br)</span></td>
          <td class="label"><div align="left"><br>
            Observa&ccedil;&otilde;es:</div></td>
        </tr>
        <tr> 
          <td height="1" bgcolor="#333333" colspan="3"></td>
        </tr>
        <tr> 
          <td><span class="label_peq_sem_fundo">
            <input name="frm_te_string_DN" type="text" size="60" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_string_DN" >
          </span></td>
          <td><span class="label_peq_sem_fundo">
            <input name="frm_te_observacao" type="text" size="60" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_observacao" >
          </span></td>   
        </tr>
        <tr> 
          <td colspan="3">&nbsp;</td>
        </tr>
      </table>
      <p align="center"> 
        <input name="submit" type="submit" value="  Gravar Informa&ccedil;&otilde;es  " onClick="return Confirma('Confirma Inclus�o de Dom�nio?');">
      </p>
    </form>
    <p>
      <?
    }
?>
</p>
</body>
</html>