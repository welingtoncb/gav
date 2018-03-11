<?php
    session_destroy();
    session_unset($_SESSION['CodUsuario']);
?>
<script language="javascript">
    window.top.location.href="login.php";
</script>
    