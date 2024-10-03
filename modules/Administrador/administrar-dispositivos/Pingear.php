<?php 
/*
<?php
$text = $_POST['text'];
$output = wordwrap($text, 60, "<br>");
echo $output;
?>*/
    $ip = $_POST['ip'];
    $resultado = shell_exec("ping -n 1 ".escapeshellarg($ip));
    if(strpos($resultado, "enviados = 1, recibidos = 1") !== false){
        
        if((strpos($resultado, "Host de destino inaccesible") !== false)){
            echo "offline";
        }else{
            echo"online";
        }
        
    }else{
        echo "offline";
        
    }
    
    ?>