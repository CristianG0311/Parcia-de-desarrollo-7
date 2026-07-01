<?php
// helpers/Validator.php
// Validación de datos del lado de PHP - todos los métodos son estáticos

class Validator
{
    public static function requerido($valor): bool
    {
        return isset($valor) && trim($valor) !== "";
    }

    public static function identidad($valor): bool
    {
        // Acepta formatos de cédula panameña estilo 8-1020-424 o similares
        return (bool) preg_match('/^[A-Za-z0-9\-]{5,20}$/', $valor);
    }

    public static function soloLetras($valor): bool
    {
        return (bool) preg_match('/^[\p{L}\s]{2,100}$/u', $valor);
    }

    public static function edad($valor): bool
    {
        return is_numeric($valor) && $valor >= 15 && $valor <= 120;
    }

    public static function sexo($valor): bool
    {
        return in_array($valor, ['Masculino', 'Femenino', 'Otro']);
    }

    public static function correo($valor): bool
    {
        return filter_var($valor, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function celular($valor): bool
    {
        return (bool) preg_match('/^[0-9\-\+\s]{7,20}$/', $valor);
    }

    public static function entero($valor): bool
    {
        return filter_var($valor, FILTER_VALIDATE_INT) !== false;
    }

    /**
     * Valida el arreglo completo del formulario.
     * Devuelve un arreglo de errores (vacío si todo es válido).
     */
    public static function validarInscriptor(array $data): array
    {
        $errores = [];

        if (!self::requerido($data['identidad'] ?? '') || !self::identidad($data['identidad']))
            $errores[] = "La identidad es obligatoria y debe tener un formato válido.";

        if (!self::soloLetras($data['nombre'] ?? ''))
            $errores[] = "El nombre es obligatorio y solo debe contener letras.";

        if (!self::soloLetras($data['apellido'] ?? ''))
            $errores[] = "El apellido es obligatorio y solo debe contener letras.";

        if (!self::edad($data['edad'] ?? ''))
            $errores[] = "La edad debe ser un número entre 15 y 120.";

        if (!self::sexo($data['sexo'] ?? ''))
            $errores[] = "Debe seleccionar un sexo válido.";

        if (!self::entero($data['pais_residencia_id'] ?? ''))
            $errores[] = "Debe seleccionar un país de residencia.";

        if (!self::entero($data['nacionalidad_id'] ?? ''))
            $errores[] = "Debe seleccionar una nacionalidad.";

        if (!self::correo($data['correo'] ?? ''))
            $errores[] = "El correo no tiene un formato válido.";

        if (!self::celular($data['celular'] ?? ''))
            $errores[] = "El celular no tiene un formato válido.";

        if (empty($data['temas']))
            $errores[] = "Debe seleccionar al menos un tema tecnológico.";

        return $errores;
    }
}
