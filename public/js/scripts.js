function login(event) {
    event.preventDefault(); // Evitar el envío estándar del formulario
    
    const form = document.querySelector('form');
    const formData = new FormData(form); // Obtener los datos del formulario

    // Enviar los datos al servidor usando fetch
    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // Convertir la respuesta en JSON
    .then(data => {
        if (data.success) {
            // Si el inicio de sesión es exitoso, redirigir
            alert(data.message);
            window.location.href = data.redirect;
        } else {
            // Mostrar el mensaje de error
            document.getElementById('error-message').textContent = data.message;
        }
    })
    .catch(error => console.error('Error:', error)); // Manejar errores
}

// Escuchar el evento submit del formulario
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', login); // Asignar la función login al evento submit
});