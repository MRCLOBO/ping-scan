function generarInforme() {
    let dispositivos = [];
    const filas = document.querySelectorAll("table tbody tr"); // Asumiendo que tienes una tabla en tu vista.php

    /*filas.forEach((fila, index) => {
        const nombre = fila.children[1].textContent; // Ajusta según la posición en tu tabla
        const estado = fila.children[3].textContent; // Ajusta según la posición en tu tabla

        dispositivos.push({ id: index + 1, nombre, estado });
    });*/

    filas.forEach((fila) => {
        const ipCompleta = fila.children[0].textContent; // Suponiendo que la IP está en la primera columna
        const nombre = fila.children[1].textContent; // Ajusta según la posición en tu tabla
        const estado = fila.children[3].textContent; // Ajusta según la posición en tu tabla

        // Extraer el último octeto de la IP
        const ultimoOcteto = ipCompleta.split('.').pop();

        dispositivos.push({ id: ultimoOcteto, nombre, estado });
    });

    // Enviar los datos al servidor
    $.ajax({
        //url: 'http://localhost/ping-scan/modules/Administrador/informes/generarPdfv3.php',
        url: 'http://localhost/ping-scan/modules/Administrador/informes/informeGenerado.php',
        method: 'POST',
        data: JSON.stringify({ dispositivos }),
        contentType: 'application/json',
        success: function(response) {
            if (response.success) {
                alert("Informe generado exitosamente. Ver Informe");
                window.open(response.filePath, '_blank');
            } else {
                alert("Error: No se pudo generar el archivo PDF.");
            }
        },
        error: function(error) {
            console.error("Error en la solicitud:", error);
            alert("Error al generar el informe.");
        }
    });
}
