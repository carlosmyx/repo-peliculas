
<?php
require_once("conexion.php");

// Consulta para mostrar películas con nombre del director
$sql = "SELECT 
            p.pelicula_id,
            p.nombre AS nombre_pelicula,
            p.fecha_estreno,
            p.duracion_minutos,
            d.nombre AS nombre_director,
            d.apellido AS apellido_director
        FROM peliculas p
        INNER JOIN directores d ON p.director_id = d.director_id";
$resultado = mysqli_query($conexion, $sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Películas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        .btn-agregar-departamento { background: #007bff; color: #fff; border: none; }
        .btn-agregar-departamento:hover { background: #0056b3; }
        .regresar-container { margin: 1em 0; }
        .btn-regresar { background: #343a40; color: #fff; padding: 0.5em 1em; border-radius: 5px; text-decoration: none; }
        .btn-regresar:hover { background: #23272b; }
    </style>
</head>
<body>
<div class="regresar-container">
  <a href="index.html" class="btn-regresar">
    <span style="font-size:1.2em;vertical-align:middle;">&#8592;</span> Regresar al inicio
  </a>
</div>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="animate__animated animate__fadeInDown">Películas</h1>
        <button type="button" class="btn-agregar-departamento animate__animated animate__pulse animate__infinite" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <span style="font-size:1.3em;vertical-align:middle;display:inline-block;margin-right:0.5em;">
                <svg width="1em" height="1em" viewBox="0 0 20 20" fill="white" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;">
                    <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                </svg>
            </span>
            Agregar película
        </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Nueva película</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="crud_peliculas.php" method="post">
                        <label class="form-label" for="txt_nombre">Nombre de la película</label>
                        <input type="text" name="txt_nombre" id="txt_nombre" class="form-control" required>

                        <label for="txt_fecha_estreno" class="form-label">Fecha de estreno</label>
                        <input type="date" name="txt_fecha_estreno" id="txt_fecha_estreno" class="form-control" required>

                        <label for="txt_duracion_minutos" class="form-label">Duración (minutos)</label>
                        <input type="number" name="txt_duracion_minutos" id="txt_duracion_minutos" class="form-control" required>

                        <label for="txt_director_nombre" class="form-label">Director</label>
                        <input type="text" name="txt_director_nombre" id="txt_director_nombre" class="form-control" required>

                        <button type="submit" class="form-control btn btn-primary mt-3" name="btn_insertar">
                            Agregar película
                        </button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>      
            </div>
        </div>
    </div>
    <table class="table table-striped table-responsive animate__animated animate__fadeInUp">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre de la película</th>
            <th>Fecha estreno</th>
            <th>Duración (min)</th>
            <th>Director</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
<?php
while($datos = mysqli_fetch_assoc($resultado)){
?>
<tr>
    <td><?php echo $datos['pelicula_id'];?></td>
    <td><?php echo $datos['nombre_pelicula'];?></td>
    <td><?php echo $datos['fecha_estreno'];?></td>
    <td><?php echo $datos['duracion_minutos'];?></td>
    <td><?php echo htmlspecialchars($datos['nombre_director'] . ' ' . $datos['apellido_director']);?></td>
    <td class="d-flex flex-row">
        <!-- Botón editar -->
        <form action="actualizar_peliculas.php" method="get" class="me-2">
            <input type="hidden" name="pelicula_id" value="<?php echo $datos['pelicula_id']; ?>">
            <button type="submit" class="btn btn-warning btn-sm" title="Editar">
                <i class="bi bi-pencil"></i>
            </button>
        </form>
        <!-- Botón eliminar -->
        <form action="crud_peliculas.php" method="post" onsubmit="return confirm('¿Seguro que deseas eliminar esta película?');">
            <input type="hidden" name="hidden_pelicula_id" value="<?php echo $datos['pelicula_id']; ?>">
            <button type="submit" class="btn btn-danger btn-sm" name="btn_eliminar" title="Eliminar">
                <i class="bi bi-trash"></i>
            </button>
        </form>
    </td>
</tr>
<?php
}
?>
</tbody>
</table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Limpiar formulario al abrir modal
    var modal = document.getElementById('exampleModal');
    if(modal){
        modal.addEventListener('show.bs.modal', function () {
            document.querySelectorAll('#exampleModal input').forEach(input => input.value = '');
        });
    }
});
</script>
</body>
</html>