<?php

class Oportunidad {
    private $conn;
    private $table_name = "oportunidades";

    public $id_oportunidad;
    public $id_cliente;
    public $nombre_oportunidad;
    public $valor_estimado;
    public $fecha_cierre;
    public $etapa;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    function listar() {
        $query = "CALL sp_oportunidades_listar()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function obtener() {
        $query = "CALL sp_oportunidades_obtener(:id_oportunidad)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_oportunidad", $this->id_oportunidad);
        $stmt->execute();
        return $stmt;
    }

    function crear() {
        $query = "CALL sp_oportunidades_crear(:id_cliente, :nombre, :valor, :fecha, :etapa)";
        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->id_cliente = htmlspecialchars(strip_tags($this->id_cliente));
        $this->nombre_oportunidad = htmlspecialchars(strip_tags($this->nombre_oportunidad));
        $this->valor_estimado = htmlspecialchars(strip_tags($this->valor_estimado));
        $this->fecha_cierre = htmlspecialchars(strip_tags($this->fecha_cierre));
        $this->etapa = htmlspecialchars(strip_tags($this->etapa));

        // Enlazar parámetros
        $stmt->bindParam(":id_cliente", $this->id_cliente);
        $stmt->bindParam(":nombre", $this->nombre_oportunidad);
        $stmt->bindParam(":valor", $this->valor_estimado);
        $stmt->bindParam(":fecha", $this->fecha_cierre);
        $stmt->bindParam(":etapa", $this->etapa);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function actualizar() {
        $query = "CALL sp_oportunidades_actualizar(:id, :id_cliente, :nombre, :valor, :fecha, :etapa)";
        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->id_oportunidad = htmlspecialchars(strip_tags($this->id_oportunidad));
        $this->id_cliente = htmlspecialchars(strip_tags($this->id_cliente));
        $this->nombre_oportunidad = htmlspecialchars(strip_tags($this->nombre_oportunidad));
        $this->valor_estimado = htmlspecialchars(strip_tags($this->valor_estimado));
        $this->fecha_cierre = htmlspecialchars(strip_tags($this->fecha_cierre));
        $this->etapa = htmlspecialchars(strip_tags($this->etapa));

        // Enlazar parámetros
        $stmt->bindParam(":id", $this->id_oportunidad);
        $stmt->bindParam(":id_cliente", $this->id_cliente);
        $stmt->bindParam(":nombre", $this->nombre_oportunidad);
        $stmt->bindParam(":valor", $this->valor_estimado);
        $stmt->bindParam(":fecha", $this->fecha_cierre);
        $stmt->bindParam(":etapa", $this->etapa);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function eliminar() {
        $query = "CALL sp_oportunidades_eliminar(:id_oportunidad)";
        $stmt = $this->conn->prepare($query);
        $this->id_oportunidad = htmlspecialchars(strip_tags($this->id_oportunidad));
        $stmt->bindParam(":id_oportunidad", $this->id_oportunidad);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function actualizarEtapa() {
        $query = "CALL sp_oportunidades_actualizar_etapa(:id_oportunidad, :etapa)";
        $stmt = $this->conn->prepare($query);
        $this->id_oportunidad = htmlspecialchars(strip_tags($this->id_oportunidad));
        $this->etapa = htmlspecialchars(strip_tags($this->etapa));
        $stmt->bindParam(":id_oportunidad", $this->id_oportunidad);
        $stmt->bindParam(":etapa", $this->etapa);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
