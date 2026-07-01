<?php
// helpers/Integridad.php
// Validación y Auditoría de Integridad (requisito #20 de la rúbrica)
// Firma cada registro (Nombre, Identificación, Correo, Teléfono, Sexo)
// usando HMAC-SHA256 (OpenSSL) con una llave secreta del servidor.
// Si alguien modifica esos campos directamente en la BD, la firma ya no
// coincidirá y el reporte lo marcará en rojo.

class Integridad
{
    // En un proyecto real esta llave debe vivir fuera del código (ej. variable de entorno)
    private static string $llaveSecreta = "iTECH-2025-llave-secreta-parcial";

    /**
     * Genera la cadena base que se firma, a partir de los campos sensibles.
     */
    private static function cadenaBase(string $nombre, string $identidad, string $correo, string $celular, string $sexo): string
    {
        return $nombre . "|" . $identidad . "|" . $correo . "|" . $celular . "|" . $sexo;
    }

    /**
     * Firma un registro usando HMAC-SHA256 (OpenSSL hash_hmac).
     */
    public static function firmar(string $nombre, string $identidad, string $correo, string $celular, string $sexo): string
    {
        $base = self::cadenaBase($nombre, $identidad, $correo, $celular, $sexo);
        return hash_hmac('sha256', $base, self::$llaveSecreta);
    }

    /**
     * Verifica si la firma almacenada corresponde a los datos actuales del registro.
     * Devuelve true (verde) si íntegro, false (rojo) si fue alterado.
     */
    public static function verificar(string $nombre, string $identidad, string $correo, string $celular, string $sexo, ?string $firmaGuardada): bool
    {
        if (!$firmaGuardada) return false;
        $firmaActual = self::firmar($nombre, $identidad, $correo, $celular, $sexo);
        return hash_equals($firmaActual, $firmaGuardada);
    }
}
