# Parcial Práctico I - iTECH (Inscripciones)

## Estructura (MVC)

```
parcial_itech/
├── config/conexion.php        # Clase mod_db (conexión PDO + operaciones)
├── models/InscriptorModel.php # Consultas y guardado (Modelo)
├── helpers/Validator.php      # Validación (métodos estáticos)
├── helpers/Sanitizer.php      # Sanitización / Data Cleaning (métodos estáticos)
├── helpers/Integridad.php     # Firma HMAC-SHA256 (OpenSSL) para auditoría
├── public/index.php           # Formulario (Vista)
├── public/guardar.php         # Guardar inscripción (Controlador)
├── public/reporte.php         # Reporte con semáforo de integridad
├── public/exportar_excel.php  # Exportación a Excel (PhpSpreadsheet)
├── public/estilo.css
├── database.sql               # Script con tablas y llaves foráneas
└── composer.json
```

## Pasos para correr el proyecto

1. Copia la carpeta `parcial_itech` a tu `www` de WAMP.
2. Importa `database.sql` en http://127.1.1.1/phpmyadmin/ (crea la BD `parcial_itech` con todas las tablas y FKs).
3. Ajusta usuario/clave en `config/conexion.php` si no usas `root` sin contraseña.
4. Desde la carpeta del proyecto: `composer require phpoffice/phpspreadsheet`
   (esto crea `vendor/` y `vendor/autoload.php`, que ya está referenciado en `exportar_excel.php`).
5. Abre `http://localhost/parcial_itech/public/index.php`

## Qué cubre cada parte de la rúbrica

- **I. Campos del formulario (15 pts):** todos los campos pedidos en `index.php`, con `select` para país/nacionalidad (cargados desde BD).
- **Contacto + restricciones BD (10 pts):** `correo` y `celular` son `UNIQUE` en la tabla `inscriptores` (database.sql).
- **Checkbox de temas (2 pts) + Observaciones (2 pts) + Footer con año (4 pts):** en `index.php`.
- **CSS (10 pts):** `estilo.css`, diseño con colores institucionales, no en blanco y negro.
- **BD (14-17, 25 pts):** `database.sql` crea `paises`, `areas_interes`, `inscriptores`, `inscriptor_temas` con `FOREIGN KEY ... ON DELETE RESTRICT ON UPDATE CASCADE`.
- **Reporte con temas por comas (5 pts):** `InscriptorModel::obtenerReporte()` usa `GROUP_CONCAT`.
- **Exportar a Excel (5 pts):** `exportar_excel.php`, mismo patrón de tu `EjemploExcelIntegrado.php`.
- **Auditoría de integridad con OpenSSL (5 pts):** `helpers/Integridad.php` firma cada registro (nombre, identidad, correo, celular, sexo) con `hash_hmac('sha256', ...)` al guardarlo, y el reporte/Excel comparan la firma para pintar verde (íntegro) o rojo (alterado).
- **Validación y Sanitización con clases estáticas (10 pts):** `Validator.php`, `Sanitizer.php`.
- **Nombre/Apellido en formato Título (4 pts):** `Sanitizer::tipoTitulo()`.
- **MVC (3 pts):** carpetas `config/`, `models/`, `helpers/`, `public/` separadas por responsabilidad.

## Nota sobre la "firma" de integridad

Se usa **HMAC-SHA256** (función `hash_hmac`, parte de la extensión OpenSSL/hash de PHP) en vez de una firma
de clave pública/privada, porque el requisito es detectar si alguien alteró los datos directamente en la
BD (integridad), no garantizar autoría entre dos partes distintas. Si prefieres usar `openssl_sign` /
`openssl_verify` con un par de llaves RSA en vez de HMAC, dímelo y te ajusto `Integridad.php`.
