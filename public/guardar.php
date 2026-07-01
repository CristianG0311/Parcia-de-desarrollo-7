<?php
// public/guardar.php - Controlador: valida, sanitiza y guarda la inscripción
require_once __DIR__ . '/../helpers/Validator.php';
require_once __DIR__ . '/../helpers/Sanitizer.php';
require_once __DIR__ . '/../models/InscriptorModel.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$errores = Validator::validarInscriptor($_POST);

if (!empty($errores)) {
    echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8">
          <title>Errores de validación</title>
          <link rel="stylesheet" href="estilo.css"></head><body>';
    echo '<div class="contenedor"><div class="alerta-error"><strong>Se encontraron errores:</strong><ul>';
    foreach ($errores as $e) {
        echo '<li>' . htmlspecialchars($e) . '</li>';
    }
    echo '</ul></div><a class="btn-secundario" href="index.php">Volver al formulario</a></div></body></html>';
    exit;
}

$datosLimpios = Sanitizer::sanitizarInscriptor($_POST);
$temas = array_map('intval', $_POST['temas'] ?? []);

$modelo = new InscriptorModel();
$id = $modelo->guardar($datosLimpios, $temas);

if ($id) {
    header('Location: index.php?ok=1');
    exit;
} else {
    echo '<div class="contenedor"><div class="alerta-error">Ocurrió un error al guardar. Verifique que la identidad, correo o celular no estén duplicados.</div>
          <a class="btn-secundario" href="index.php">Volver</a></div>';
}
