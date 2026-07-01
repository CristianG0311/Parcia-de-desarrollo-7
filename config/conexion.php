<?php
// config/conexion.php
// Clase de conexión y operaciones de base de datos (basada en tu mod_db original)

class mod_db
{
    private $conexion;
    private $debug = false;

    public function __construct()
    {
        $sql_host = "localhost";
        $sql_name = "parcial_itech";
        $sql_user = "root";
        $sql_pass = "";

        $dsn = "mysql:host=$sql_host;dbname=$sql_name;charset=utf8mb4";
        try {
            $this->conexion = new PDO($dsn, $sql_user, $sql_pass);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if ($this->debug) {
                echo "Conexión exitosa a la base de datos<br>";
            }
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function getConexion()
    {
        return $this->conexion;
    }

    public function insertSeguro($tb_name, $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $tb_name ($columns) VALUES ($placeholders)";

        try {
            $stmt = $this->conexion->prepare($sql);
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->execute();
            return $this->conexion->lastInsertId();
        } catch (PDOException $e) {
            echo "Error en INSERT: " . $e->getMessage();
            return false;
        }
    }

    public function Arreglos($string = "")
    {
        $stmt = "";
        try {
            if ($string) {
                $stmt = $this->conexion->query($string);
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    public function objects($string = "")
    {
        try {
            $stmt = $this->conexion->query($string);
            return $stmt ? $stmt->fetchAll(PDO::FETCH_OBJ) : [];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
}
