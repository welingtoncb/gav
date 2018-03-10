<!doctype html>
<?php
	if(!isset($tbUsuario)) {$tbUsuario = "";}
?>
<html>
<head>
<title>GAV - Login</title>
<meta charset="iso-8859-1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<script language="javascript" type="text/javascript">			
	function ValidaLogin()
	{		
		if (document.form1.tbUsuario.value == "")
		{
			alert("Informe o nome do usuario !");
			document.form1.tbUsuario.focus();
			return false;
		}
		
		if (document.form1.tbSenha.value == "")
		{
			alert("Informe a senha do usuario !");
			document.form1.tbSenha.focus();
			return false;
		}
		
		document.form1.submit();
		return false;
	}
	
	function SubmeteTela()
	{
		if(navigator.appName.indexOf("Netscape")==-1 ) //Se for InternetExplorer
		{
			if((event.keyCode==13) && (document.form1.tbUsuario.value!='') && (document.form1.tbSenha.value!=''))
			{
				ValidaLogin();
				return true;
			}
		}
		else
	    {
			document.captureEvents(Event.KEYPRESS);
			document.onkeypress = PressionouNetscape;
		}
	}

	function PressionouNetscape(e)
	{
		if((e.which==13) && (document.form1.tbUsuario.value!='') && (document.form1.tbSenha.value!='')){
			ValidaLogin();
		}
	}
	
	function SelecionaCampo()
	{
		if(document.form1.tbUsuario.value.length >0)
			{document.form1.tbSenha.focus();}
		else
			{document.form1.tbUsuario.focus();}
	}
</script>
</head>
<body onLoad="SelecionaCampo()">

<form action="processa_login.php" method="post" name="form1" target="_self">
<div style="top:180px; left:500px; position:absolute;">
    <table>
    	<tr>
    		<td align="right"><label>Usuario &nbsp;</label></td>
    		<td><input class="form-control" name="tbUsuario" type="text" size="18" maxlength="100" onKeyUp="SubmeteTela()"></td>
    	</tr>
        
    	<tr>
    		<td align="right"><label>Senha &nbsp;</label></td>
    		<td><input class="form-control" name="tbSenha" type="password" size="18" maxlength="100" class="texto3" onKeyUp="SubmeteTela()"></td>
    	</tr>
    	<tr>
    		<td align="center"><input name="btEntra" type="button" class="btn btn-primary" value="Entrar" onClick="ValidaLogin();"></td>
    	</tr>
    </table>
</div>
</form>
</body>
</html>