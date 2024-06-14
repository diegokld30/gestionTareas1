<?php
$ch = curl_init();
$urlGetTareas = 'http://localhost/gestionTareasDC1/tarea/tareas';

curl_setopt($ch, CURLOPT_URL, $urlGetTareas);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    $error_msg = curl_error($ch);
    echo "Error al conectarse a la API para obtener tareas: " . $error_msg;
} else {
    curl_close($ch);
    $tareas_data = json_decode($response, true);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de tareas</title>
    <!-- Incluye el CSS de Bootstrap 5.3.2 para estilizar la tabla -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="my-4">Gestión de tareas</h1>
    <p>Nombre página: <?= isset($data['page_title']) ? htmlspecialchars($data['page_title']) : 'Título no definido'; ?></p>

    <a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#crearTareaModal" role="button">Crear Tarea</a>

    <div>
        <?php
        if (isset($tareas_data["data"])) {
            echo "<h1 class='text-center'>Lista de Tareas</h1>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-striped table-bordered'>";
            echo "<thead class='thead-dark'>";
            echo "<tr class='text-center'><th>ID</th><th>Título</th><th>Descripción</th><th>Completado</th><th>Fecha de Registro</th></tr>";
            echo "</thead>";
            echo "<tbody>";

            foreach ($tareas_data["data"] as $tarea) {
                $completado = $tarea["completado"] == 1 ? "Completada" : "Pendiente";
                echo "<tr>";
                echo "<td>" . htmlspecialchars($tarea["id"]) . "</td>";
                echo "<td>" . htmlspecialchars($tarea["titulo"]) . "</td>";
                echo "<td>" . htmlspecialchars($tarea["descripcion"]) . "</td>";
                echo "<td>" . $completado . "</td>";
                echo "<td>" . htmlspecialchars($tarea["fechaRegistro"]) . "</td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
            echo "</div>"; // Cierre de table-responsive
        } else {
            echo "No se encontraron datos de tareas.";
        }
        ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="crearTareaModal" tabindex="-1" aria-labelledby="crearTareaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crearTareaModalLabel">Crear Tarea</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="crearTareaForm">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="completado" class="form-label">Completado</label>
                        <select class="form-control" id="completado" name="completado" required>
                            <option value="0">Pendiente</option>
                            <option value="1">Completada</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Crear</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Incluye el JS de Bootstrap 5.3.2 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Script para refrescar la página automáticamente cada 60 segundos -->
<script>
    setInterval(function(){
        location.reload();
    }, 60000); // Refrescar cada 60000 milisegundos (60 segundos)

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
</script>
</body>
</html>
