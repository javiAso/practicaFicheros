<?php

class funciones {

    public static function controlAcceso($post) {

        if (empty($post)) {
            $msj = 'Debe acceder a descargas a traves del index :)';
            funciones::escribeLog("Se intenta un acceso sin pasar por el index");
            header("Location:index.php?msj=$msj");
            exit();
        }

        if ($post['enviar'] != 'publicar') {
            if ($post['name'] === '' || $post['pass'] === '') {
                $msj = 'Debe especificar user y pass :)';
                funciones::escribeLog("Se intenta un acceso sin especificar user o pass");
                header("Location:index.php?msj=$msj");
                exit();
            }
        }
    }

    public static function subir_ficheros($fichero) {

        //Accedemos al fichero que está de forma temporal en el servidor
        $origen = $fichero['tmp_name'];

        //Accedemos al nombre del fichero con el que el cliente lo subió
        $nombreFichero = $fichero['name'];
        //Comprobamos la extensión del fichero
        $extension = end(explode(".", $nombreFichero));
        //Establecemos la ruta donde queremos dejar el fichero dependiendo de su extensión
        switch ($extension) {
            case 'pdf':

                $destino = "descargas/uploads/pdf/" . $nombreFichero;


                break;
            case 'mp3': case 'wav':

                $destino = "descargas/uploads/musica/" . $nombreFichero;


                break;
            case 'jpg': case 'jpeg': case 'png':

                $destino = "descargas/uploads/imagenes/" . $nombreFichero;


                break;


            default:
                $destino = "descargas/uploads/otros/" . $nombreFichero;
        }


//Ahora procedemos a copiar y ver el éxito o fracaso
        if (move_uploaded_file($origen, $destino)) {

            return ("El fichero $nombreFichero se ha subido correctamente");
        } else
            return ("Error subiendo el fichero $nombreFichero");
    }

    public static function muestraFicherosDownload() {

        $cadena = "<fieldset class='caja_centrada'><legend>ficheros listos para descargar</legend>";

        $ficheros = scandir("descargas/downloads");

        foreach ($ficheros as $dir) {

            if (strpos(".", $dir) === 0 || strpos("..", $dir) === 0)
                continue;

            $cadena .= "<fieldset><legend class='legend2'>$dir</legend><ul>";
            $carpeta = scandir("descargas/downloads/$dir");

            foreach ($carpeta as $fichero) {
                if (strpos(".", $fichero) === 0 || strpos("..", $fichero) === 0)
                    continue;
                $cadena .= "<li><a href='descargas/downloads/$dir/$fichero'>$fichero</a></li>";
            }
            $cadena .= "</ul></fieldset>";
        }
        $cadena .= "</fieldset>";
        return $cadena;
    }

    public static function muestraFicherosUpload() {

        $cadena = "<fieldset class='caja_centrada'><legend>ficheros pendientes de revisión</legend><form action='descargas.php' method='POST'>";

        $ficheros = scandir("descargas/uploads");

        foreach ($ficheros as $dir) {

            if (strpos(".", $dir) === 0 || strpos("..", $dir) === 0)
                continue;

            $cadena .= "<fieldset><legend class='legend2'>$dir</legend>";
            $carpeta = scandir("descargas/uploads/$dir");


            foreach ($carpeta as $fichero) {
                if (strpos(".", $fichero) === 0 || strpos("..", $fichero) === 0)
                    continue;

                $name = $dir . '[]';

                $cadena .= " <label><input type='checkbox' name='$name' value='$fichero'> $fichero</label><br>";
            }
            $cadena .= "</fieldset>";
        }
        $cadena .= "<input type='submit' name='enviar' value='publicar'></form></fieldset>";
        return $cadena;
    }

    public static function publicarArchivos($post) {

        foreach ($post as $carpeta => $ficheros) {

            if ($carpeta == 'enviar') {
                continue;
            }

            foreach ($ficheros as $fichero) {

                if (rename("descargas/uploads/$carpeta/$fichero", "descargas/downloads/$carpeta/$fichero")) {
                    funciones::escribeLog("Se publica $fichero en la carpeta $carpeta");
                }
            }
        }
    }

    public static function escribeLog($mensaje) {//Debe estar la carpeta del proyecto con los correspondientes permisos y propietarios
        $file = "./logs/log.txt";

        if (!file_exists("logs")) {
            mkdir("logs", 0775);
            $msj = "Se ha creado la carpeta ficheros\n";
            file_put_contents($file, $msj);
        }

        $fecha = date('d-m-Y', strtotime("now"));
        $hora = date('H:i:s', strtotime("now"));
        $log = "El día $fecha a las $hora: $mensaje\n";

        file_put_contents($file, $log, FILE_APPEND); //Esta funcion, abre, escribe y cierra
    }

}

?>