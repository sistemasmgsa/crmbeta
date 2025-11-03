<?php
class ReportesModel {
    private $conn;

    private $column_map = [
        'oportunidades' => [
            'ID Oportunidad' => 'o.id_oportunidad',
            'Nombre Oportunidad' => 'o.nombre_oportunidad',
            'Cliente' => 'c.nombre_cliente',
            'Valor Estimado' => 'o.valor_estimado',
            'Etapa' => 'o.etapa',
            'Fecha Cierre' => 'o.fecha_cierre',
            'Propietario' => 'u.nombre_usuario',
            'Fecha Creación' => 'o.fecha_creacion',
        ],
        'actividades' => [
            'ID Actividad' => 'a.id_actividad',
            'Asunto' => 'a.asunto',
            'Tipo' => 'a.tipo_actividad',
            'Fecha' => 'a.fecha_actividad',
            'Usuario' => 'u.nombre_usuario',
            'Cliente' => 'c.nombre_cliente',
            'Oportunidad' => 'o.nombre_oportunidad',
            'Descripción' => 'a.descripcion',
            'Fecha Creación' => 'a.fecha_creacion',
        ],
        'clientes' => [
            'ID Cliente' => 'c.id_cliente',
            'Nombre' => 'c.nombre_cliente',
            'Tipo Documento' => 'td.nombre_documento',
            'Num. Documento' => 'c.numero_documento',
            'Teléfono' => 'c.telefono_cliente',
            'Email' => 'c.correo_electronico',
            'Dirección' => 'c.direccion_cliente',
            'Departamento' => 'ub.departamento',
            'Provincia' => 'ub.provincia',
            'Distrito' => 'ub.distrito',
            'Estado' => 'c.estado',
            'Fecha Creación' => 'c.fecha_creacion',
        ]
    ];

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtener_columnas($reporte) {
        return array_keys($this->column_map[$reporte] ?? []);
    }

    public function generar_reporte($reporte, $columnas) {
        $sql_columns = [];
        foreach ($columnas as $user_col) {
            if (isset($this->column_map[$reporte][$user_col])) {
                $sql_columns[] = $this->column_map[$reporte][$user_col] . ' AS `' . $user_col . '`';
            }
        }

        if (empty($sql_columns)) {
            return [];
        }

        $columnas_seleccionadas = implode(', ', $sql_columns);
        $sql = "CALL sp_reporte_$reporte(:columnas)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':columnas', $columnas_seleccionadas);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $data;
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