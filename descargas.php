<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
spl_autoload_register(function($clase) {
    require "$clase.php";
});


funciones::controlAcceso($_POST);


switch ($_POST['enviar']) {

    case 'subirFichero':
        $fichero = $_FILES['fichero'];
        if (!empty($fichero['name'])) {
            $subida = funciones::subir_ficheros($fichero);
            header("Location:index.php?msj=$subida");
        } else
            header("Location:index.php?msj=Debe seleccionar un fichero");
        exit();


        break;
    case 'subirAcceder':
        $fichero = $_FILES['fichero'];
        if (!empty($fichero['name'])) {
            $subida = funciones::subir_ficheros($fichero);
            if (explode(" ", $subida)[0] == 'Error') {

                header("Location:index.php?msj=$subida");
                exit();
            } else {

                $download = funciones::muestraFicherosDownload();
            }
        } else {
            header("Location:index.php?msj=Debe seleccionar un fichero");
            exit();
        }


        break;
    case 'acceder':



        $download = funciones::muestraFicherosDownload();
        if ($_POST['name'] == 'admin') {

            $download .= funciones::muestraFicherosUpload();
        }

        break;

    case 'publicar':

        var_dump($_POST);
        if (sizeof($_POST) > 1) {
            //mover los archivos
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
