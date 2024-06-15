function obtenerTareas() {

    fetch('http://localhost/gestionTareasDC1/tarea/tareas')
        .then(response => {

            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.text();
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
                                <td><input type="checkbox" class="task-checkbox" data-id="${tarea.id}"></td>
                                <td>${tarea.id}</td>
                                <td>${tarea.titulo}</td>
                                <td>${tarea.descripcion}</td>
                                <td>${completado}</td>
                                <td>${tarea.fechaRegistro}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm editar-tarea" data-id="${tarea.id}">Editar</button>
                                    <button class="btn btn-danger btn-sm eliminar-tarea" data-id="${tarea.id}">Eliminar</button>
                                </td>
                            </tr>
                        `;
                    });
                    document.querySelector('#tareasTable tbody').innerHTML = tableBody;
                    $('#tareasTable').DataTable({
                        "order": [[1, "desc"]],
                        "language": {
                            "url": "Views/js/es_es.json"
                        }
                    });

                    document.querySelectorAll('.editar-tarea').forEach(button => {
                        button.addEventListener('click', function() {
                            const id = this.dataset.id;
                            editarTarea(id);
                        });
                    });

                    document.querySelectorAll('.eliminar-tarea').forEach(button => {
                        button.addEventListener('click', function() {
                            const id = this.dataset.id;
                            eliminarTarea(id);
                        });
                    });

                    document.getElementById('selectAll').addEventListener('change', function() {
                        document.querySelectorAll('.task-checkbox').forEach(checkbox => {
                            checkbox.checked = this.checked;
                        });
                    });

                    document.getElementById('marcarCompletadas').addEventListener('click', function() {
                        marcarTareas(true);
                    });

                    document.getElementById('marcarPendientes').addEventListener('click', function() {
                        marcarTareas(false);
                    });
                } else {
                    Swal.fire('Error', 'Error al obtener las tareas.', 'error');
                }
            } catch (error) {
                console.error('Error parsing JSON:', error);
                Swal.fire('Error', 'Error parsing JSON: ' + error, 'error');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            Swal.fire('Error', 'Fetch error: ' + error, 'error');
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
                    Swal.fire('Éxito', 'Tarea creada correctamente.', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', 'Error al crear la tarea.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Error al crear la tarea.', 'error');
            });
    });

    document.getElementById('editarTareaForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const id = document.getElementById('editId').value;
        const titulo = document.getElementById('editTitulo').value;
        const descripcion = document.getElementById('editDescripcion').value;
        const completado = document.getElementById('editCompletado').value;

        fetch(`http://localhost/gestionTareasDC1/tarea/actualizarTarea/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ titulo, descripcion, completado })
        })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    Swal.fire('Éxito', 'Tarea actualizada correctamente.', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', 'Error al actualizar la tarea.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Error al actualizar la tarea.', 'error');
            });
    });

    setInterval(function(){
        location.reload();
    }, 60000);
});

function editarTarea(id) {
    fetch(`http://localhost/gestionTareasDC1/tarea/tarea/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                const tarea = data.data;
                document.getElementById('editId').value = tarea.id;
                document.getElementById('editTitulo').value = tarea.titulo;
                document.getElementById('editDescripcion').value = tarea.descripcion;
                document.getElementById('editCompletado').value = tarea.completado;
                const editModal = new bootstrap.Modal(document.getElementById('editarTareaModal'));
                editModal.show();
            } else {
                Swal.fire('Error', 'Error al obtener los datos de la tarea.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Error al obtener los datos de la tarea.', 'error');
        });
}

function eliminarTarea(id) {
    fetch(`http://localhost/gestionTareasDC1/tarea/eliminarTarea/${id}`, {
        method: 'DELETE'
    })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                Swal.fire('Éxito', 'Tarea eliminada correctamente.', 'error').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', 'Error al eliminar la tarea.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Error al eliminar la tarea.', 'error');
        });
}

function marcarTareas(completado) {
    const checkboxes = document.querySelectorAll('.task-checkbox:checked');
    const ids = Array.from(checkboxes).map(checkbox => checkbox.dataset.id);

    if (ids.length === 0) {
        Swal.fire('Advertencia', 'No se seleccionaron tareas.', 'warning');
        return;
    }

    fetch('http://localhost/gestionTareasDC1/tarea/marcarTareas', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ids, completado })
    })
        .then(response => response.text())
        .then(text => {
            console.log("Response text:", text); // Log the response text
            const data = JSON.parse(text);
            if (data.status) {
                Swal.fire('Éxito', 'Tareas actualizadas correctamente.', 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', 'Error al actualizar las tareas.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Error al actualizar las tareas.', 'error');
        });
}
