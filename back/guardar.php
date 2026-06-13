<?php
ob_start(); // 🔹 Captura todos los echo

$dataPath = __DIR__ . "/../consultas/data/";
//recibe datos
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$email = $_POST['email'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$telefono = $_POST['telefono'];
$puesto = $_POST['puesto'];
$sueldo = $_POST['sueldo'];
$eventos = $_POST['eventos'] ?? [];
$redes = $_POST['redes'] ?? [];

$validado = true;

// NOMBRE
if (isset($nombre) && !empty(trim($nombre))) {
    if (preg_match('/^[A-Za-z]+$/', $nombre) && strlen(trim($nombre)) >= 2) {
        echo "Nombre válido<br>";
    } else {
        echo "Nombre inválido<br>";
        $validado = false;
    }
} else {
    $validado = false;
}

// APELLIDO
if (isset($apellido) && !empty(trim($apellido))) {
    if (preg_match('/^[A-Za-z]+$/', $apellido) && strlen(trim($apellido)) >= 2) {
        echo "Apellido válido<br>";
    } else {
        echo "Apellido inválido<br>";
        $validado = false;
    }
} else {
    $validado = false;
}

// EMAIL
if (isset($email) && !empty(trim($email))) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email válido<br>";
    } else {
        echo "Email inválido<br>";
        $validado = false;
    }
} else {
    $validado = false;
}

// FECHA NACIMIENTO
if (isset($fecha_nacimiento) && !empty($fecha_nacimiento)) {
    $hoy = date("Y-m-d");
    if ($fecha_nacimiento <= $hoy) {
        $edad = date_diff(date_create($fecha_nacimiento), date_create('today'))->y;
        if ($edad >= 16) {
            echo "Edad válida ($edad años)<br>";
        } else {
            echo "Debe ser mayor de 16 años<br>";
            $validado = false;
        }
    } else {
        echo "La fecha no puede ser futura<br>";
        $validado = false;
    }
} else {
    $validado = false;
}

// TELEFONO
if (isset($telefono) && !empty(trim($telefono))) {
    if (preg_match('/^[0-9]{7,15}$/', $telefono)) {
        echo "Teléfono válido<br>";
    } else {
        echo "Teléfono inválido<br>";
        $validado = false;
    }
} else {
    $validado = false;
}

// PUESTO
if (isset($puesto) && !empty($puesto)) {
    echo "Puesto recibido<br>";
} else {
    $validado = false;
}

// EVENTOS
if (!isset($eventos) || count($eventos) == 0) {
    echo "Debe seleccionar al menos un evento<br>";
    $validado = false;
}

// BUSCAR ID PUESTO
$idPuesto = null;
$archivoPuestos = $dataPath . "puestos-laborales.dat";
if (file_exists($archivoPuestos)) {
    $lineas = file($archivoPuestos, FILE_IGNORE_NEW_LINES);
    foreach ($lineas as $linea) {
        $datos = explode("|", $linea);
        if ($datos[1] == $puesto) {
            $idPuesto = $datos[0];
        }
    }
}

if ($idPuesto === null) {
    echo "Puesto inválido<br>";
    $validado = false;
}

// VALIDAR EVENTOS
$eventos_ids = [];
$archivoEventos = $dataPath . "eventos.dat";

foreach ($eventos as $evento) {
    $idEvento = null;
    if (file_exists($archivoEventos)) {
        $lineas = file($archivoEventos, FILE_IGNORE_NEW_LINES);
        foreach ($lineas as $linea) {
            $datos = explode("|", $linea);
            if ($datos[1] == $evento) {
                $idEvento = $datos[0];
            }
        }
    }

    if ($idEvento === null) {
        echo "Evento inválido: $evento<br>";
        $validado = false;
    } else {
        $eventos_ids[] = $idEvento;
    }
}

// VALIDAR REDES
$redes_ids = [];
$archivoRedes = $dataPath . "redes.dat";

foreach ($redes as $red) {
    $idRed = null;
    if (file_exists($archivoRedes)) {
        $lineas = file($archivoRedes, FILE_IGNORE_NEW_LINES);
        foreach ($lineas as $linea) {
            $datos = explode("|", $linea);
            if ($datos[1] == $red) {
                $idRed = $datos[0];
            }
        }
    }

    if ($idRed !== null) {
        $redes_ids[] = $idRed;
    }
}

// GUARDAR
if ($validado) {
    $archivoParticipantes = $dataPath . "participantes.dat";

    if (!file_exists($archivoParticipantes)) {
        $idParticipante = 1;
    } else {
        $lineas = file($archivoParticipantes, FILE_IGNORE_NEW_LINES);
        if (empty($lineas)) {
            $idParticipante = 1;
        } else {
            $ultima = explode("|", end($lineas));
            $idParticipante = $ultima[0] + 1;
        }
    }

    $linea = "$idParticipante|$nombre|$apellido|$email|$fecha_nacimiento|$telefono|$sueldo\n";
    file_put_contents($archivoParticipantes, $linea, FILE_APPEND);

    file_put_contents($dataPath . "participante-puesto.dat", "$idParticipante|$idPuesto\n", FILE_APPEND);

    foreach ($eventos_ids as $idEvento) {
        file_put_contents($dataPath . "participante-evento.dat", "$idParticipante|$idEvento\n", FILE_APPEND);
    }

    foreach ($redes_ids as $idRed) {
        file_put_contents($dataPath . "participante-red.dat", "$idParticipante|$idRed\n", FILE_APPEND);
    }

    $mensajeFinal = "Registro exitoso";
} else {
    $mensajeFinal = "Errores en el formulario";
}

$detalles = ob_get_clean(); // 🔹 Guarda todos los echo
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Resultado</title>

<style>
body {
    margin: 0;
    background: #1e1e1e;
    color: white;
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.card {
    background: #2a2a2a;
    padding: 30px;
    border-radius: 12px;
    width: 400px;
    text-align: center;
    box-shadow: 0 0 20px rgba(0,0,0,0.5);
}
.success {
    color: #00ffae;
}
.error {
    color: #ff4d4d;
}
.detalles {
    margin-top: 15px;
    font-size: 14px;
    color: #ccc;
    text-align: left;
}
a {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 15px;
    background: #00aaff;
    color: white;
    text-decoration: none;
    border-radius: 6px;
}
</style>

</head>
<body>

<div class="card">
    <h2 class="<?= $validado ? 'success' : 'error' ?>">
        <?= $mensajeFinal ?>
    </h2>

    <div class="detalles">
        <?= $detalles ?>
    </div>

    <a href="../form/index.form.php">Volver</a>
    <a href="../consultas/index-consultas.php">o consultar datos</a>
</div>

</body>
</html>