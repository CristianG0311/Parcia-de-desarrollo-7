<?php
// public/exportar_excel.php
// Exporta el reporte de inscriptores a Excel usando PhpSpreadsheet
// (mismo patrón que tu EjemploExcelIntegrado.php)

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../models/InscriptorModel.php';
require_once __DIR__ . '/../helpers/Integridad.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$modelo = new InscriptorModel();
$registros = $modelo->obtenerReporte();

$documento = new Spreadsheet();
$documento->getProperties()
    ->setCreator("iTECH")
    ->setLastModifiedBy("Sistema de Inscripciones")
    ->setTitle("Reporte de Inscriptores")
    ->setDescription("Inscriptores exportados desde MySQL - parcial_itech");

$hoja = $documento->getActiveSheet();
$hoja->setTitle("Inscriptores");

$encabezado = [
    "Integridad", "Identidad", "Nombre", "Apellido", "Edad", "Sexo",
    "País Residencia", "Nacionalidad", "Correo", "Celular",
    "Temas de Interés", "Observaciones", "Fecha de Registro"
];
$hoja->fromArray($encabezado, null, 'A1');
$hoja->getStyle('A1:M1')->getFont()->setBold(true);
$hoja->getStyle('A1:M1')->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setRGB('0F3D63');
$hoja->getStyle('A1:M1')->getFont()->getColor()->setRGB('FFFFFF');

$fila = 2;
foreach ($registros as $r) {
    $intacto = Integridad::verificar(
        $r['nombre'] . ' ' . $r['apellido'],
        $r['identidad'],
        $r['correo'],
        $r['celular'],
        $r['sexo'],
        $r['firma_integridad']
    );

    $hoja->setCellValue('A' . $fila, $intacto ? 'Íntegro' : 'Alterado');
    $hoja->setCellValue('B' . $fila, $r['identidad']);
    $hoja->setCellValue('C' . $fila, $r['nombre']);
    $hoja->setCellValue('D' . $fila, $r['apellido']);
    $hoja->setCellValue('E' . $fila, $r['edad']);
    $hoja->setCellValue('F' . $fila, $r['sexo']);
    $hoja->setCellValue('G' . $fila, $r['pais_residencia']);
    $hoja->setCellValue('H' . $fila, $r['nacionalidad']);
    $hoja->setCellValue('I' . $fila, $r['correo']);
    $hoja->setCellValue('J' . $fila, $r['celular']);
    $hoja->setCellValue('K' . $fila, $r['temas']);
    $hoja->setCellValue('L' . $fila, $r['observaciones']);
    $hoja->setCellValue('M' . $fila, $r['fecha_registro']);

    // Color de la celda de integridad: verde si íntegro, rojo si alterado
    $colorCelda = $intacto ? 'C6EFCE' : 'FFC7CE';
    $hoja->getStyle('A' . $fila)->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setRGB($colorCelda);

    $fila++;
}

foreach (range('A', 'M') as $col) {
    $hoja->getColumnDimension($col)->setAutoSize(true);
}

// Guardar copia en el servidor
if (!is_dir(__DIR__ . '/../doc_exportados')) {
    mkdir(__DIR__ . '/../doc_exportados', 0777, true);
}
$rutaArchivo = __DIR__ . '/../doc_exportados/Reporte_Inscriptores_' . date('Ymd_His') . '.xlsx';
$writer = new Xlsx($documento);
$writer->save($rutaArchivo);

// Enviar directo al navegador para descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte_Inscriptores.xlsx"');
header('Cache-Control: max-age=0');

$writerDescarga = new Xlsx($documento);
$writerDescarga->save('php://output');
exit;
