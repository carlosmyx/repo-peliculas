<?php
require_once("conexion.php");

// Modificar película
if(isset($_POST['btn_modificar'])){
    $pelicula_id = intval($_POST['txt_pelicula_id']);
    $nombre = trim($_POST['txt_nombre']);
    $fecha_estreno = trim($_POST['txt_fecha_estreno']);
    $duracion_minutos = isset($_POST['txt_duracion_minutos']) ? intval($_POST['txt_duracion_minutos']) : 0;
    $director_nombre = isset($_POST['txt_director_nombre']) ? trim($_POST['txt_director_nombre']) : '';

    // Buscar o crear director
    $director_id = 0;
    if($director_nombre){
        $sql_buscar = "SELECT director_id FROM directores WHERE CONCAT(nombre, ' ', apellido) = '".mysqli_real_escape_string($conexion, $director_nombre)."' LIMIT 1";
        $res_buscar = mysqli_query($conexion, $sql_buscar);
        if($row = mysqli_fetch_assoc($res_buscar)){
            $director_id = $row['director_id'];
        } else {
            $partes = preg_split('/\s+/', $director_nombre, 2);
            $nombre_dir = $partes[0];
            $apellido_dir = isset($partes[1]) ? $partes[1] : '';
            $sql_insert_dir = "INSERT INTO directores (nombre, apellido) VALUES ('".mysqli_real_escape_string($conexion, $nombre_dir)."', '".mysqli_real_escape_string($conexion, $apellido_dir)."')";
            if(mysqli_query($conexion, $sql_insert_dir)){
                $director_id = mysqli_insert_id($conexion);
            } else {
                echo "Error al crear director: ".mysqli_error($conexion);
            }
        }
    }

    if($director_id > 0){
        $sql = 'UPDATE peliculas SET 
            nombre="'.mysqli_real_escape_string($conexion, $nombre).'", 
            fecha_estreno="'.mysqli_real_escape_string($conexion, $fecha_estreno).'", 
            duracion_minutos='.$duracion_minutos.', 
            director_id='.$director_id.' 
            WHERE pelicula_id='.$pelicula_id.';';
        if(mysqli_query($conexion, $sql)){
            header('Location: peliculas.php');
            exit;
        } else {
            echo "<br>Datos no actualizados<br>".mysqli_error($conexion);
        }
    } else {
        echo "Error: No se pudo determinar el director.";
    }
}

// Eliminar película
if(isset($_POST['btn_eliminar'])){
    $pelicula_id = intval($_POST['hidden_pelicula_id']);
    $sql = "DELETE FROM peliculas WHERE pelicula_id=".$pelicula_id;
    if(mysqli_query($conexion, $sql)){
        header('Location: peliculas.php');
        exit;
    } else {
        echo "<br>Datos no eliminados<br>".mysqli_error($conexion);        
    } 
}

// Insertar película
if(isset($_POST['btn_insertar'])){
    $nombre = isset($_POST['txt_nombre']) ? trim($_POST['txt_nombre']) : '';
    $fecha_estreno = isset($_POST['txt_fecha_estreno']) ? trim($_POST['txt_fecha_estreno']) : '';
    $duracion_minutos = isset($_POST['txt_duracion_minutos']) ? intval($_POST['txt_duracion_minutos']) : 0;
    $director_nombre = isset($_POST['txt_director_nombre']) ? trim($_POST['txt_director_nombre']) : '';

    if($nombre && $fecha_estreno && $duracion_minutos && $director_nombre){
        // Buscar si el director ya existe
        $sql_buscar = "SELECT director_id FROM directores WHERE CONCAT(nombre, ' ', apellido) = '".mysqli_real_escape_string($conexion, $director_nombre)."' LIMIT 1";
        $res_buscar = mysqli_query($conexion, $sql_buscar);
        if($row = mysqli_fetch_assoc($res_buscar)){
            $director_id = $row['director_id'];
        }  else {
            $partes = preg_split('/\s+/', $director_nombre, 2);
            $nombre_dir = $partes[0];
            $apellido_dir = isset($partes[1]) ? $partes[1] : '';
            $sql_insert_dir = "INSERT INTO directores (nombre, apellido) VALUES ('".mysqli_real_escape_string($conexion, $nombre_dir)."', '".mysqli_real_escape_string($conexion, $apellido_dir)."')";
            if(mysqli_query($conexion, $sql_insert_dir)){
                $director_id = mysqli_insert_id($conexion);
            } else {
                echo "Error al crear director: ".mysqli_error($conexion);
            }
        }

        if($director_id > 0){
            $sql = "INSERT INTO peliculas (nombre, fecha_estreno, duracion_minutos, director_id) 
                VALUES (
                    '".mysqli_real_escape_string($conexion, $nombre)."', 
                    '".mysqli_real_escape_string($conexion, $fecha_estreno)."', 
                    $duracion_minutos, $director_id
                )";
            if(mysqli_query($conexion, $sql)){
                header('Location: peliculas.php');
                exit;
            } else {
                echo "<br>Datos no fueron guardados<br>".mysqli_error($conexion);        
            } 
        } else {
            echo "Error: No se pudo determinar el director.";
        }
    } else {
        echo "Error: Faltan datos obligatorios.";
    }
}
?>