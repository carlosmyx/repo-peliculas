<?php 
    
    if (isset($_POST['hidden_pelicula_id'])) {
        $pelicula_id = intval($_POST['hidden_pelicula_id']);
    } elseif (isset($_GET['pelicula_id'])) {
        $pelicula_id = intval($_GET['pelicula_id']);
    } else {
        // Si no hay ID, redirigir o mostrar error
        die("ID de película no especificado.");
    }

    require_once("conexion.php");
    $sql = "SELECT * FROM peliculas WHERE pelicula_id = " . $pelicula_id;
    $ejecutar = mysqli_query($conexion, $sql);
    $datos = mysqli_fetch_assoc($ejecutar);

    // Si no se encuentra la película
    if (!$datos) {
        die("Película no encontrada.");
    }

    // Si el nombre del director está en la tabla
    $director_nombre = isset($datos['director_nombre']) ? $datos['director_nombre'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Película</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Modificar Película</h1>
        <form action="crud_peliculas.php" method="post">
            <input type="hidden" name="txt_pelicula_id" id="txt_pelicula_id" 
                value="<?php echo $pelicula_id;?>">

            <label class="form-label" for="txt_nombre">Nombre</label>
            <input type="text" name="txt_nombre" id="txt_nombre" 
                value="<?php echo htmlspecialchars($datos['nombre']);?>" 
                class="form-control">

            <label for="txt_fecha_estreno" class="form-label">Fecha de Estreno</label>
            <input type="date" name="txt_fecha_estreno" id="txt_fecha_estreno" 
                value="<?php echo $datos['fecha_estreno'];?>" class="form-control">

            <label for="txt_duracion_minutos" class="form-label">Duración (minutos)</label>
            <input type="number" name="txt_duracion_minutos" id="txt_duracion_minutos" 
                value="<?php echo $datos['duracion_minutos'];?>" class="form-control">

            <label for="txt_director_nombre" class="form-label">Nombre del director</label>
            <input type="text" name="txt_director_nombre" id="txt_director_nombre" 
                value="<?php echo htmlspecialchars($director_nombre); ?>" class="form-control">

            <button type="submit" class="form-control btn btn-primary"
                name="btn_modificar" id="btn_modificar">
                Modificar Película
            </button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>