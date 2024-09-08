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
setInterval(()=>{pingeo("10.75.6.36")},1000);


