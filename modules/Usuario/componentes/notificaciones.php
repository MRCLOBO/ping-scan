<div id="notificacion"  class="notificacion" <?php if($_SESSION['notificacion']==""){echo "style='height:0px;'";} ?>>
    <p><?php 
echo $_SESSION['notificacion'];
?>
</p>
</div>