<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro Evento</title>

</head>
<body>

<div class="container">
    <h2>Registro a Evento Tecnológico</h2>

    <form method="POST" action="../back/guardar.php">

        <fieldset>
            <label>Nombre</label>
            <input type="text" name="nombre" required>
        </fieldset>

        <fieldset>
            <label>Apellido</label>
            <input type="text" name="apellido" required>
        </fieldset>

        <fieldset>
            <label>Email</label>
            <input type="email" name="email" required>
        </fieldset>

        <fieldset>
            <label>Fecha de nacimiento</label>
            <input type="date" name="fecha_nacimiento" required>
        </fieldset>

        <fieldset>
            <label>Teléfono</label>
            <input type="text" name="telefono" required>
        </fieldset>

        <fieldset>
            <label>Puesto laboral</label>
            <select name="puesto" required>
                <option value="">Seleccionar</option>
                <option value="junior">Junior</option>
                <option value="semi">Semi Senior</option>
                <option value="senior">Senior</option>
            </select>
        </fieldset>

        <fieldset class="section">
            <label>Eventos</label>
            <div class="checkbox-group">
                <label><input type="checkbox" name="eventos[]" value="backend"> Backend - Lunes 13hs a 20hs</label>
                <label><input type="checkbox" name="eventos[]" value="frontend"> Frontend - Martes 18hs a 21hs</label>
                <label><input type="checkbox" name="eventos[]" value="devops"> DevOps - Miércoles 20hs a 23hs</label>
                <label><input type="checkbox" name="eventos[]" value="ia"> IA - Jueves 13hs a 20hs</label>
                <label><input type="checkbox" name="eventos[]" value="seguridad"> Seguridad - Viernes 18hs a 20hs</label>
            </div>
        </fieldset>

        <fieldset class="section">
            <label>Redes sociales</label>
            <div class="checkbox-group">
                <label><input type="checkbox" name="redes[]" value="instagram"> Instagram</label>
                <label><input type="checkbox" name="redes[]" value="twitter"> Twitter</label>
                <label><input type="checkbox" name="redes[]" value="linkedin"> LinkedIn</label>
            </div>
        </fieldset>

        <fieldset>
            <label>Rango salarial</label>
            <select name="sueldo">
                <option value="0-1000">0 - 1000 USD</option>
                <option value="1000-2000">1000 - 2000 USD</option>
                <option value="2000+">2000+ USD</option>
            </select>
        </fieldset>

        <button type="submit">Enviar</button>

    </form>
</div>

<script>
document.querySelector("form").addEventListener("submit", function(e) {
    let eventos = document.querySelectorAll('input[name="eventos[]"]:checked');
    
    if (eventos.length === 0) {
        alert("Seleccioná al menos un evento");
        e.preventDefault();
    }
});
</script>

</body>
</html>
