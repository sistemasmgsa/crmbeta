<?php

class Actividad {
    private $conn;
    private $table_name = "actividades";

    public $id_actividad;
    public $id_cliente;
    public $id_contacto;
    public $id_oportunidad;
    public $id_usuario;
    public $tipo_actividad;
    public $asunto;
    public $descripcion;
    public $fecha_actividad;

    public function __construct($db) {
        $this->conn = $db;
    }

    function listarPorCliente() {
        $query = "CALL sp_actividades_listar_por_cliente(:id_cliente)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_cliente", $this->id_cliente);
        $stmt->execute();
        return $stmt;
    }

    function crear() {
        $query = "CALL sp_actividades_crear(:id_cliente, :id_contacto, :id_oportunidad, :id_usuario, :tipo, :asunto, :descripcion, :fecha)";
        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->id_cliente = htmlspecialchars(strip_tags($this->id_cliente));
        $this->id_contacto = htmlspecialchars(strip_tags($this->id_contacto));
        $this->id_oportunidad = htmlspecialchars(strip_tags($this->id_oportunidad));
        $this->id_usuario = htmlspecialchars(strip_tags($this->id_usuario));
        $this->tipo_actividad = htmlspecialchars(strip_tags($this->tipo_actividad));
        $this->asunto = htmlspecialchars(strip_tags($this->asunto));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->fecha_actividad = htmlspecialchars(strip_tags($this->fecha_actividad));

        // Enlazar parámetros
        $stmt->bindParam(":id_cliente", $this->id_cliente);
        $stmt->bindParam(":id_contacto", $this->id_contacto);
        $stmt->bindParam(":id_oportunidad", $this->id_oportunidad);
        $stmt->bindParam(":id_usuario", $this->id_usuario);
        $stmt->bindParam(":tipo", $this->tipo_actividad);
        $stmt->bindParam(":asunto", $this->asunto);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":fecha", $this->fecha_actividad);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
