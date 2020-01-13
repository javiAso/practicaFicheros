<?php

class funciones { //En esta clase están todas las funciones necesarias para el funcionamiento de la aplicación:

    public static function controlAcceso($post) {//Controla que el acceso sea a través del index e identificado
        if (empty($post)) {
            $msj = 'Debe acceder a descargas a traves del index :)';
            funciones::escribeLog("Se intenta un acceso sin pasar por el index");
            header("Location:index.php?msj=$msj");
            exit();
        }

        if ($post['enviar'] != 'publicar') {//Si no se viene de publicar, al que ya se ha tenido que acceder a traes del admin anteriormente
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
        $arraySeparado = explode(".", $nombreFichero);
        $extension = end($arraySeparado);
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

            funciones::escribeLog("El fichero $nombreFichero se ha subido correctamente");
            return ("El fichero $nombreFichero se ha subido correctamente");
        } else
            funciones::escribeLog("Error subiendo el fichero $nombreFichero");
        return ("Error subiendo el fichero $nombreFichero");
    }

    public static function muestraFicherosDownload() {//Crea una cadena HTML con el contenido de la carpeta Downloads
        $cadena = "<fieldset class='caja_centrada'><legend>ficheros listos para descargar</legend>";

        $ficheros = scandir("descargas/downloads");

        foreach ($ficheros as $dir) {//Recorro las carpetas
            if (strpos(".", $dir) === 0 || strpos("..", $dir) === 0)
                continue;

            $cadena .= "<fieldset><legend class='legend2'>$dir</legend><ul>";
            $carpeta = scandir("descargas/downloads/$dir");

            foreach ($carpeta as $fichero) {//Recorro los ficheros
                if (strpos(".", $fichero) === 0 || strpos("..", $fichero) === 0)
                    continue;
                $cadena .= "<li><a href='descargas/downloads/$dir/$fichero'>$fichero</a></li>";
            }
            $cadena .= "</ul></fieldset>";
        }
        $cadena .= "</fieldset>";
        return $cadena;
    }

    public static function muestraFicherosUpload() {//Crea una cadena HTML con el contenido de la carpeta Downloads eun un formulario
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

                $name = $dir . '[]'; //Para mandar los ficheros como un array (Se manda un array por carpeta)

                $cadena .= " <label><input type='checkbox' name='$name' value='$fichero'> $fichero</label><br>";
            }
            $cadena .= "</fieldset>";
        }
        $cadena .= "<input type='submit' name='enviar' value='publicar'></form></fieldset>";
        return $cadena;
    }

    public static function publicarArchivos($post) {//Cambia los archivos de carpeta, recibe el Post de cuando se pulsa publicar
        foreach ($post as $carpeta => $ficheros) {//Recorro todo el $_POST
            if ($carpeta == 'enviar') {//Salto el campo del submit del formulario
                continue;
            }

            foreach ($ficheros as $fichero) {//Recorro los arrays de cada carpeta
                if (rename("descargas/uploads/$carpeta/$fichero", "descargas/downloads/$carpeta/$fichero")) {
                    funciones::escribeLog("Se publica $fichero en la carpeta $carpeta");
                } else {
                    funciones::escribeLog("Error al publicar $fichero en la carpeta $carpeta");
                }
            }
        }
    }

    public static function escribeLog($mensaje) {//Debe estar la carpeta del proyecto con los correspondientes permisos y propietarios
        $file = "./logs/log.txt";

        if (!file_exists("logs")) {
            mkdir("logs", 0775);
            $msj = "Se ha creado la carpeta ficheros\n";
            file_put_contents($file, $msj); //Esta funcion, abre, escribe y cierra
        }

        $fecha = date('d-m-Y', strtotime("now"));
        $hora = date('H:i:s', strtotime("now"));
        $log = "El día $fecha a las $hora: $mensaje\n";

        file_put_contents($file, $log, FILE_APPEND);
    }

}

?>