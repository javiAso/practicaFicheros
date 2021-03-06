<?php
spl_autoload_register(function($clase) {
    require "$clase.php";
});

funciones::controlAcceso($_POST); //Controlo que el acceso sea correcto
($_POST['enviar'] == 'publicar') ? $nombreUsuario = "" : $nombreUsuario = $_POST['name'];

switch ($_POST['enviar']) {//Controlo el acceso desde los distintos submit
    case 'subirFichero':
        $fichero = $_FILES['fichero'];
        if (!empty($fichero['name'])) {
            $subida = funciones::subir_ficheros($fichero);
            funciones::escribeLog("El usuario $nombreUsuario ha intentado subir un fichero obreniendo el siguiente resultado: $subida");
            header("Location:index.php?msj=$subida");
            exit();
        } else {

            funciones::escribeLog("El usuario $nombreUsuario ha intentado subir un fichero sin seleccionarlo previamente");
            header("Location:index.php?msj=Debe seleccionar un fichero");

            exit();
        }

        break;
    case 'subirAcceder':
        $fichero = $_FILES['fichero'];
        if (!empty($fichero['name'])) {
            $subida = funciones::subir_ficheros($fichero);
            funciones::escribeLog("El usuario $nombreUsuario ha intentado subir un fichero y acceder obteniendo el siguiente resultado: $subida");

            if (explode(" ", $subida)[0] == 'Error') {//Si la funcion subir ficheros me devuelve un error mando al user al index
                header("Location:index.php?msj=$subida");
                exit();
            } else {

                $download = funciones::muestraFicherosDownload();
                if ($nombreUsuario == 'admin') {

                    $download .= funciones::muestraFicherosUpload();
                }
            }
        } else {
            funciones::escribeLog("El usuario $nombreUsuario ha intentado acceder sin seleccionar un fichero");
            header("Location:index.php?msj=Debe seleccionar un fichero");
            exit();
        }


        break;
    case 'acceder':

        funciones::escribeLog("Acceso sin subida de archivo del user " . $nombreUsuario);

        $download = funciones::muestraFicherosDownload();
        if ($nombreUsuario == 'admin') {

            $download .= funciones::muestraFicherosUpload();
        }

        break;

    case 'publicar':


        if (sizeof($_POST) > 1) {//Puede ser que el admin no haya seleccionado ningún checkbox; en ese caso no llamo a la función.
            funciones::publicarArchivos($_POST);
        }

        $download = funciones::muestraFicherosDownload() . funciones::muestraFicherosUpload();

        break;
}
?>

<html>
    <head>
        <title></title>
        <link href="css/estilo.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <h2>Página de descargas</h2>
        <?= $download ?>
    </body>
</html>
