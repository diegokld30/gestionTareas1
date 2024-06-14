function obtenerTareas() {

    fetch('http://localhost/gestionTareasDC1/tarea/tareas')
        .then(response => {

            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.text(); // Note the change to text()
        })
        .then(text => {

            try {
                const data = JSON.parse(text);
                if (data.status) {
                    let tableBody = '';
                    data.data.forEach(tarea => {
                        const completado = tarea.completado == 1 ? "Completada" : "Pendiente";
                        tableBody += `
                            <tr>
                                <td>${tarea.id}</td>
                                <td>${tarea.titulo}</td>
                                <td>${tarea.descripcion}</td>
                                <td>${completado}</td>
                                <td>${tarea.fechaRegistro}</td>
                            </tr>
                        `;
                    });
                    document.querySelector('#tareasTable tbody').innerHTML = tableBody;
                    $('#tareasTable').DataTable({
                        "order": [[0, "desc"]],
                        "language": {
                            "url": "Views/js/es_es.json" // Ruta local al archivo de idioma
                        }
                    });
                } else {
                    alert('Error al obtener las tareas.');
                }
            } catch (error) {
                console.error('Error parsing JSON:', error);
                alert('Error parsing JSON: ' + error);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Fetch error: ' + error);
        });
}

document.addEventListener('DOMContentLoaded', function() {
    obtenerTareas();

    document.getElementById('crearTareaForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const titulo = document.getElementById('titulo').value;
        const descripcion = document.getElementById('descripcion').value;
        const completado = document.getElementById('completado').value;

        fetch('http://localhost/gestionTareasDC1/tarea/agregarTarea', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ titulo, descripcion, completado })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Refrescar la página para mostrar la nueva tarea
                } else {
                    alert('Error al crear la tarea.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al crear la tarea.');
            });
    });

    setInterval(function(){
        location.reload();
    }, 60000); // Refrescar cada 60000 milisegundos (60 segundos)
});
