<?php
// helpers/Sanitizer.php
// Sanitización / normalización de datos - todos los métodos son estáticos

class Sanitizer
{
    public static function texto($valor): string
    {
        return htmlspecialchars(trim(strip_tags($valor)), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Convierte a formato "Título": primera letra de cada palabra en mayúscula.
     * Requisito #23 de la rúbrica.
     */
    public static function tipoTitulo($valor): string
    {
        $valor = self::texto($valor);
        return mb_convert_case(mb_strtolower($valor, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
    }

    public static function correo($valor): string
    {
        return filter_var(trim($valor), FILTER_SANITIZE_EMAIL);
    }

    public static function entero($valor): int
    {
        return (int) filter_var($valor, FILTER_SANITIZE_NUMBER_INT);
    }

    public static function celular($valor): string
    {
        return preg_replace('/[^0-9\-\+\s]/', '', trim($valor));
    }

    /**
     * Sanitiza y normaliza todo el arreglo del formulario listo para insertar en BD.
     */
    public static function sanitizarInscriptor(array $data): array
    {
        return [
            'identidad'          => self::texto($data['identidad']),
            'nombre'             => self::tipoTitulo($data['nombre']),
            'apellido'           => self::tipoTitulo($data['apellido']),
            'edad'               => self::entero($data['edad']),
            'sexo'               => self::texto($data['sexo']),
            'pais_residencia_id' => self::entero($data['pais_residencia_id']),
            'nacionalidad_id'    => self::entero($data['nacionalidad_id']),
            'correo'             => self::correo($data['correo']),
            'celular'            => self::celular($data['celular']),
            'observaciones'      => self::texto($data['observaciones'] ?? ''),
        ];
    }
}
