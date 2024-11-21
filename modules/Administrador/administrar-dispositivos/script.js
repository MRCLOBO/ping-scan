import {pingeo} from './pingear.js';
/*
Primera expresion funcional 
document.getElementById("boton").addEventListener("click",()=>pingeo())

setInterval(()=>document.getElementById("boton").click() ,1000);

*/ 

/*segunda prueba- mejor rendimiento por menor anidacion de codigo 
setInterval(()=>{pingeo()},1000);
prueba  de rendimiento exitosa*/

//Tercera prueba con IP de parametro para la reutilizacion de codigo 
/*
setInterval(()=>{pingeo("10.186.188.84")},1000);
setInterval(()=>{pingeo("192.168.205.8")},1000);
*/
//Cuarta Prueba, mandar el pingeo por IP de cada equipo dentro de la tabla
/*
console.log(document.getElementById("auxiliar-iterador").textContent)
let i;
let max = Number(document.getElementById("auxiliar-iterador").textContent) 


setInterval(()=>{
    for(i=0;i<max;i++){
        let ipActual= document.getElementById(i).textContent;    
        pingeo(ipActual,i)
    }
},1000);
*/
//Quinta prueba codigo limpio - CODIGO FINAL
let i; //iterador
let max = Number(document.getElementById("auxiliar-iterador").textContent) //Cantidad de filas de la tabla
let os = document.getElementById("os").textContent.substring(0,1) //devuelve W o L dependiendo del tipo de SO

/*
Logica de V 1.5.1
setInterval(()=>{
    for(i=0;i<max;i++){
        let ipActual= document.getElementById(i).children[0].textContent;    
        pingeo(ipActual,i,os)
    }
},5000);//Se ira ejecutando la funcion cada 1 segundo
*/
for(i=0;i<max;i++){
    let ipActual= document.getElementById(i).children[0].textContent;    
    pingeo(ipActual,i,os)
}


    //funcion para capturar la fila seleccionada
    