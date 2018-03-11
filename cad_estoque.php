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

    if($_POST['hdAcao'] == "INSERE")
    {
        $bOKInsere = true;
        $bdConexao->BeginTransaction();
        
        // Verifica se existe registro na tabela estoque se não existir cadastra, caso contrário atualiza
        $sql = "SELECT CodRef FROM estoque WHERE CodProduto=".$_POST["cmbProduto"];
        $rst = $bdConexao->Consulta($sql);
        if($bdConexao->TotalRegistros($rst) <= 0)
        {
            $sql = "INSERT INTO estoque (CodProduto, QtdEstoque)";
            $sql.= " VALUES ";
            $sql.= "(".$_POST["cmbProduto"].",".$_POST["tbQuantidade"].")";
        }
        else{
            $sql = "UPDATE estoque SET QtdEstoque=QtdEstoque+".$_POST["tbQuantidade"]." WHERE CodProduto=".$_POST["cmbProduto"];
        }
        $rst = $bdConexao->Consulta($sql);
        //echo $sql;

        if($bdConexao->Erro){
            $bOKInsere = false;
        }

        if($bOKInsere)
        {	
            $bdConexao->CommitTransaction();
            
            echo '<script language="javascript">'.chr(10);
                echo 'alert("Cadastrado com sucesso!")'.chr(10);
                echo 'location.href="cad_estoque.php"'.chr(10);
            echo '</script>'.chr(10);
        }
        else
        {
            $bdConexao->RollbackTransaction();
            
            echo '<script language="javascript">'.chr(10);
                echo 'alert("ERRO AO CADASTRAR ESTOQUE!")'.chr(10);
                echo 'location.href="cad_estoque.php"'.chr(10);
            echo '</script>'.chr(10);
        }				
    }
    
?>
<!doctype html>
<html>
<head>
<title>GAV - Cadastro de estoque</title>
<meta charset="iso-8859-1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<script language="javascript" type="text/javascript">
    function ValidaCadastro()
    {
            document.form1.btCadastra.disabled=true;

            if(document.form1.cmbProduto.value == "")
            {
                alert("selecione o produto!");
                document.form1.btCadastra.disabled=false;
                return false;
            }

            if(document.form1.tbQuantidade.value == "" || document.form1.tbQuantidade.value <= 0)
            {
                alert("Valor invalido!");
                document.form1.tbQuantidade.focus();
                document.form1.btCadastra.disabled=false;			
                return false;
            }

            if(isNaN(document.form1.tbQuantidade.value))
            {
                alert("Valor deverá ser numérico!");
                document.form1.tbQuantidade.focus();
                document.form1.btCadastra.disabled=false;			
                return false;
            }

            document.form1.hdAcao.value = "INSERE";
            document.form1.submit();	
    }
</script>
</head>

<body>
    <?php include ("includes/inc_menu.php");?>
<div id="dvCadastro" style="top:70px;left:0%;padding: 20px; position: absolute;" class="form-group">
    <form name="form1" action="cad_estoque.php" method="post" class="navbar-form navbar-left" role="search">
        <table cellspacing="1" cellpadding="1" border="0">
            <tr>
                <td align="right"><label class="rotulo1">Produto *&nbsp;</label></td>
                <td>
                    <select name="cmbProduto" class="form-control" style="width:250px" onchange="document.form1.submit();">
                    <option value="">Selecione o produto</option>
                    <?php
                        $sql = "SELECT * FROM produtos ORDER BY Nome";
                        $rst = $bdConexao->Consulta($sql);
                        while(!$bdConexao->EOF($rst))
                        {
                            if($_POST["cmbProduto"] == $bdConexao->Registro["CodProduto"]){
                                echo '<option selected value="'.$bdConexao->Registro["CodProduto"].'">';
                            }
                            else{
                                echo '<option value="'.$bdConexao->Registro["CodProduto"].'">';}

                            echo $bdConexao->Registro["Nome"];
                            echo "</option>";
                        }
                    ?>
                </select>
                </td>
            </tr>
            <?php
                $nQtd = 0;
                
                $sql = "SELECT QtdEstoque FROM estoque WHERE CodProduto=".$_POST["cmbProduto"];
                $rst = $bdConexao->Consulta($sql);
                if(!$bdConexao->EOF($rst)){
                  $nQtd = $bdConexao->Registro["QtdEstoque"];
                }
            ?>
            <?php if($_POST["cmbProduto"] != "") {?>
            <tr>
                <td align="right"><label>Estoque:&nbsp;</label></td>
                <td><label><?=$nQtd?></label></td>
            </tr>
            <tr>
                <td align="right"><label>Quantidade *&nbsp;</label></td>
                <td><input class="form-control" name="tbQuantidade" type="text" size="10" maxlength="10"></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input name="btCadastra" type="button" class="btn btn-primary" value="Gravar" onClick="ValidaCadastro()"></td>
            </tr>
            <?php } ?>
        </table>
<input name="hdAcao" type="hidden" value="">
</form>
</div>
</body>
</html>
<?php $bdConexao->Desconecta();?>