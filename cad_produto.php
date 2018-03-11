<?php
    header("Content-Type: text/html; charset=iso-8859-1",true);
    
    include ("includes/inc_geral.php"); 
    include ("includes/inc_verifica_sessao.php"); 
	
    if(!isset($_POST['hdAcao'])) {$_POST['hdAcao'] = "";}

    $bdConexao = new db();
    $bdConexao->Usuario = $gsUsuarioBanco1;
    $bdConexao->Local = $gsLocalBanco1; 
    $bdConexao->Senha = $gsSenhaBanco1; 
    $bdConexao->Banco = $gsNomeBanco1; 
    $bdConexao->Tipo = $gsTipoBanco1; 	
    $bdConexao->Conecta();
    $bdConexao->AcessaBanco("gav");

    function Extensao ($filename)
    {
        $filename = strtolower($filename) ;
        return $filename;
    } 
    
    if($_POST['hdAcao'] == "INSERE")
    {
        $sNomeArquivo = "";

        if($_FILES['tbArquivo']['name'] != "")
        {
            $sNomeArquivo  = time().".".Extensao($_FILES['tbArquivo']['name']);
            $sArquivoAnexo = "/var/www/html/images/".$sNomeArquivo;

            if(is_uploaded_file($_FILES['tbArquivo']['tmp_name'])) {
                move_uploaded_file($_FILES['tbArquivo']['tmp_name'], $sArquivoAnexo);
            }
        }
        
        $bOKInsere = true;
        $bdConexao->BeginTransaction();

        $sql = "INSERT INTO produtos (Nome, DtEntrada, VrCompra, Descricao, Imagem, DtInsercao)";
        $sql.= " VALUES ";
        $sql.= "('".addslashes($_POST['tbNome'])."','".substr($_POST['tbDtEntrada'],-4)."-".substr($_POST['tbDtEntrada'],3,2)."-". substr($_POST['tbDtEntrada'],0,2)."',".$_POST['tbValor'].", '". addslashes($_POST['txrDescricao'])."', '".$sNomeArquivo."'  , NOW())";
        $rst = $bdConexao->Consulta($sql);

        if($bdConexao->Erro){
            $bOKInsere = false;
        }

        if($bOKInsere)
        {	
            $bdConexao->CommitTransaction();
            
            echo '<script language="javascript">'.chr(10);
                echo 'alert("Cadastrado com sucesso!")'.chr(10);
                echo 'location.href="cad_produto.php"'.chr(10);
            echo '</script>'.chr(10);
        }
        else
        {
            $bdConexao->RollbackTransaction();
            
            echo '<script language="javascript">'.chr(10);
                echo 'alert("ERRO AO CADASTRAR PRODUTO!")'.chr(10);
                echo 'location.href="cad_produto.php"'.chr(10);
            echo '</script>'.chr(10);
        }				
    }
    
    if($_POST['hdAcao'] == "ALTERA")
    {
        $bOKInsere = true;
        $bdConexao->BeginTransaction();
        
        if($_FILES['tbArquivo']['name'] != "")
        {
            $sNomeArquivo  = time().".".Extensao($_FILES['tbArquivo']['name']);
            $sArquivoAnexo = "/var/www/html/images/".$sNomeArquivo;

            if(is_uploaded_file($_FILES['tbArquivo']['tmp_name'])) {
                move_uploaded_file($_FILES['tbArquivo']['tmp_name'], $sArquivoAnexo);
            }
        }
        
        $sql = "UPDATE produtos SET Nome='". addslashes($_POST['tbNome'])."', VrCompra=".$_POST['tbValor'].", DtEntrada='".substr($_POST['tbDtEntrada'],-4)."-".substr($_POST['tbDtEntrada'],3,2)."-". substr($_POST['tbDtEntrada'],0,2)."', Descricao='". addslashes($_POST['txrDescricao'])."', DtAlteracao = NOW() ";
        if($sNomeArquivo != ""){
            $sql.= ", Imagem='".$sNomeArquivo."' ";
        }
        $sql.= " WHERE CodProduto=".$_POST["hdCodProduto"];
        $rst = $bdConexao->Consulta($sql);
        if($bdConexao->Erro){
            $bOKInsere = false;
        }
        
        if($bOKInsere)
        {
            $bdConexao->CommitTransaction();
            
            echo '<script language="javascript">'.chr(10);
                echo 'alert("Alterado com sucesso!")'.chr(10);
                echo 'location.href="cad_produto.php"'.chr(10);
            echo '</script>'.chr(10);
        }
        else
        {
            $bdConexao->RollbackTransaction();
            
            echo '<script language="javascript">'.chr(10);
                echo 'alert("ERRO AO ALTERAR PRODUTO!")'.chr(10);
                echo 'location.href="cad_produto.php"'.chr(10);
            echo '</script>'.chr(10);
        } 
    }
	
    if($_POST['hdAcao'] == "EXCLUE")
    {
        $bOKInsere = true;
        $bdConexao->BeginTransaction();
        
        if($_POST['hdImagem'] != "")
        {
            $sCaminhoImagem = '/var/www/html/images/'.$_POST['hdImagem'];
            unlink($sCaminhoImagem);
        }
        
        $sql = "DELETE FROM produtos WHERE CodProduto=".$_POST["hdCodProduto"];
        $rst = $bdConexao->Consulta($sql);
        if($bdConexao->Erro){
            $bOKInsere = false;
        }
        
        if($bOKInsere)
        {
            $bdConexao->CommitTransaction();
            
            echo '<script language="javascript">'.chr(10);
                echo 'alert("Excluído com sucesso!")'.chr(10);
                echo 'location.href="cad_produto.php"'.chr(10);
            echo '</script>'.chr(10);
        }
        else
        {
            $bdConexao->RollbackTransaction();
            
            echo '<script language="javascript">'.chr(10);
                echo 'alert("ERRO AO EXCLUIR PRODUTO!")'.chr(10);
                echo 'location.href="cad_produto.php"'.chr(10);
            echo '</script>'.chr(10);
        } 
    }

    if($_POST['hdAcao'] == "BUSCA")
    {
        $sql = "SELECT Nome, Descricao, Imagem, VrCompra, DATE_FORMAT(DtEntrada,'%d/%m/%Y') AS DtEntrada FROM produtos WHERE CodProduto=".$_POST['hdCodProduto'];
        $rst = $bdConexao->Consulta($sql);
        if(!$bdConexao->EOF($rst))
        {
            $_POST['tbNome'] = $bdConexao->Registro["Nome"];
            $_POST['tbValor'] = $bdConexao->Registro["VrCompra"];
            $_POST['tbDtEntrada'] = $bdConexao->Registro["DtEntrada"];
            $_POST['hdImagem'] = $bdConexao->Registro["Imagem"];
            $_POST['txrDescricao'] = $bdConexao->Registro["Descricao"];
        }
        
        // Verifica se existe produto em estoque, se existir não pode exluir.
        $bPodeExcluir = true;
        $sql = "SELECT CodRef FROM estoque WHERE CodProduto=".$_POST['hdCodProduto'];
        $rst = $bdConexao->Consulta($sql);
        if($bdConexao->TotalRegistros($rst) > 0){
            $bPodeExcluir = false;
        }
    }			

    if($_POST['hdAcao'] == "REMOVE_IMAGEM")
    {
        $bOKInsere = true;
        $bdConexao->BeginTransaction();
        
        $sql = "UPDATE produtos SET Imagem='' WHERE CodProduto=".$_POST['hdCodProduto'];
        $rst = $bdConexao->Consulta($sql);
        if($bdConexao->Erro){
            $bOKInsere = false;
        }
        
        if($bOKInsere)
        {
            $sCaminhoImagem = '/var/www/html/images/'.$_POST['hdImagem'];
            unlink($sCaminhoImagem);
        }

        if($bOKInsere)
        {
            $bdConexao->CommitTransaction();
            
            $_POST["hdImagem"] = "";
            
            echo '<script language="javascript">'.chr(10);
                echo 'alert("Imagem removida com sucesso!")'.chr(10);
                echo 'location.href="cad_produto.php'.chr(10);
            echo '</script>'.chr(10);
        }
        else
        {
            $bdConexao->RollbackTransaction();
            
            echo '<script language="javascript">'.chr(10);
                echo 'alert("ERRO AO REMOVER IMAGEM!")'.chr(10);
                echo 'location.href="cad_produto.php"'.chr(10);
            echo '</script>'.chr(10);
        }
    }
?>
<!doctype html>
<html>
<head>
<title>GAV - Cadastro de produto</title>
<meta charset="iso-8859-1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<script language="javascript" type="text/javascript">
    function VerificaNumero(Numero) 
    {
        var i;
        // Verifica se o valor do campo é numérico

        for(i=0; i<Numero.length; i++) 
        {
            if(!ENumero(Numero.charAt(i)))
                 return false;   
        }  
       return true;
    }

    function ENumero(Digito)
    {
        if(Digito=="0" || Digito=="1" || Digito=="2" || Digito=="3" || Digito=="4" || Digito=="5" || Digito=="6" || Digito=="7" || Digito=="8" || Digito=="9" || Digito==".")
            return true ;
        else 
            return false; 
    }

    function VerificaData(data)
    {
        var err=0
        var psj=0;
        a = data
        if(a.length != 10) return false; 

        if(a.length == 10)
        {
            dia = a.substring(0, 2) // dia
            c = a.substring(2, 3) // '/'
            mes = a.substring(3, 5) // mes
            e = a.substring(5, 6) // '/'
            ano = a.substring(6,10) // ano
        }

      if(!VerificaNumero(dia)){
        return false;
      }

      if(!VerificaNumero(mes)){
        return false;
      }

      if(!VerificaNumero(ano)){
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

      if(mes == 4 || mes == 6 || mes == 9 || mes == 11){
        if(dia == 31) err = 1
      }

      // fevereiro, ano bissexto
      if (mes == 2)
      {
        var g = parseInt(ano/4)

        if(isNaN(g)){
          err = 1
        }

        if (dia > 29) err = 1 
        if (dia == 29 && ((ano/4) != parseInt(ano/4))) err = 1
      }

      if(err == 1){
        return false;
      }
      else{
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

            if(document.form1.tbValor.value == "" || document.form1.tbValor.value <= 0)
            {
                alert("Valor invalido!");
                document.form1.tbValor.focus();
                document.form1.btCadastra.disabled=false;			
                return false;
            }

            if(isNaN(document.form1.tbValor.value))
            {
                alert("Valor devera ser numérico!");
                document.form1.tbValor.focus();
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

        if(confirm("Tem certeza que deseja excluir o produto?"))
        {
            document.form1.hdAcao.value = "EXCLUE";
            document.form1.submit();
        }
        else{
            document.form1.btExclue.disabled=false;}
    }

    function Consulta()
    {
        document.form1.hdAcao.value = "CONSULTA";
        document.form1.submit();
    }
    
    function RemoveImagem()
    {
        //document.form1.hdCodProduto.value = nCodProduto;
        document.form1.hdAcao.value = "REMOVE_IMAGEM";
        document.form1.submit();
    }
</script>
</head>

<body>
    <?php include ("includes/inc_menu.php");?>
<div id="dvCadastro" style="top:70px;left:0%;padding: 20px; position: absolute;" class="form-group">
    <form name="form1" action="cad_produto.php" method="post" enctype="multipart/form-data" class="navbar-form navbar-left" role="search">
        <table cellspacing="1" cellpadding="1" border="0">
            <tr>
                <td align="right"><label>Nome *&nbsp;</label></td>
                <td><input class="form-control" name="tbNome" type="text" value="<?=$_POST['tbNome']?>" size="50" maxlength="100"></td>
            </tr>
            <tr>
                <td align="right"><label>Valor da Compra *&nbsp;</label></td>
                <td><input class="form-control" name="tbValor" type="text" value="<?=$_POST['tbValor']?>" size="10" maxlength="15"></td>
            </tr>
            <tr>
                <td align="right"><label>Data de Entrada *&nbsp;</label></td>
                <td><input class="form-control" name="tbDtEntrada" type="text" value="<?=$_POST['tbDtEntrada']?>" size="10" maxlength="10" onKeyUp="FormataDados(this, '##/##/####');"></td>
            </tr>
            <tr>
                <td align="right"><label>Descrição *&nbsp;</label></td>
                <td><textarea class="form-control" name="txrDescricao" cols="52" rows="3"><?=$_POST['txrDescricao']?></textarea></td>
            </tr>
            <?php
                if($_POST['hdImagem'] != "")
                {
                    echo '<tr>';
                        echo '<td><label>Imagem Cadastrada</label></td>';
                        echo '<td align="left"><img height="80" width="60" src="/images/'.$_POST['hdImagem'].'"><a href="javascript:RemoveImagem();">&nbsp;Remover Imagem</a></td>';
                    echo '</tr>';
                }
            ?>
            <tr>
                <td><label>Imagem&nbsp;</label></td>
                <td><input name="tbArquivo" type="file" size="30"></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <table>
                        <tr>
                            <?php if($_POST["hdCodProduto"] == "") { ?>
                            <td><input name="btCadastra" type="button" class="btn btn-primary" value="Gravar" onClick="ValidaCadastro()"></td>
                            <?php } else { ?>
                                <td><input name="btCadastra" type="button" class="btn btn-primary" value="Alterar" onClick="ValidaCadastro()"></td>
                                
                                <?php if($bPodeExcluir){ ?>
                                    <td><input name="btExclue" type="button" class="btn btn-primary" value="Excluir" onClick="ValidaExclusao()"></td>
                                <?php } ?>
                                
                            <?php } ?>
                                <td><input name="btConsulta" type="button" class="btn btn-primary" value="Consultar" onClick="Consulta()"></td>
                        </tr>
                    </table>
                </td>	
            </tr>  
        </table>
        <?php 
        if($_POST['hdAcao'] == "CONSULTA"){
            echo '<br />';
            echo '<table border="1" cellspacing="1" cellpading="1">';
                echo '<tr>';
                  echo '<td colspan="4" align="center"><label>Itens Cadastrados</label></td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td align="center"><strong>Nome</strong></td>';
                    echo '<td align="center"><strong>Valor Compra</strong></td>';
                    echo '<td align="center"><strong>Data Entrada</strong></td>';
                    echo '<td align="center"><strong>Descrição</strong></td>';
                echo '</tr>';
            
                $sql = "SELECT CodProduto, Nome, Descricao, Imagem, VrCompra, DATE_FORMAT(DtEntrada,'%d/%m/%Y') AS DtEntrada FROM produtos WHERE 1=1 ";
                if($_POST['tbNome'] != "") {$sql.= " AND UPPER(Nome) LIKE '%".strtoupper($_POST['tbNome'])."%' ";}
                if($_POST['tbValor'] != "") {$sql.= " AND VrCompra = ".$_POST['tbValor']." ";}
                if($_POST['tbDtEntrada'] != "") {$sql.= " AND DtEntrada = '".substr($_POST['tbDtEntrada'],-4)."-". substr($_POST['tbDtEntrada'],3,2)."-".substr($_POST['tbDtEntrada'],0,2)."' ";}
                if($_POST['txrDescricao'] != "") {$sql.= " AND UPPER(Descricao) LIKE '%".strtoupper($_POST['txrDescricao'])."%' ";}
                $sql.= " ORDER BY Nome, DtInsercao";
                $rst = $bdConexao->Consulta($sql);
                if($bdConexao->TotalRegistros($rst) <=0)
                {
                    echo '<tr>';
                        echo '<td colspan="4" align="center"><strong>Nenhum registro encontrado</strong></td>';
                    echo '</tr>';
                }
                while(!$bdConexao->EOF($rst))
                {
                    echo '<tr>';
                        echo '<td><a href="javascript:Seleciona('.$bdConexao->Registro["CodProduto"].');">'.$bdConexao->Registro["Nome"].'</a></td>';
                        echo '<td align="center"><a href="javascript:Seleciona('.$bdConexao->Registro["CodProduto"].');">'.$bdConexao->Registro["VrCompra"].'</a></td>';
                        echo '<td align="center"><a href="javascript:Seleciona('.$bdConexao->Registro["CodProduto"].');">'.$bdConexao->Registro["DtEntrada"].'</a></td>';
                        echo '<td align="center"><a href="javascript:Seleciona('.$bdConexao->Registro["CodProduto"].');">'.$bdConexao->Registro["Descricao"].'</a></td>';
                    echo '</tr>';
                }
            echo '</table>';
        } 
        ?>

<input name="hdAcao" type="hidden" value="">
<input name="hdCodProduto" type="hidden" value="<?=$_POST['hdCodProduto']?>">
<input name="hdImagem" type="hidden" value="<?=$_POST['hdImagem']?>">
</form>
</div>
</body>
</html>
<?php $bdConexao->Desconecta();?>