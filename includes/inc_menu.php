<?php header("Content-Type: text/html; charset=iso-8859-1",true); ?>

<div style="left: 30px; position: absolute">
    <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
            <a href="cad_produto.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Produto</a>
        </li>
        <li class="dropdown">
            <a href="cad_estoque.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Estoque</a>
        </li>

        <?php if($_SESSION['CodUsuario'] == 2 || $_SESSION['CodUsuario'] == 3) {?>
        <li class="dropdown">
            <a href="cad_venda.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Venda</a>
        </li>
        <?php } ?>

        <li class="dropdown">
            <a href="sair.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Sair</a>
        </li>
    </ul>    
</div>