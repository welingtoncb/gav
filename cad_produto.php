<!doctype html>
<?php
	include ("includes/inc_geral.php"); 
	
	if(!isset($hdAcao)) {$hdAcao = "";}	

	$bdConexao = new db();
	$bdConexao->Usuario = $gsUsuarioBanco1;
	$bdConexao->Local = $gsLocalBanco1; 
	$bdConexao->Senha = $gsSenhaBanco1; 
	$bdConexao->Banco = $gsNomeBanco1; 
	$bdConexao->Tipo = $gsTipoBanco1; 	
	$bdConexao->Conecta();
	$bdConexao->AcessaBanco("gav");
				
	if($hdAcao == "INSERE")
	{
	  if($_SESSION['ControlaSubmit'] == $_POST['hdCoordSubmit'])
      {
			$_SESSION['ControlaSubmit'] += 1;
			
			$bOKInsere = true;
			
			
			$bdConexao->BeginTransaction();

			$sql = "INSERT INTO produtos (Nome, DtEntrada, VrCompra, Descricao, imagem)";
			$sql.= " VALUES ";
			$sql.= "('".addslashes($tbNome)."','".addslashes($tbUnidade)."','".addslashes($tbDescUnidade)."','".$cmbTipoItem."', '".addslashes($tbMercosul)."',".$cmbGrupo.",'".$rdModulo."','".addslashes($txrDescricao)."','".$tbCFOP."', '".$tbCST."', '".$tbNCMSH."', '".$rdFabricacaoP."')";
			$rst = $bdConexao->Consulta($sql);
			//echo $sql;
			$nCodigoProduto = $bdConexao->CodigoInserido();
			if($bdConexao->Erro)
				{$bOKInsere = false;}
			
			
			if($bOKInsere)
			{	
				  $bdConexao->CommitTransaction();
				  $sMensagem .= '<label>Dados cadastrados com Sucesso!</label>';			
				 
			}
			else
			{
				$bdConexao->RollbackTransaction();
				$sMensagem  = '<label>Erro ao cadastrar dados!</label>';
			}				
	   } 
	}

	if($hdAcao == "ALTERA")
	{
	  if($_SESSION['ControlaSubmit'] == $_POST['hdCoordSubmit'])
      {
        $_SESSION['ControlaSubmit'] += 1;
		$sql = "UPDATE produtos SET ";
		$rstOperacao = $bdConexao->Consulta($sql);
		if(!$bdConexao->Erro){
			$sMensagem .= '<label>Dados alterados com Sucesso!</label>';}						
		else{
			$sMensagem = '<label class="mensagem_erro">Erro ao alterar dados ! ERRO NO.'.$bdConexao->CodigoErro.' :: '.$bdConexao->DescricaoErro .'</label>';}				
	  } 
	  else 
	  { 
	  }
	}
	
	if($hdAcao == "EXCLUE")
	{
		if($_SESSION['ControlaSubmit'] == $_POST['hdCoordSubmit'])
		{
			$_SESSION['ControlaSubmit']+= 1;

			$sMensagem = "";
		
			$sql = "DELETE FROM produtos WHERE CodProduto = ".$_POST["hdCodProduto"];
			$rstOperacao = $bdConexao->Consulta($sql); 
			if($bdConexao->Erro)
				{$sMensagem .= '<label class="mensagem_erro">Erro ao excluir item !</label>';}
			else
				{$sMensagem = '<label class="mensagem_sucesso">Dados excluídos com sucesso !</label>';}
       }
	}
	
	$bdConexao->Desconecta();
?>
<html>
<head>
<title>GAV - Cadastro de produto</title>
<meta charset="iso-8859-1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<script language="javascript" type="text/javascript">

function VerificaData(data)
{
  var err=0
  var psj=0;
  a = data
  if (a.length != 10) return false; 
  
  if (a.length == 10)
  {
   dia = a.substring(0, 2) // dia
   c = a.substring(2, 3) // '/'
   mes = a.substring(3, 5) // mes
   e = a.substring(5, 6) // '/'
   ano = a.substring(6,10) // ano
  }
  
  if (!VerificaNumero(dia))
  {
    return false;
  }
  
  if (!VerificaNumero(mes))
  {
    return false;
  }
  
  if (!VerificaNumero(ano))
  {
    return false;
  }
  
  // verificação de erros básicos
 
  if (mes < 1 || mes > 12) err = 1
  if (c != '/') err = 1
  if (dia < 1 || dia > 31) err = 1
  if (e != '/') err = 1
  if (ano < 0 || ano > 9999) err = 1
	
  // verificação de erros avançados

  // meses com 30 dias

  if (mes == 4 || mes == 6 || mes == 9 || mes == 11)
  {
    if (dia == 31) err = 1
  }

  // fevereiro, ano bissexto

  if (mes == 2)
  {
    var g = parseInt(ano/4)
    if (isNaN(g)) 
    {
      err = 1
    }

    if (dia > 29) err = 1 
    if (dia == 29 && ((ano/4) != parseInt(ano/4))) err = 1
  }

  if (err == 1)
  {
    return false;
  }
  else
  {
    return true;
  }
}

function FormataDados(src,mask)
{
  /*
   Essa função serve para formatar vários tipos de dados.
   Para CEP, basta passar esses dois parâmetros na chamada da função: this, '#####-###'
   Para Data, basta passar esses dois parâmetros na chamada da função: this, '##/##/####'
   Para CPF, basta passar esses dois parâmetros na chamada da função: this, '###.###.###-##'
   
  */
  var i = src.value.length;
  var saida = mask.substring(0,1);
  var texto = mask.substring(i)
  
  if(texto.substring(0,1) != saida)
  	{src.value += texto.substring(0,1);}
 }

function ValidaCadastro()
{
	document.form1.btCadastra.disabled=true;
	
	if(document.form1.tbNome.value == "")
	{
		alert("Informe o nome do produto!");
		document.form1.tbNome.focus();
		document.form1.btCadastra.disabled=false;
		return false;
	}
	
	if(!VerificaData(document.form1.tbDtEntrada.value))
	{
		alert("Data invalida! Informe a data no formato DD/MM/AAAA.");
		document.form1.tbDtEntrada.focus();
		document.form1.btCadastra.disabled=false;
		return false;
	}
	
	if(document.form1.txrDescricao.value == "")
	{
		alert("Informe a descricao do produto!");
		document.form1.txrDescricao.focus();
		document.form1.btCadastra.disabled=false;			
		return false;
	}
	
	if(document.form1.hdCodProduto.value == ""){
		document.form1.hdAcao.value = "INSERE";
	}
	else{
		document.form1.hdAcao.value = "ALTERA";
	}
			
	document.form1.submit();	
}
			
function Seleciona(nCodigo)
{
	document.form1.hdCodProduto.value = nCodigo;
	document.form1.hdAcao.value = "BUSCA";
	document.form1.submit();		
}
	
function ValidaExclusao()
{	
	document.form1.btExclue.disabled=true;
	bOKExclusao = false;
	
	if(bOKExclusao)
	{
		if(confirm("Tem certeza que deseja excluir o produto?"))
		{
			document.form1.hdAcao.value = "EXCLUE";
			document.form1.submit();
		}
		else
		{document.form1.btExclue.disabled=false;}
	}
}

function Consulta()
{
	document.form1.hdAcao.value = "CONSULTA";
	document.form1.submit();
}
</script>
</head>

<body>

<form name="form1" action="cad_produto.php" method="post">
<div id="dvCadastro" style="top:100px;left:0%;padding: 20px; position: absolute;">
<table cellspacing="1" cellpadding="1" border="0"> 
<?php if (isset($sMensagem)) {echo '<tr><td colspan="2">'.$sMensagem.'</td></tr>';} ?>	
 <tr>
	<td align="right"><label class="rotulo1">Nome *&nbsp;</label></td>
	<td><input class="form-control" name="tbNome" type="text" value="" size="70" maxlength="100"></td>
 </tr>
 <tr>
	<td align="right"><label class="rotulo1">Data de Entrada *&nbsp;</label></td>
	<td><input class="form-control" name="tbDtEntrada" type="text" size="10" maxlength="10" onKeyUp="FormataDados(this, '##/##/####');"></td>
 </tr>
 </tr>
	<td align="right"><label class="rotulo1">Descricao *&nbsp;</label></td>
    <td><textarea class="form-control" name="txrDescricao" cols="40" rows="3"></textarea></td>
  </tr>
 <tr>
 	<td colspan="2" align="center">
	<table><tr>
	<?php if($_POST["hdCodProduto"] == "") { ?>
		<td align="center"><input name="btCadastra" type="button" class="btn btn-primary" value="Gravar" onClick="ValidaCadastro()"></td>
	<?php }  else { ?>
		<td align="center"><input name="btCadastra" type="button" class="btn btn-primary" value="Alterar" onClick="ValidaCadastro()"></td>
	<?php } ?>
	<td align="center"><input name="btConsulta" type="button" class="btn btn-primary" value="Consultar" onClick="Consulta()"></td>
	</tr></table>
	</td>	
  </tr>  
  </table>
 <br /> 	
<?php if($hdAcao == "CONSULTA") {} ?>
</div>

<input name="hdAcao" type="hidden" value="">
<input name="hdCodProduto" type="hidden" value="<?=$_POST["hdCodProduto"]?>">
</form>
</body>
</html>