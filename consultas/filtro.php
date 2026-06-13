<?php
$dataPath = "../Data/";

$tipo = $_POST['tipo'] ?? '';

if($tipo == '')
{
    die("Debe seleccionar una opción para filtrar.");
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

<form method="GET">

    <input type="hidden" name="tipo" value="<?= $tipo ?>">

    <label>Seleccione opción:</label>

    <select name="valor" required>

        <option value="">Seleccionar</option>

        <?php

        if($tipo == "evento")
        {
            $file = $dataPath."eventos.dat";
        }
        elseif($tipo == "puesto")
        {
            $file = $dataPath."puestos-laborales.dat";
        }
        else
        {
            $file = $dataPath."redes.dat";
        }

        if(file_exists($file))
        {
            $lines = file($file, FILE_IGNORE_NEW_LINES);

            foreach($lines as $line)
            {
                list($id,$nombre) = explode("|",$line);
                echo "<option value='$nombre'>$nombre</option>";
            }
        }

        ?>

    </select>

    <button type="submit">Filtrar</button>

</form>

<hr>
<?php

if(isset($_GET['valor']))
{
    $valor = $_GET['valor'];

    if($valor == '')
    {
        echo "<p>Debe seleccionar un valor.</p>";
        exit;
    }

    if($tipo == "evento")
        $relacion = "participante-evento.dat";
    elseif($tipo == "puesto")
        $relacion = "participante-puesto.dat";
    else
        $relacion = "participante-red.dat";

    $idBuscado = null;

    $catalogo = ($tipo == "evento") ? "eventos.dat" :
                (($tipo == "puesto") ? "puestos-laborales.dat" : "redes.dat");

    $lines = file($dataPath.$catalogo, FILE_IGNORE_NEW_LINES);

    foreach($lines as $line)
    {
        list($id,$nombre) = explode("|",$line);

        if($nombre == $valor)
        {
            $idBuscado = $id;
            break;
        }
    }

    if($idBuscado == null)
    {
        die("Valor inválido");
    }

    $ids = [];

    $lines = file($dataPath.$relacion, FILE_IGNORE_NEW_LINES);

    foreach($lines as $line)
    {
        list($idP,$idR) = explode("|",$line);

        if($idR == $idBuscado)
        {
            $ids[] = $idP;
        }
    }

    echo "<h3>Total: ".count($ids)." participantes</h3>";

    $personas = file($dataPath."participantes.dat", FILE_IGNORE_NEW_LINES);

    echo "<ul>";

    foreach($personas as $p)
    {
        $d = explode("|",$p);

        if(in_array($d[0], $ids))
        {
            echo "<li>{$d[1]} {$d[2]} - {$d[3]}</li>";
        }
    }

    echo "</ul>";
}
?>