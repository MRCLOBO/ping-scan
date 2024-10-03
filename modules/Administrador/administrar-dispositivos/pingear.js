//Parte del sistemaque se encarga de solicitar el pingeo de una IP pasada por parametro
//Version Final y funcional

export function pingeo(ipEquipo,id){
  fetch('./Pingear.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
    },
    body: "ip="+ipEquipo
  })
  .then(response => response.text())
  .then(data => {
    if(data=="offline"){  
      document.getElementById(id).children[3].className=data;
      document.getElementById(id).children[3].textContent="Fuera de linea";
    }else if(data=="online"){
      document.getElementById(id).children[3].className="online";
      document.getElementById(id).children[3].textContent="Conectado";
    }
    
  })//reemplazar el id con la ip del equipo
}