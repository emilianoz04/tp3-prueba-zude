<?php

$dataPath = __DIR__ . "/data/";

/* --------------------------
   OBTENER TIPO DE BUSQUEDA
---------------------------*/

$tipo = '';

if(isset($_POST['tipo']))
{
    $tipo = $_POST['tipo'];
}
elseif(isset($_GET['tipo']))
{
    $tipo = $_GET['tipo'];
}

if($tipo == '')
{
    die("Debe seleccionar una opción para filtrar.");
}

/* --------------------------
   DEFINIR ARCHIVO CATALOGO
---------------------------*/

if($tipo == "evento")
{
    $catalogo = "eventos.dat";
}
elseif($tipo == "puesto")
{
    $catalogo = "puestos-laborales.dat";
}
else
{
    $catalogo = "redes.dat";
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Filtro</title>
</head>
<body>

<h2>Filtro de búsqueda</h2>

<!-- FORMULARIO DE FILTRO -->

<form method="GET">

    <input type="hidden" name="tipo" value="<?php echo $tipo; ?>">

    <label>Seleccione opción:</label>

    <select name="valor" required>
        <option value="">Seleccionar</option>

        <?php

        if(file_exists($dataPath . $catalogo))
        {
            $lines = file($dataPath . $catalogo, FILE_IGNORE_NEW_LINES);

            foreach($lines as $line)
            {
                $datos = explode("|", $line);

                echo "<option value='".$datos[1]."'>".$datos[1]."</option>";
            }
        }

        ?>

    </select>

    <button type="submit">Filtrar</button>

</form>

<hr>

<?php

/* --------------------------
   PROCESAR FILTRO
---------------------------*/

if(isset($_GET['valor']))
{
    $valor = $_GET['valor'];

    if($valor == '')
    {
        echo "<p>Debe seleccionar un valor.</p>";
        exit;
    }

    /* RELACION SEGUN TIPO */

    if($tipo == "evento")
    {
        $relacion = "participante-evento.dat";
    }
    elseif($tipo == "puesto")
    {
        $relacion = "participante-puesto.dat";
    }
    else
    {
        $relacion = "participante-red.dat";
    }

    /* BUSCAR ID DEL VALOR */

    $idBuscado = null;

    if(file_exists($dataPath . $catalogo))
    {
        $lines = file($dataPath . $catalogo, FILE_IGNORE_NEW_LINES);

        foreach($lines as $line)
        {
            $datos = explode("|", $line);

            if($datos[1] == $valor)
            {
                $idBuscado = $datos[0];
                break;
            }
        }
    }

    if($idBuscado == null)
    {
        die("Valor inválido.");
    }

    /* BUSCAR PARTICIPANTES RELACIONADOS */

    $ids = [];

    if(file_exists($dataPath . $relacion))
    {
        $lines = file($dataPath . $relacion, FILE_IGNORE_NEW_LINES);

        foreach($lines as $line)
        {
            $datos = explode("|", $line);

            if($datos[1] == $idBuscado)
            {
                $ids[] = $datos[0];
            }
        }
    }

    echo "<h3>Total encontrados: ".count($ids)." participantes</h3>";

    /* MOSTRAR PARTICIPANTES */

    if(!file_exists($dataPath . "participantes.dat"))
    {
        die("No hay participantes registrados.");
    }

    $personas = file($dataPath . "participantes.dat", FILE_IGNORE_NEW_LINES);

    if(count($ids) == 0)
    {
        echo "<p>No se encontraron participantes.</p>";
    }
    else
    {
        echo "<ul>";

        foreach($personas as $p)
        {
            $d = explode("|", $p);

            if(in_array($d[0], $ids))
            {
                echo "<li>";
                echo "Nombre: ".$d[1]." ".$d[2]." | ";
                echo "Email: ".$d[3]." | ";
                echo "Nacimiento: ".$d[4]." | ";
                echo "Tel: ".$d[5]." | ";
                echo "Sueldo: ".$d[6];
                echo "</li>";
            }
        }

        echo "</ul>";
    }
}

?>

<br>

<a href="index-consultas.php">Volver</a>

</body>
</html>