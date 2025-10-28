<?php

class Ubigeo {
    private $conn;
    private $table_name = "ubigeos";

    public $id_ubigeo;
    public $departamento;
    public $provincia;
    public $distrito;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    function listar() {
        $query = "CALL sp_ubigeos_listar()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function obtener() {
        $query = "CALL sp_ubigeos_obtener(:id_ubigeo)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_ubigeo", $this->id_ubigeo);
        $stmt->execute();
        return $stmt;
    }

    function crear() {
        $query = "CALL sp_ubigeos_crear(:departamento, :provincia, :distrito)";
        $stmt = $this->conn->prepare($query);

        $this->departamento = htmlspecialchars(strip_tags($this->departamento));
        $this->provincia = htmlspecialchars(strip_tags($this->provincia));
        $this->distrito = htmlspecialchars(strip_tags($this->distrito));

        $stmt->bindParam(":departamento", $this->departamento);
        $stmt->bindParam(":provincia", $this->provincia);
        $stmt->bindParam(":distrito", $this->distrito);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function actualizar() {
        $query = "CALL sp_ubigeos_actualizar(:id_ubigeo, :departamento, :provincia, :distrito)";
        $stmt = $this->conn->prepare($query);

        $this->id_ubigeo = htmlspecialchars(strip_tags($this->id_ubigeo));
        $this->departamento = htmlspecialchars(strip_tags($this->departamento));
        $this->provincia = htmlspecialchars(strip_tags($this->provincia));
        $this->distrito = htmlspecialchars(strip_tags($this->distrito));

        $stmt->bindParam(":id_ubigeo", $this->id_ubigeo);
        $stmt->bindParam(":departamento", $this->departamento);
        $stmt->bindParam(":provincia", $this->provincia);
        $stmt->bindParam(":distrito", $this->distrito);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function eliminar() {
        $query = "CALL sp_ubigeos_eliminar(:id_ubigeo)";
        $stmt = $this->conn->prepare($query);
        $this->id_ubigeo = htmlspecialchars(strip_tags($this->id_ubigeo));
        $stmt->bindParam(":id_ubigeo", $this->id_ubigeo);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
