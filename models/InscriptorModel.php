<?php
// models/InscriptorModel.php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../helpers/Integridad.php';

class InscriptorModel
{
    private $db;
    private $con;

    public function __construct()
    {
        $this->db = new mod_db();
        $this->con = $this->db->getConexion();
    }

    public function obtenerPaises(): array
    {
        return $this->db->Arreglos("SELECT id, nombre FROM paises ORDER BY nombre");
    }

    public function obtenerAreasInteres(): array
    {
        return $this->db->Arreglos("SELECT id, nombre FROM areas_interes ORDER BY nombre");
    }

    /**
     * Guarda el inscriptor + sus temas de interés (relación N:M) dentro de una transacción.
     */
    public function guardar(array $datos, array $temas): int|false
    {
        try {
            $this->con->beginTransaction();

            $firma = Integridad::firmar(
                $datos['nombre'] . ' ' . $datos['apellido'],
                $datos['identidad'],
                $datos['correo'],
                $datos['celular'],
                $datos['sexo']
            );
            $datos['firma_integridad'] = $firma;

            $idInscriptor = $this->db->insertSeguro('inscriptores', $datos);
            if (!$idInscriptor) throw new Exception("No se pudo insertar el inscriptor.");

            $stmt = $this->con->prepare(
                "INSERT INTO inscriptor_temas (inscriptor_id, area_interes_id) VALUES (:insc, :area)"
            );
            foreach ($temas as $areaId) {
                $stmt->execute(['insc' => $idInscriptor, 'area' => (int)$areaId]);
            }

            $this->con->commit();
            return $idInscriptor;
        } catch (Exception $e) {
            $this->con->rollBack();
            echo "Error al guardar: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Reporte: un registro por inscriptor, con los temas concatenados por comas
     * (usando GROUP_CONCAT en SQL, según requisito #18).
     */
    public function obtenerReporte(): array
    {
        $sql = "SELECT i.id, i.identidad, i.nombre, i.apellido, i.edad, i.sexo,
                       pr.nombre AS pais_residencia, na.nombre AS nacionalidad,
                       i.correo, i.celular, i.observaciones, i.firma_integridad,
                       i.fecha_registro,
                       GROUP_CONCAT(ai.nombre ORDER BY ai.nombre SEPARATOR ', ') AS temas
                FROM inscriptores i
                JOIN paises pr ON pr.id = i.pais_residencia_id
                JOIN paises na ON na.id = i.nacionalidad_id
                LEFT JOIN inscriptor_temas it ON it.inscriptor_id = i.id
                LEFT JOIN areas_interes ai ON ai.id = it.area_interes_id
                GROUP BY i.id
                ORDER BY i.fecha_registro DESC";
        return $this->db->Arreglos($sql);
    }
}
