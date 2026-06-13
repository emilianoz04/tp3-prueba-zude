<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultas</title>
</head>
<body>

<h2>Buscar participantes</h2>

<form action="filtro.php" method="POST">

    <label>Buscar por:</label>

    <select name="tipo" required>
        <option value="">Seleccionar</option>
        <option value="evento">Evento</option>
        <option value="puesto">Puesto laboral</option>
        <option value="red">Red social</option>
    </select>

    <button type="submit">Buscar</button>

</form>

</body>
</html>