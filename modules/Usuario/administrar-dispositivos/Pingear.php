<?php 
/*
<?php
$text = $_POST['text'];
$output = wordwrap($text, 60, "<br>");
echo $output;
?>*/

/*
    $ip = $_POST['ip'];
    $os = $_POST['os'];
    var_dump(json_encode($_POST));
#substr(PHP_OS, 0, 3)) === 'WIN'
    if($os === 'W'){
        $resultado = shell_exec("ping -n 1 ".escapeshellarg($ip));
    }else if($os === 'L'){
        $resultado = shell_exec("ping -c 1 ".escapeshellarg($ip));
    }
*/
//Recibir JSON
$json = file_get_contents('php://input');
// Decodificar JSON
$datos = json_decode($json, true);    

$os = $datos["os"];
$ip = $datos["ip"];

if($os === 'W'){
    $resultado = shell_exec("ping -n 1 ".escapeshellarg($ip));
}else if($os === 'L'){
    $resultado = shell_exec("ping -c 1 ".escapeshellarg($ip));
}


    if(strpos($resultado, "enviados = 1, recibidos = 1") !== false || strpos($resultado, "sent = 1, received = 1") !== false){
        
        if((strpos($resultado, "Host de destino inaccesible") !== false) || (strpos($resultado, "net unreachable") !== false)){
            echo "offline";
        }else{
            echo"online";
        }
        
    }else{
        echo "offline";
        
    }
    die();
 
    ?>