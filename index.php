<?php

$archivo_abierto = null;

if ($_GET) {
    if ($_GET['archivo']) {

        $cuerpo = file_get_contents(__DIR__."/notas/" . urldecode($_GET['archivo']));

        $archivo_abierto = array('cuerpo' => $cuerpo, 'nombre' => urldecode($_GET['archivo']));
    }
} else {
    if ($_POST['crear-archivo']) {
        if (count(explode('/', $_POST['crear-archivo'])) > 1) {
            header('Location: index.php');
            die;
        }

        $archivo_nuevo = fopen(__DIR__."/notas/{$_POST['nombre_archivo']}.txt", 'w');
        fclose($archivo_nuevo);

        header('Location: index.php?archivo=' . urlencode($_POST['nombre_archivo']));
        die;
    } else if ($_POST['guardar-archivo']) {
        $archivo_abierto = fopen(__DIR__."/notas/{$_POST['nombre_archivo']}" . 
            (strpos($_POST['nombre_archivo'], '.txt') === false ? '.txt' : '')
            , 'w');

        fwrite($archivo_abierto, $_POST['cuerpo_archivo'] ?? '');

        fclose($archivo_abierto);


        header('Location: index.php?archivo=' . urlencode($_POST['nombre_archivo']));
        die;
    } else if ($_POST['crear-carpeta']) {
        mkdir(__DIR__."/notas/{$_POST['nombre_carpeta']}");
        header("Refresh:0");
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloc de notas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row max-vh-50 min-vh-50">
            <div class="col-sm-12 col-md-8">
                <?php if ($_GET['archivo']) { ?>
                    <form action="index.php" method="post">
                        <label for="cuerpo_archivo"></label>
                        <input type="text" class="form-control" name="nombre_archivo" <?php if ($archivo_abierto) {
                                                                                            echo 'value="' . $archivo_abierto['nombre'] . '"';
                                                                                        } ?> placeholder="Nombre archivo" readonly>
                        <textarea name="cuerpo_archivo" id="cuerpo_archivo" class="form-control" cols="30" rows="10"><?php if ($archivo_abierto) echo $cuerpo; ?></textarea>
                        <input type="submit" value="Guardar archivo" class="btn btn-success mt-1" name="guardar-archivo">
                    </form>
                <?php } ?>
            </div>
            <div class="col-sm-12 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <ul>
                            <?php
                            $archivos = scandir(__DIR__.'/notas');
        
                            foreach ($archivos as $archivo) {
                                if ($archivo == '.' || $archivo == '..') continue;
        
                                $archivo_url = urlencode($archivo);
        
                                if (is_dir("notas/$archivo")) {
                                    echo "<li>$archivo <a href='delete_dir.php?archivo=$archivo_url' class='btn btn-danger'>Eliminar</a></li>";
        
                                    $archivos_sub = scandir("notas/$archivo");
        
                                    echo '<ul>';
        
        
                                    foreach ($archivos_sub as $archivo_sub) {
                                        if (is_dir(__DIR__."/notas/$archivo/$archivo_sub")) continue;
        
                                        $archivo_url = urlencode("$archivo/$archivo_sub");
        
                                        echo "<li><a href='index.php?archivo=$archivo_url'>$archivo_sub</a><a href='delete_archivo.php?archivo=$archivo_url' class='btn btn-danger'>Eliminar</a></li>";
                                    }
                                    echo '</ul>';
                                } else {
                                    echo "<li><a href='index.php?archivo=$archivo_url'>$archivo</a><a href='delete_archivo.php?archivo=$archivo_url' class='btn btn-danger'>Eliminar</a></li>";
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
        <div class="row mt-2">
            <div class="col">
                <form action="index.php" method="post">
                    <input type="text" class="form-control" name="nombre_archivo" placeholder="Nombre archivo">
                    <input type="submit" value="Crear archivo" class="btn btn-primary mt-1" name="crear-archivo">
                </form>
            </div>
            <div class="col">
            <form action="index.php" method="post">
                    <input type="text" class="form-control" name="nombre_carpeta" placeholder="Nombre de carpeta">
                    <input type="submit" value="Crear carpeta" class="btn btn-primary mt-1" name="crear-carpeta">
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>

</html>