<?php
    
    if(!isset($_SESSION['CodUsuario']))
    {
         /* Acesso inválido ou sessao expirou */
        echo "<script language='Javascript'>";
            echo 'alert("Usuario deve estar logado!")'.chr(10);
            echo 'window.location.href="login.php"'.chr(10);
        echo "</script>";
    }
?>