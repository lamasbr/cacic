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
  die('Acesso negado!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

?>
<html>
<head>
<title>Configurar Gerente</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" src="../include/cacic.js"></script>
<?
require_once('../include/selecao_listbox.js');
?>
<link rel="stylesheet"   type="text/css" href="../include/cacic.css">
<SCRIPT LANGUAGE="JavaScript">
function valida_notificacao_hardware() 
	{
	if ( (document.forma.listaHardwareSelecionados.length > 0 && 
	      document.forma.te_notificar_mudanca_hardware.value == '') ||
	     (document.forma.listaHardwareSelecionados.length == 0 && 
	      document.forma.te_notificar_mudanca_hardware.value != '')) 
		{	
		alert("ATEN��O: Verifique os campos para notifica��o de altera��o de hardware");
		if (document.forma.listaHardwareSelecionados.length == 0)
			document.forma.listaHardwareSelecionados.focus()
		else
	      document.forma.te_notificar_mudanca_hardware.focus();

		return false;

		}	
	}

function SetaServidorUpdates()	
	{
	document.forma.frm_te_serv_updates_padrao.value = document.forma.sel_te_serv_updates.value;	
	}

</script>

</head>

<body background="../imgs/linha_v.gif" onLoad="SetaCampo('te_notificar_mudanca_hardware');">
<?
$frm_id_local = ($_POST['frm_id_local']<>''?$_POST['frm_id_local']:$_SESSION['id_local']);

require_once('../include/library.php');
conecta_bd_cacic();
$where = ' AND loc.id_local ='.$frm_id_local;

if ($_SESSION['te_locais_secundarios'])
	{
	$where = str_replace('loc.id_local',' (loc.id_local',$where);
	$where .= ' OR (loc.id_local IN ('.$_SESSION['te_locais_secundarios'].'))) ';
	}
	
$queryConfiguracoesLocais = "	SELECT 			loc.id_local,
												loc.sg_local,
												loc.nm_local,
												c_loc.te_notificar_mudanca_hardware,
												c_loc.te_exibe_graficos,
												c_loc.te_serv_cacic_padrao,
												c_loc.te_serv_updates_padrao
								FROM 			locais loc,
												configuracoes_locais c_loc
								WHERE 			loc.id_local = c_loc.id_local ";
$orderby = ' ORDER BY loc.sg_local';
$resultConfiguracoesLocais = mysql_query($queryConfiguracoesLocais.$where.$orderby) or die('1-Select Imposs�vel nas tabelas Locais/Configuracoes_Locais ou sua sess�o expirou!');
$row_configuracoes_locais = mysql_fetch_array($resultConfiguracoesLocais);
if ($_SESSION['cs_nivel_administracao'] == 1 || $_SESSION['cs_nivel_administracao'] == 2 || ($_SESSION['cs_nivel_administracao'] == 3 && $_SESSION['te_locais_secundarios']<>''))
	{	
	?>
	<div id="LayerLocais" style="position:absolute; width:200px; height:115px; z-index:1; left: 0px; top: 0px; visibility:hidden">
	<?

	$resultConfiguracoesLocais = mysql_query($queryConfiguracoesLocais.$orderby) or die('2-Select Imposs�vel nas tabelas Locais/Configuracoes_Locais ou sua sess�o expirou!');

	echo '<select name="SELECTconfiguracoes_locais">';
	while ($rowConfiguracoesLocais = mysql_fetch_array($resultConfiguracoesLocais))
		{
		echo '<option id="'.$rowConfiguracoesLocais['id_local'].'" value="'. $rowConfiguracoesLocais['nm_local'].'#'.
																			 $rowConfiguracoesLocais['te_notificar_mudanca_hardware'].'#'.
																			 $rowConfiguracoesLocais['te_exibe_graficos'].'#'.
																			 $rowConfiguracoesLocais['te_serv_cacic_padrao'].'#'.																		
																			 $rowConfiguracoesLocais['te_serv_updates_padrao'].'">'.$rowConfiguracoesLocais['nm_local'].'</option>';					
		}
	echo '</select>';		

	$queryDescricaoHardware = "	SELECT 		nm_campo_tab_hardware,
											te_desc_hardware,
											te_locais_notificacao_ativada
								FROM 		descricao_hardware
								ORDER BY	te_desc_hardware";

	$resultDescricaoHardware = mysql_query($queryDescricaoHardware) or die('3-Select Imposs�vel na tabela Descricao_Hardware ou sua sess�o expirou!');

	echo '<select name="SELECTdescricao_hardware">';
	while ($rowDescricaoHardware = mysql_fetch_array($resultDescricaoHardware))
		{
		echo '<option value="'.$rowDescricaoHardware['te_locais_notificacao_ativada'].'" id="'. $rowDescricaoHardware['nm_campo_tab_hardware'].'">'.$rowDescricaoHardware['te_desc_hardware'].'</option>';					
		}
	echo '</select>';		
		
	?>
	</div>
	<?
	}
	?>
<script language="JavaScript" type="text/javascript" src="../include/cacic.js"></script>
<script language="JavaScript" type="text/javascript" src="../include/setLocalConfigGerais.js"></script>
	<form action="config_gerais_set.php"  method="post" ENCTYPE="multipart/form-data" name="forma" onSubmit="return valida_form();return valida_notificacao_hardware();">
<table width="90%" border="0" align="center">

  <tr> 
      <td class="cabecalho">Configura&ccedil;&otilde;es do M&oacute;dulo Gerente</td>
  </tr>
  <tr> 
      <td class="descricao">As op&ccedil;&otilde;es abaixo determinam como o m&oacute;dulo gerente dever&aacute; se comportar.</td>
  </tr>
</table>
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
  	<? 

	// Ser� mostrado apenas para os n�veis Administra��o, Gest�o Central e Supervis�o com acessos a locais secund�rios.
	if ($_SESSION['cs_nivel_administracao'] == 1 || $_SESSION['cs_nivel_administracao'] == 2 || ($_SESSION['cs_nivel_administracao'] == 3 && $_SESSION['te_locais_secundarios']<>''))
		{
		?>
	    <tr> 
	    <td class="label"><br>Locais: </td>
    	</tr>  
    	<tr> 
      	<td height="1" bgcolor="#333333"></td>
    	</tr>
    	<tr> 	
		<td>
		<?
		if ($_SESSION['cs_nivel_administracao'] == 1 || $_SESSION['cs_nivel_administracao'] == 2)
			$where = '';
			
    	conecta_bd_cacic();
		$query_locais = "SELECT		loc.id_local,
									loc.nm_local,
									loc.sg_local
					  	FROM		locais loc 
						WHERE 		1 ". // Somente para reaproveitar a defini��o de where feita anteriormente...
						$where . " 
				  		ORDER BY  	loc.sg_local"; 
		$result_locais = mysql_query($query_locais) or die('4-Ocorreu um erro durante a consulta � tabela de Locais ou sua sess�o expirou!'); 

		?>
    	<select size="5" name="SELECTlocais"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" onChange="setLocal(this);">	
    	<? 		
		while ($row_locais = mysql_fetch_array($result_locais))
			{
			echo '<option id="'.$row_locais['id_local'].'" value="'. $row_locais['id_local'].'"';
			if ($row_locais['id_local']==$frm_id_local) 
				echo '  selected="selected"';
						
			echo '>'.$row_locais['sg_local'].' - '.$row_locais['nm_local'].'</option>';					
			}
 		?> 
    	</select>
		</td>
    	</tr>
		<?
		}
		?>
		
    <tr> 
      <td class="label"> 
        &nbsp; &nbsp;<br>
        Nome da organiza&ccedil;&atilde;o/empresa/&oacute;rg&atilde;o: </td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><p> 
          <? // Aten��o: o campo abaixo deve estar em "disabled", pois, a altera��o desse valor s� ser� permitida ao n�vel 
		   //          Administra��o, na op��o Administra��o/Cadastros/Locais ?>
          <input name="nm_organizacao" id="nm_organizacao" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" type="text" value="<? echo $row_configuracoes_locais['nm_local']; ?>" size="65" disabled>
          <input name="frm_id_local" id="frm_id_local" type="hidden" value="<? echo $frm_id_local; ?>">		  
        </p></td>
    </tr>
    <tr> 
      <td height="17">&nbsp;</td>
    </tr>
    <tr> 
      <td class="label">Notificar os seguintes e-mails ao detectar altera&ccedil;&otilde;es 
        nas configura&ccedil;&otilde;es de hardware: </td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><p> 
          <textarea name="te_notificar_mudanca_hardware" cols="55"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="te_notificar_mudanca_hardware"><? echo $row_configuracoes_locais['te_notificar_mudanca_hardware']; ?></textarea>
        </p></td>
    </tr>
    <tr> 
      <td class="ajuda">Aten&ccedil;&atilde;o: informe os e-mails separados por 
        v&iacute;rgulas (&quot;,&quot;). <br>
        Exemplo: fulano.tal@previdencia.gov.br, luis.almeida@xyz.com</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td class="label">Realizar notifica&ccedil;&atilde;o caso haja altera&ccedil;&otilde;es 
              nas seguintes configura&ccedil;&otilde;es de hardware: </td>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td height="1" class="label"><table border="0" cellpadding="0" cellspacing="0">
                <tr> 
                  <td>&nbsp;&nbsp;</td>
                  <td class="label"><div align="left">Dispon&iacute;veis:</div></td>
                  <td>&nbsp;&nbsp;</td>
                  <td width="40">&nbsp;</td>
                  <td nowrap>&nbsp;&nbsp;</td>
                  <td nowrap class="label"><p>Selecionadas:</p></td>
                  <td nowrap>&nbsp;&nbsp;</td>
                </tr>
                <tr> 
                  <td>&nbsp;</td>
                  <td> <div align="left"> 
                      <?    
				        /* Consulto todos os hardwares que foram previamente selecionados. */ 
			  	$query = "SELECT nm_campo_tab_hardware, te_desc_hardware
						  FROM   descricao_hardware 
						  WHERE  ".$frm_id_local." IN (te_locais_notificacao_ativada)";
						$result_hardwares_ja_selecionados = mysql_query($query) or die('5-Ocorreu um erro durante a consulta � tabela descricao_hardware (1) ou sua sess�o expirou!');
						
						/* Agora monto os itens do combo de hardwares selecionadas. */ 
				while($campos_hardwares_selecionados = mysql_fetch_array($result_hardwares_ja_selecionados)) 
					{
					$itens_combo_hardwares_selecionados = $itens_combo_hardwares_selecionados . '<option value="' . $campos_hardwares_selecionados['nm_campo_tab_hardware']. '">' . $campos_hardwares_selecionados['te_desc_hardware'] . '</option>'; 
//						   $not_in_ja_selecionados = $not_in_ja_selecionados . "'" . $campos_hardwares_selecionados['nm_campo_tab_hardware'] .  "',";
					}
						
						/* Consulto as hardwares que n�o foram previamente selecionadas. */ 
			  	$query = "SELECT nm_campo_tab_hardware, te_desc_hardware
						  FROM   descricao_hardware 
						  WHERE  ".$frm_id_local." NOT IN (te_locais_notificacao_ativada)";
						$result_hardwares_nao_selecionados = mysql_query($query) or die('6-Ocorreu um erro durante a consulta � tabela descricao_hardware (2) ou sua sess�o expirou!');
						/* Agora monto os itens do combo de hardwares N�O selecionadas. */ 
       		while($campos_hardwares_nao_selecionados=mysql_fetch_array($result_hardwares_nao_selecionados)) 	
				{
				$itens_combo_hardwares_nao_selecionados = $itens_combo_hardwares_nao_selecionados . '<option value="' . $campos_hardwares_nao_selecionados['nm_campo_tab_hardware']. '">' . $campos_hardwares_nao_selecionados['te_desc_hardware']  . '</option>';
				}  ?>
                      <select multiple size="10" name="list1[]" id="listaHardwareDisponiveis"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                        <? echo $itens_combo_hardwares_nao_selecionados; ?> 
                      </select>
                    </div></td>
                  <td>&nbsp;</td>
                  <td width="40"> <div align="center"> 
                      <input type="button" value="   &gt;   " onClick="move(this.form.elements['list1[]'],this.form.elements['list2[]'])" name="B1">
                      <br>
                      <br>
                      <input type="button" value="   &lt;   " onClick="move(this.form.elements['list2[]'],this.form.elements['list1[]'])" name="B2">
                    </div></td>
                  <td>&nbsp;</td>
                  <td><select multiple size="10" name="list2[]" id="listaHardwareSelecionados"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
                      <? echo $itens_combo_hardwares_selecionados; ?> </select></td>
                  <td>&nbsp;</td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td height="1" class="ajuda">&nbsp;&nbsp;&nbsp;(Dica: use SHIFT ou 
              CTRL para selecionar m&uacute;ltiplos itens)</td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td class="label">Exibir Gr&aacute;ficos na P&aacute;gina Principal e Detalhes:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td height="1" class="label"><table border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td>&nbsp;&nbsp;</td>
            <td class="label"><div align="left">Dispon&iacute;veis:</div></td>
            <td>&nbsp;&nbsp;</td>
            <td width="40">&nbsp;</td>
            <td nowrap>&nbsp;&nbsp;</td>
            <td nowrap class="label"><p>Selecionados:</p></td>
            <td nowrap>&nbsp;&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> <div align="left"> 
			<?
			// Gr�ficos dispon�veis para exibi��o na p�gina principal
			// [so][acessos][locais][acessos_locais]
			// A vari�vel de sess�o menu_seg->_SESSION['te_exibe_graficos'] cont�m os gr�ficos selecionados para exibi��o
			$te_exibe_graficos = get_valor_campo('configuracoes_locais', 'te_exibe_graficos', 'id_local='.$frm_id_local);			

			?>
                <select multiple size="10" name="list3[]" id="listaExibeGraficosDisponiveis" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
					<? if (substr_count($te_exibe_graficos,'[so]')==0)
							echo '<option value="[so]">Totais de Computadores por Sistemas Operacionais</option>';
					   if (substr_count($te_exibe_graficos,'[acessos]')==0)
		                    echo '<option value="[acessos]">&Uacute;ltimos Acessos dos Agentes do Local</option>';
					   if (substr_count($te_exibe_graficos,'[acessos_locais]')==0)
					   		echo '<option value="[acessos_locais]">&Uacute;ltimos Acessos dos Agentes por Local na Data</option>';
					   if (substr_count($te_exibe_graficos,'[locais]')==0)							
		                    echo '<option value="[locais]">Totais de Computadores Monitorados por Local</option>';
					?>
                </select>
              </div></td>
            <td>&nbsp;</td>
            <td width="40"> <div align="center"> 
                <input type="button" value="   &gt;   " onClick="move(this.form.elements['list3[]'],this.form.elements['list4[]'])" name="B3">
                <br>
                <br>
                <input type="button" value="   &lt;   " onClick="move(this.form.elements['list4[]'],this.form.elements['list3[]'])" name="B4">
              </div></td>
            <td>&nbsp;</td>
            <td><select multiple size="10" name="list4[]" id="listaExibeGraficosSelecionados"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
					<? if (substr_count($te_exibe_graficos,'[so]')>0)
							echo '<option value="[so]">Totais de Computadores por Sistemas Operacionais</option>';
					   if (substr_count($te_exibe_graficos,'[acessos]')>0)
		                    echo '<option value="[acessos]">&Uacute;ltimos Acessos dos Agentes do Local</option>';
					   if (substr_count($te_exibe_graficos,'[acessos_locais]')>0)
					   		echo '<option value="[acessos_locais]">&Uacute;ltimos Acessos dos Agentes por Local na Data</option>';
					   if (substr_count($te_exibe_graficos,'[locais]')>0)							
		                    echo '<option value="[locais]">Totais de Computadores Monitorados por Local</option>';
					?>

				</select></td>
            <td>&nbsp;</td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td class="label">Servidor de Aplica&ccedil;&atilde;o padr&atilde;o:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><p><strong> 
          <select name="frm_te_serv_cacic_padrao" id="frm_te_serv_cacic_padrao" onChange="SetaServidorBancoDadosPadrao();"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
            <option value="0">===> Selecione <===</option>
        <?
		$query_configuracoes_padrao = "SELECT	Distinct te_serv_cacic_padrao,
		                                        		 te_serv_updates_padrao
						 			   FROM		configuracoes_padrao"; 

		$result_configuracoes_padrao = mysql_query($query_configuracoes_padrao) or die('7-Ocorreu um erro durante a consulta � tabela de configura��es ou sua sess�o expirou!'); 
		
		$v_achei = 0;
		while ($row_configuracoes_padrao=mysql_fetch_array($result_configuracoes_padrao))
			{ 
			$v_achei = 1;
			echo "<option value=\"" . $row_configuracoes_padrao["te_serv_cacic_padrao"] . "\"";
			if ($row_configuracoes_padrao['te_serv_cacic_padrao'] == $row_configuracoes_locais["te_serv_cacic_padrao"]) echo " selected ";
			echo ">" . $row_configuracoes_padrao["te_serv_cacic_padrao"] . "</option>";
			}

		if ($v_achei == 0)			
			{			
			echo "<option value=\"" . $_SERVER['HTTP_HOST'] . "\"";
			echo ">" . $_SERVER['HTTP_HOST'] . "</option>";			
			}
			?>
          </select>
          </strong></p></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td class="label">Servidor de Updates padr&atilde;o:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><p> 
          <select name="frm_te_serv_updates_padrao" id="frm_te_serv_updates_padrao" onChange="SetaServidorUpdatesPadrao();"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
            <option value="0">===> Selecione <===</option>
            <?
			mysql_data_seek($result_configuracoes_padrao,0);			
			while ($row_configuracoes_padrao=mysql_fetch_array($result_configuracoes_padrao))
				{ 
				echo "<option value=\"" . $row_configuracoes_padrao["te_serv_updates_padrao"] . "\"";
				if ($row_configuracoes_locais['te_serv_updates_padrao'] == $row_configuracoes_padrao["te_serv_updates_padrao"]) echo " selected ";
				echo ">" . $row_configuracoes_padrao["te_serv_updates_padrao"] . "</option>";
			   	} 			
			?>
          </select>
        </p></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><div align="center"> 
          <input name="submit" type="submit" value="  Gravar Informa&ccedil;&otilde;es   " onClick="SelectAll(this.form.elements['list2[]']),SelectAll(this.form.elements['list4[]'])" <? echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
        </div></td>
    </tr>
  </table>
</form>		  
<p>&nbsp;</p>
</body>
</html>