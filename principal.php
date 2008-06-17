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
@session_start(); 
require_once("include/library.php");
?>
<html>
<head>
<title><?= $oTranslator->_('kciq_msg statistics');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="refresh" content="180">
<link rel="stylesheet"   type="text/css" href="include/cacic.css">
</head>

<body bgcolor="#FFFFFF" background="imgs/linha_v.gif">
<table width="90%" border="0" align="center">
<tr>
<td class="cabecalho"><?= $oTranslator->_('kciq_msg cacic statistics');?></td>
</tr>
<? 
// Reinicializo as vari�veis para tratamento de gr�ficos
session_unregister('te_exibe_graficos');

// Resgato a configura��o sobre exibi��o dos gr�ficos da p�gina principal
$_SESSION['te_exibe_graficos'] = get_valor_campo('configuracoes_locais', 'te_exibe_graficos', 'id_local='.$_SESSION['id_local']);			

// Caso o usu�rio atual n�o esteja logado, mostro apenas os n�meros de acessos por local
if (!session_is_registered('cs_nivel_administracao'))
	{		
	?>
	<tr><td>
	<?
	$_SESSION['in_grafico']	= 'acessos_locais';
	if (substr_count($_SESSION['te_exibe_graficos'],'[acessos_locais]')>0)
		echo '<img src="graficos/pie_acessos_locais.php" border="no">';
	else
		require "include/exibe_consultas_texto.php";					
	?>
	</td>
	</tr>
	<tr><td></td></tr>
	<tr>
	<td class="descricao"><div align="center"><?= $oTranslator->_('kciq_msg computadores monitorados hoje');?></div></td>
	</tr>  
	<tr> 
	<td height="1"  bgcolor="#e7e7e7"></td>
	</tr>
	<tr> 
	<td>&nbsp;</td>
	</tr>
	<?
	}	
else
	{
	?>
	<tr><td>
	<?
	$_SESSION['in_grafico']	= 'so';	
	if ($_SESSION["cs_nivel_administracao"] <> 0)
		echo '<a href="relatorios/software/rel_software.php?orderby=4&principal=so">';
			
	if (substr_count($_SESSION['te_exibe_graficos'],'[so]')>0)
		echo '<img src="graficos/pie_so.php" border="no">';		
	else
		include "include/exibe_consultas_texto.php";					
	
	if ($_SESSION["cs_nivel_administracao"] <> 0)
		echo '</a>';
			
	$te_title = $oTranslator->_('kciq_msg total of computers per os');
	?>
	</td>	
	</tr>
	<tr><td></td></tr>	
	<tr> 
	<td class="descricao"><div align="center">
	<?
	if ($_SESSION["cs_nivel_administracao"] <> 0)
		echo '<a href="relatorios/software/rel_software.php?orderby=4&principal=so">';

	echo $te_title;

	if ($_SESSION["cs_nivel_administracao"] <> 0)
		echo '</a>';
	?>
	</div></td>
	</tr>  

	<?
	if ($_SESSION['te_locais_secundarios']  <> '' || 
	    $_SESSION["cs_nivel_administracao"] == 1  || 
		$_SESSION["cs_nivel_administracao"] == 2  || 
		$_SESSION["cs_nivel_administracao"] == 3)
		echo '<tr><td class="label_peq_sem_fundo"><div align="center">'.$oTranslator->_('kciq_msg total referente a multi-locais').' (<a href="#" onclick="MyWindow=window.open(\'graficos/detalhes_estatisticas.php?in_grafico=so&te_exibe_graficos='.$_SESSION['te_exibe_graficos'].'&te_title='.$te_title.'\', \'JANELA\',\'toolbar=no,location=no,width=795,left=150,height=600,top=50,scrollbars=yes,menubar=no\');MyWindow.document.close()"><font color="#FF0000"><b>'.$oTranslator->_('kciq_msg details').'</b></font></a>)</div></td></tr>';
		?>
	
	<tr> 
	<td height="1"  bgcolor="#e7e7e7"></td>
	</tr>
	
	<tr> 
	<td>&nbsp;</td>
	</tr>
	<tr> 
	<td>
	<? 
	$_SESSION['in_grafico']	= 'acessos';	
	$te_title = $oTranslator->_('kciq_msg last agents access');
		
	if ($_SESSION["cs_nivel_administracao"] <> 0)
		echo '<a href="relatorios/software/rel_software.php?orderby=6&principal=acessos">';

	if (substr_count($_SESSION['te_exibe_graficos'],'[acessos]')>0)
		echo '<img src="graficos/pie_acessos.php" border="no">';
	else
		require "include/exibe_consultas_texto.php";					
			
	if ($_SESSION["cs_nivel_administracao"] <> 0)
		echo '</a>';
	?>
	<tr><td></td></tr>		
	<tr> 
	<td class="descricao"><div align="center">	
	<?
	if ($_SESSION["cs_nivel_administracao"] <> 0)
		echo '<a href="relatorios/software/rel_software.php?orderby=6&principal=acessos">';

	echo $te_title;

	if ($_SESSION["cs_nivel_administracao"] <> 0)
		echo '</a>';
	?>
	</div></td>
	</tr>  
	</td>
	</tr>
	<?
	$te_title = $oTranslator->_('kciq_msg last agents access per local');
	if ($_SESSION['te_locais_secundarios']  <> '' || 
	    $_SESSION["cs_nivel_administracao"] == 1  || 
		$_SESSION["cs_nivel_administracao"] == 2  || 
		$_SESSION["cs_nivel_administracao"] == 3)
		echo '<tr><td class="label_peq_sem_fundo"><div align="center">'.$oTranslator->_('kciq_msg total referente a multi-locais').' (<a href="#" onclick="MyWindow=window.open(\'graficos/detalhes_estatisticas.php?in_grafico=acessos&te_exibe_graficos='.$_SESSION['te_exibe_graficos'].'&te_title='.$te_title.'\', \'JANELA\',\'toolbar=no,location=no,width=795,left=150,height=600,top=50,scrollbars=yes,menubar=no\');MyWindow.document.close()"><font color="#FF0000"><b>'.$oTranslator->_('kciq_msg details').'</b></font></a>)</div></td></tr>';
	else
		{
		?>
		<tr><td></td></tr>		
		<tr> 
		<td class="descricao"><div align="center"><?=$oTranslator->_('kciq_msg last agents access on local');?></div></td>
		</tr>
		<?
		}
		?>
	<tr> 
	<td height="1"  bgcolor="#e7e7e7"></td>
	</tr>
	
	<tr> 
	<td>&nbsp;</td>
	</tr>
	<?
	if ($_SESSION["cs_nivel_administracao"] == 1 ||
		$_SESSION["cs_nivel_administracao"] == 2 ||
		$_SESSION["cs_nivel_administracao"] == 3)
		{
		?>
		<tr><td>
		<?
		$_SESSION['in_grafico']	= 'locais';		
		if (substr_count($_SESSION['te_exibe_graficos'],'[locais]')>0)
			echo '<img src="graficos/pie_locais.php" border="no">';
		else
			require "include/exibe_consultas_texto.php";					
		
		?>
		</td>
		</tr>
		<tr><td></td></tr>		
		<tr>
		<td class="descricao"><div align="center"><?=$oTranslator->_('kciq_msg total of computers per local');?></div></td>
		</tr>  
		<tr> 
		<td height="1"  bgcolor="#e7e7e7"></td>
		</tr>
		<tr> 
		<td>&nbsp;</td>
		</tr>
		<?
		}
		?>
		<tr> 
		<td>&nbsp;</td>
		</tr>
		<tr> 
		<td>
		<? 
		if ($_SESSION["cs_nivel_administracao"] <> 0)
			echo '<a href="relatorios/software/rel_software.php?orderby=6&principal=acessos_locais">';
			
		$_SESSION['in_grafico']	= 'acessos_locais';	
			
		if (substr_count($_SESSION['te_exibe_graficos'],'[acessos_locais]')>0)
			echo '<img src="graficos/pie_acessos_locais.php" border="no">';
		else
			require "include/exibe_consultas_texto.php";					
		
		if ($_SESSION["cs_nivel_administracao"] <> 0)
			echo '</a>';
		?>	
		</td>
		</tr>
		<tr><td></td></tr>		
		<tr> 
		<td class="descricao"><div align="center"><? echo $te_title;?></div></td>
		</tr>
		<tr> 
		<td height="1"  bgcolor="#e7e7e7"></td>
		</tr>
	
	<tr> 
	<td>
	<? 
	$_SESSION['in_grafico'] = 'mac';		
	
	// Insiro a consulta espec�fica para Mac
	require "include/monta_consulta_mac.php";			

	require "include/exibe_consultas_texto.php";					

	$te_title = $oTranslator->_('kciq_msg real total of computers mac based');
	?>	
	</td>
	</tr>
	<tr><td></td></tr>	
	<tr> 
	<td class="descricao"><div align="center"><? echo $te_title;?></div></td>
	</tr>
	<?
	/*
	if ($_SESSION['te_locais_secundarios']  <> '' || 
	    $_SESSION["cs_nivel_administracao"] == 1  || 
		$_SESSION["cs_nivel_administracao"] == 2  ||
		$_SESSION["cs_nivel_administracao"] == 3)
		{
		echo '<tr><td class="label_peq_sem_fundo"><div align="center"><b>ATEN��O:</b> Informa��o referente a mais de uma localidade. (<a href="#" onclick="MyWindow=window.open(\'graficos/detalhes_estatisticas.php\', \'JANELA\',\'toolbar=no,location=no,width=600,left=200,height=600,top=50,scrollbars=yes,menubar=no\');MyWindow.document.close()"><font color="#FF0000"><b>Detalhes</b></font></a>)</div></td></tr>';
		}
		*/
		?>

	<tr> 
	<td height="1"  bgcolor="#e7e7e7"></td>
	</tr>
	</table>
	<?
	}
	?>
<table width="90%">
<tr><td height="30"></td></tr>
<tr><td class="descricao">
<p align="center">Desenvolvido pela Dataprev - Unidade Regional Esp&iacute;rito Santo 
<p align="center"><a href="http://www.anybrowser.org/campaign/anybrowser_br.html" target="_blank"><img src="imgs/anybrowser.gif" alt="Vis&iacute;vel por qualquer browser" width="88" height="31" border="0"></a>
</td></tr>
</table>
</body>
</html>