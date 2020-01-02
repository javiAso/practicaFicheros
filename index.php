<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (empty($_GET['msj'])) {
    $msj = "";
} else {

    $msj = $_GET['msj'];
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link href="css/estilo.css" rel="stylesheet" type="text/css">

    </head>
    <body>

        <fieldset class="caja_centrada">
            <div class="error"></div>
            <legend style="font-size:20px;font-style: oblique;background:aliceblue ">Subida de ficheros</legend>
            <form action="descargas.php" method="POST" enctype="multipart/form-data">
                <p class="msj"><?= $msj ?></p>
                <br/>
                Usuario&nbsp&nbsp&nbsp <input type="text" name="name">
                <br>
                Password <input type="text" name="pass">
                <br/>
                <br/>
                <!-- MAX_FILE_SIZE debe preceder al campo de entrada del fichero -->
                <!--    <input type="hidden" name="MAX_FILE_SIZE" value=1024 />-->
                <div style="float:right">

                    <input type="file" name="fichero"><br>
                </div>
                <br>
                <br>
                <input type="submit" value="subirFichero" name="enviar">
                <input type="submit" value="subirAcceder" name="enviar">
                <input type="submit" value="acceder" name="enviar">

            </form>
        </fieldset>

    </body>
</html>