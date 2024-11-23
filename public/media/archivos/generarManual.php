<?php
if (isset($_POST['descargar'])) { 
  $file = 'Manual de usuario - Ping-Scan V 1-9-2.pdf';
  if (is_file($file)) {
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=\"$file\"");
    readfile($file);
  } else {
    die("Error: no se encontró el archivo '$file'");
  }
}
?>