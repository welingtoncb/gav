<?php
	include ("includes/inc_geral.php");
	
	$goBD = new db();
	$goBD->Usuario = $gsUsuarioBanco1;
	$goBD->Local = $gsLocalBanco1; 
	$goBD->Senha = $gsSenhaBanco1; 
	$goBD->Banco = $gsNomeBanco1; 
	$goBD->Tipo = $gsTipoBanco1; 
	$goBD->Conecta();
	$goBD->AcessaBanco("gav");

	$sql = "SELECT CodUsuario, Login FROM usuario WHERE Login='".$_POST["tbUsuario"]."' AND Senha = PASSWORD('".$_POST["tbSenha"]."')";	
	$rst = $goBD->Consulta($sql);
	if($goBD->Erro) 
	{
		/* Erro ao acessar tabela de usu�rio */
		$goBD->Desconecta();	
		echo '<script language="Javascript">'.chr(10);
			echo 'alert("Erro ao acessar tabela de usuario!")'.chr(10);
			echo 'window.location.href="login.php"'.chr(10);
		echo "</script>".chr(10);
	}
	
	if($goBD->EOF($rst))
	{
		/* Usu�rio e/ou senha inv�lidos */
		$goBD->Desconecta();	
		echo '<script language="Javascript">'.chr(10);
			echo 'alert("Usu�rio ou senha inv�lidos!")'.chr(10);
			echo 'window.location.href="login.php"'.chr(10);
		echo "</script>".chr(10);
	}
	else
	{
		header("Location: cad_produto.php");
	}
?>