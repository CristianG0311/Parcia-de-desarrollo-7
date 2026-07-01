<?php
// public/reporte.php - Reporte con auditoría de integridad (verde/rojo)
require_once __DIR__ . '/../models/InscriptorModel.php';
require_once __DIR__ . '/../helpers/Integridad.php';

$modelo = new InscriptorModel();
$registros = $modelo->obtenerReporte();
$anioActual = date('Y');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte de Inscriptores</title>
<link rel="stylesheet" href="estilo.css">
</head>
<body>

<header class="top">
    <h1>Reporte de Inscriptores</h1>
    <p>Evento Tecnológico iTECH</p>
</header>

<div class="contenedor" style="max-width: 1150px;">

    <div class="acciones-reporte">
        <a href="index.php" class="btn-secundario">&larr; Nueva inscripción</a>
        <a href="exportar_excel.php" class="btn-secundario">Exportar a Excel</a>
    </div>

    <p><span class="badge badge-verde"></span> Registro íntegro &nbsp;&nbsp;
       <span class="badge badge-rojo"></span> Registro alterado / corrompido</p>

    <table>
        <thead>
        <tr>
            <th>Integridad</th>
            <th>Identidad</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Edad</th>
            <th>Sexo</th>
            <th>País Residencia</th>
            <th>Nacionalidad</th>
            <th>Correo</th>
            <th>Celular</th>
            <th>Temas</th>
            <th>Observaciones</th>
            <th>Fecha</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($registros as $r): ?>
            <?php
                $intacto = Integridad::verificar(
                    $r['nombre'] . ' ' . $r['apellido'],
                    $r['identidad'],
                    $r['correo'],
                    $r['celular'],
                    $r['sexo'],
                    $r['firma_integridad']
                );
                $claseBadge = $intacto ? 'badge-verde' : 'badge-rojo';
                $tituloBadge = $intacto ? 'Íntegro' : 'Alterado';
            ?>
            <tr>
                <td><span class="badge <?= $claseBadge ?>" title="<?= $tituloBadge ?>"></span></td>
                <td><?= htmlspecialchars($r['identidad']) ?></td>
                <td><?= htmlspecialchars($r['nombre']) ?></td>
                <td><?= htmlspecialchars($r['apellido']) ?></td>
                <td><?= htmlspecialchars($r['edad']) ?></td>
                <td><?= htmlspecialchars($r['sexo']) ?></td>
                <td><?= htmlspecialchars($r['pais_residencia']) ?></td>
                <td><?= htmlspecialchars($r['nacionalidad']) ?></td>
                <td><?= htmlspecialchars($r['correo']) ?></td>
                <td><?= htmlspecialchars($r['celular']) ?></td>
                <td><?= htmlspecialchars($r['temas'] ?? '') ?></td>
                <td><?= htmlspecialchars($r['observaciones']) ?></td>
                <td><?= htmlspecialchars($r['fecha_registro']) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($registros)): ?>
            <tr><td colspan="13" style="text-align:center;">Aún no hay inscripciones registradas.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<footer class="pie">
    &copy; <?= $anioActual ?> iTECH. All rights reserved.
</footer>

</body>
</html>
