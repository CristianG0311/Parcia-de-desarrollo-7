<?php
require_once __DIR__ . '/../models/InscriptorModel.php';
$modelo = new InscriptorModel();
$paises = $modelo->obtenerPaises();
$areas  = $modelo->obtenerAreasInteres();
$anioActual = date('Y');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Inscripción - Evento iTECH</title>
<link rel="stylesheet" href="estilo.css">
</head>
<body>

<header class="top">
    <h1>Formulario de Inscripción</h1>
    <p>Evento Tecnológico iTECH</p>
</header>

<div class="contenedor">

    <?php if (isset($_GET['ok'])): ?>
        <div class="alerta-ok">Inscripción registrada correctamente. <a href="reporte.php">Ver reporte</a></div>
    <?php endif; ?>

    <form action="guardar.php" method="POST" novalidate>

        <fieldset>
            <legend>Datos Personales</legend>

            <div class="campo">
                <label for="identidad">Identidad (Documento de Identificación)</label>
                <input type="text" id="identidad" name="identidad" placeholder="8-1020-424" required>
            </div>

            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div class="campo">
                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" required>
            </div>

            <div class="campo">
                <label for="edad">Edad</label>
                <input type="number" id="edad" name="edad" min="15" max="120" required>
            </div>

            <div class="campo">
                <label for="sexo">Sexo</label>
                <select id="sexo" name="sexo" required>
                    <option value="">Seleccione...</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>

            <div class="campo">
                <label for="pais_residencia_id">País de Residencia</label>
                <select id="pais_residencia_id" name="pais_residencia_id" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($paises as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="campo">
                <label for="nacionalidad_id">Nacionalidad</label>
                <select id="nacionalidad_id" name="nacionalidad_id" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($paises as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </fieldset>

        <fieldset>
            <legend>Información de Contacto</legend>

            <div class="campo">
                <label for="correo">Correo</label>
                <input type="email" id="correo" name="correo" required>
            </div>

            <div class="campo">
                <label for="celular">Celular</label>
                <input type="tel" id="celular" name="celular" placeholder="6000-0000" required>
            </div>
        </fieldset>

        <fieldset>
            <legend>Tema Tecnológico que le gustaría aprender</legend>
            <div class="checkbox-grid">
                <?php foreach ($areas as $a): ?>
                    <label>
                        <input type="checkbox" name="temas[]" value="<?= $a['id'] ?>">
                        <?= htmlspecialchars($a['nombre']) ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </fieldset>

        <fieldset>
            <legend>Observaciones</legend>
            <div class="campo">
                <label for="observaciones">Observaciones o Consulta sobre el evento</label>
                <textarea id="observaciones" name="observaciones"></textarea>
            </div>
        </fieldset>

        <button type="submit" class="btn">Registrar Inscripción</button>
        <a href="reporte.php" class="btn-secundario">Ver reporte</a>
    </form>
</div>

<footer class="pie">
    &copy; <?= $anioActual ?> iTECH. All rights reserved. | contacto@itech-evento.com
</footer>

</body>
</html>
