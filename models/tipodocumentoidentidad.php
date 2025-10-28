<?php

class TipoDocumentoIdentidad {
    private $conn;
    private $table_name = "tipos_documento_identidad";

    public $id_tipo_documento;
    public $nombre_documento;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    function listar() {
        $query = "CALL sp_tipos_documento_listar()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function obtener() {
        $query = "CALL sp_tipos_documento_obtener(:id_tipo_documento)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_tipo_documento", $this->id_tipo_documento);
        $stmt->execute();
        return $stmt;
    }

    function crear() {
        $query = "CALL sp_tipos_documento_crear(:nombre_documento)";
        $stmt = $this->conn->prepare($query);

        $this->nombre_documento = htmlspecialchars(strip_tags($this->nombre_documento));

        $stmt->bindParam(":nombre_documento", $this->nombre_documento);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function actualizar() {
        $query = "CALL sp_tipos_documento_actualizar(:id_tipo_documento, :nombre_documento)";
        $stmt = $this->conn->prepare($query);

        $this->id_tipo_documento = htmlspecialchars(strip_tags($this->id_tipo_documento));
        $this->nombre_documento = htmlspecialchars(strip_tags($this->nombre_documento));

        $stmt->bindParam(":id_tipo_documento", $this->id_tipo_documento);
        $stmt->bindParam(":nombre_documento", $this->nombre_documento);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function eliminar() {
        $query = "CALL sp_tipos_documento_eliminar(:id_tipo_documento)";
        $stmt = $this->conn->prepare($query);
        $this->id_tipo_documento = htmlspecialchars(strip_tags($this->id_tipo_documento));
        $stmt->bindParam(":id_tipo_documento", $this->id_tipo_documento);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
