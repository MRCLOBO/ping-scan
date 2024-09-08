export function pingeo(ipEquipo){
    return(
    fetch('./Prueba1-anidado.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
        },
        body: "ip="+ipEquipo
      })
      .then(response => response.text())
      .then(data => console.log(data))
    )

}