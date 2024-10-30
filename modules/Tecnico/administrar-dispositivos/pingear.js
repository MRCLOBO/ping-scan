//Parte del sistemaque se encarga de solicitar el pingeo de una IP pasada por parametro
//Version Final y funcional

export function pingeo(ipEquipo,id,osEquipo){

  const datos = {
    "ip":ipEquipo,
    "os":osEquipo
  };

  const json = JSON.stringify(datos);

  $.ajax({
    type: "POST",
    url: "./Pingear.php",
    data: json,
    contentType: "application/json",
    success: function(respuesta) {
      if(respuesta=="offline"){  
        document.getElementById(id).children[3].className=respuesta;
        document.getElementById(id).children[3].textContent="Fuera de linea";
      }else if(respuesta=="online"){
        document.getElementById(id).children[3].className="online";
        document.getElementById(id).children[3].textContent="Conectado";
      }

      setTimeout( () =>{pingeo(ipEquipo,id,osEquipo)},5000 )
    
    }
  });

  /*fetch('./Pingear.php', {
    method: 'POST',
    headers: {
      "Content-Type": "application/json",
    },
    body: {'ip': ipEquipo,'os':os,}
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
    
  })*///reemplazar el id con la ip del equipo
}