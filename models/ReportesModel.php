<?php
class ReportesModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtener_columnas($reporte) {
        $columnas = [];
        $query = "";
        if ($reporte === 'oportunidades') {
            $query = "DESCRIBE oportunidades";
        } elseif ($reporte === 'actividades') {
            $query = "DESCRIBE actividades";
        } elseif ($reporte === 'clientes') {
            $query = "DESCRIBE clientes";
        }

        if ($query) {
            $stmt = $this->conn->query($query);
            if ($stmt) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $columnas[] = $row['Field'];
                }
                $stmt->closeCursor();
            }
        }
        return $columnas;
    }

    public function generar_reporte($reporte, $columnas) {
        $columnas_seleccionadas = implode(',', $columnas);
        $sql = "CALL sp_reporte_$reporte(:columnas)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':columnas', $columnas_seleccionadas);
        $stmt->execute();
        if ($stmt) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return $data;
        }
        return [];
    }

    public function guardar_plantilla($nombre, $reporte, $columnas) {
        $columnas_json = json_encode($columnas);
        $stmt = $this->conn->prepare("INSERT INTO reportes_plantillas (nombre_plantilla, tipo_reporte, columnas) VALUES (:nombre, :reporte, :columnas)");
        $result = $stmt->execute(['nombre' => $nombre, 'reporte' => $reporte, 'columnas' => $columnas_json]);
        $stmt->closeCursor();
        return $result;
    }

    public function listar_plantillas($reporte) {
        $stmt = $this->conn->prepare("SELECT id_plantilla, nombre_plantilla FROM reportes_plantillas WHERE tipo_reporte = :reporte");
        $stmt->execute(['reporte' => $reporte]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $data;
    }

    public function cargar_plantilla($id_plantilla) {
        $stmt = $this->conn->prepare("SELECT columnas FROM reportes_plantillas WHERE id_plantilla = :id_plantilla");
        $stmt->execute(['id_plantilla' => $id_plantilla]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $resultado ? json_decode($resultado['columnas'], true) : [];
    }
}
?>