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

require_once('../../include/library.php');

// Comentado temporariamente - AntiSpy();
// Fun��o para replica��o do conte�do do REPOSIT�RIO nos servidores de UPDATES das redes cadastradas.
	if ($_REQUEST['v_parametros']<>'')
		{
		?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
		<html>
		<head>
		<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
	
		<title>Verifica&ccedil;&atilde;o/Atualiza&ccedil;&atilde;o dos Servidores de Updates</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		</head>
		<body background="../../imgs/linha_v.gif">
		<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>	
		
		<form name="frm_update_subredes" id="frm_update_subredes">
		<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr nowrap> 
		<td class="cabecalho">Verifica&ccedil;&atilde;o dos Servidores de Updates das Redes</td>
		</tr>
		<tr> 
		<td class="descricao">M&oacute;dulo para verifica&ccedil;&atilde;o/atualiza&ccedil;&atilde;o das vers&otilde;es 
		dos objetos localizados nos servidores de updates das redes monitoradas.</td>
		</tr>
		</table>
		<br>
		<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#666666">
		<tr bordercolor="#000000" bgcolor="#CCCCCC">	
		<td valign="center" class="cabecalho_tabela">
		<p align="left">IP da Rede</p>
		</td>

		<td valign="center" class="cabecalho_tabela">
		<p align="left">&nbsp;&nbsp;&nbsp;</p>
		</td>
		
		<td valign="center" class="cabecalho_tabela">
		<p align="left">Nome da Rede
		</p>
		</td>		
		<td valign="center" class="cabecalho_tabela">
		<p align="left">&nbsp;&nbsp;&nbsp;</p>
		</td>
	
		<td valign="center" class="cabecalho_tabela">
		<p align="left">Status</p>
		</td>			
		</tr>
	
		<?
		$v_array_parametros = explode('_-_',$_REQUEST['v_parametros']);

		$v_array_redes = explode('__',str_replace('_fr_',"'",$v_array_parametros[1]));	

		
		//echo '_REQUEST[v_parametros] = '.$_REQUEST['v_parametros'].'<br>';
		//echo 'v_array_parametros[1] = '.$v_array_parametros[1].'<br>';

		if (count($v_array_redes)>0)
			{
			for ($i = 0;$i < count($v_array_redes);$i++)
				{
				if ($v_where <> '') $v_where .= ' or ';
				$v_where .= ' id_ip_rede="'.$v_array_redes[$i].'"';
				}
			}

		$query_REDES= "	SELECT 	re.id_ip_rede,
								re.nm_rede,
								re.id_local,
								re.te_serv_updates
					 FROM		redes re 
					 WHERE " . $v_where . 
					" ORDER BY    re.nm_rede";
		conecta_bd_cacic();					
		$result_REDES = mysql_query($query_REDES);
		$_SESSION['v_tripa_servidores_updates'] = '';
		while ($row = mysql_fetch_array($result_REDES))
			{
			if ($v_array_parametros[2]<>'') // Se a op��o "For�ar" foi marcada...
				{	
				$query_del = "DELETE 
							  FROM 		redes_versoes_modulos 
				              WHERE 	id_local = ".$row['id_local'] ." AND 
							  			id_ip_rede in ('" . $row['id_ip_rede'] . "') and nm_modulo in (".str_replace('_fm_',"'",$v_array_parametros[2]).")";								
				conecta_bd_cacic();					

				$result_del = mysql_query($query_del) or die('Erro na Opera��o de DELETE ou sua sess�o expirou => Query_Del: '.$query_del.' <br> '.mysql_error());
				//break;				
				}
			
			if ($v_cor_zebra == '#FFFFFF') $v_cor_zebra = '#EEEEEE'; else $v_cor_zebra = '#FFFFFF';		

			?>		

			<tr> 
			<td valign="center" bgcolor="<? echo $v_cor_zebra;?>" class="opcao_tabela">
			<p align="left"><? echo $row['id_ip_rede']; ?></p>
			</td>
			<td valign="center" bgcolor="<? echo $v_cor_zebra;?>" class="opcao_tabela">
			<p align="left">&nbsp;&nbsp;&nbsp;</p>
			</td>
		
			<td valign="center" bgcolor="<? echo $v_cor_zebra;?>" nowrap class="opcao_tabela">
			<p align="left"><? echo $row['nm_rede']; ?></p>
			</td>
			<td valign="center" bgcolor="<? echo $v_cor_zebra;?>" class="opcao_tabela">
			<p align="left">&nbsp;&nbsp;&nbsp;</p>
			</td>
			<td valign="center" bgcolor="<? echo $v_cor_zebra;?>" nowrap class="opcao_tabela_blue">
			<p align="left">
			<?
			flush();
			$strTeServUpdatesToCheck = '#'.trim($row['te_serv_updates']).'#';
			if (@substr_count($_SESSION['v_tripa_servidores_updates'],$strTeServUpdatesToCheck)>0)
				{
				echo '<b>Verifica��o Efetuada!</b>&nbsp;&nbsp;<font color=black size=1>(Servidor de Updates Verificado Anteriormente!)</font>';
				flush();				
				}
			else
				{
				update_subredes($row['id_ip_rede'],'Pagina','__'.$v_array_parametros[0],$row['id_local']);			
				flush();
				if ($_SESSION['v_efetua_conexao_ftp'] > 0 && 
				    (($_SESSION['v_conta_objetos_atualizados'] +
					  $_SESSION['v_conta_objetos_nao_atualizados'] +
					  $_SESSION['v_conta_objetos_enviados'] +
					  $_SESSION['v_conta_objetos_nao_enviados'])==0))
					{	
					echo '<b>Verifica��o Efetuada!</b>';												
					}									
				else if($_SESSION['v_status_conexao'] == 'NC')
					{
					echo '<a href="../redes/detalhes_rede.php?id_ip_rede='. $row['id_ip_rede'] .'&id_local='.$row['id_local'].'" style="color: red"><strong>FTP n�o configurado!</strong></a>';										
					}
				else if($_SESSION['v_status_conexao'] == 'OFF')
					{
					echo '<a href="../redes/detalhes_rede.php?id_ip_rede='. $row['id_ip_rede'] .'&id_local='.$row['id_local'].'" style="color: red"><strong>Servidor OffLine!</strong></a>';																				
					}
				flush();
				}
				session_unregister('v_status_conexao');					
			?>
			</p>
			</td>			
			</tr>			
			<?
			}
			?>
			<?
		$_SESSION['v_conta_objetos_enviados'] 			= 	0;
		$_SESSION['v_conta_objetos_nao_enviados']		= 	0;
		$_SESSION['v_conta_objetos_atualizados']		=	0;
		$_SESSION['v_conta_objetos_nao_atualizados']	= 	0;
		
		session_unregister('v_conta_objetos_enviados');
		session_unregister('v_conta_objetos_nao_enviados');
		session_unregister('v_conta_objetos_atualizados');
		session_unregister('v_conta_objetos_nao_atualizados');
		session_unregister('v_tripa_servidores_updates');	
		session_unregister('v_efetua_conexao_ftp');
		session_unregister('v_conexao_ftp');
	
		?>
		
		<tr bordercolor="#000000" bgcolor="#999999">
		<td valign="center" class="opcao_tabela">
		<p align="left">&nbsp;</p>
		</td>
		<td valign="center" class="opcao_tabela">
		<p align="left">&nbsp;</p>
		</td>
	
		<td valign="center" class="opcao_tabela">
		<p align="left">&nbsp;</p>
		</td>		
		<td valign="center" class="opcao_tabela">
		<p align="left">&nbsp;</p>
		</td>
	
		<td valign="center" class="opcao_tabela">
		<p align="left">&nbsp;</p>
		</td>			
		</tr>	
	
		</table>	
		
</form>
</body>
		</html>
		<?
		flush();
		}
		?>