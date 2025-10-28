<?php

class Perfil {
    private $conn;
    private $table_name = "perfiles";

    public $id_perfil;
    public $nombre_perfil;

    public function __construct($db) {
        $this->conn = $db;
    }

    function listar() {
        $query = "CALL sp_perfiles_listar()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function obtener() {
        $query = "CALL sp_perfiles_obtener(:id_perfil)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_perfil", $this->id_perfil);
        $stmt->execute();
        return $stmt;
    }

    function crear() {
        $query = "CALL sp_perfiles_crear(:nombre_perfil)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre_perfil", $this->nombre_perfil);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function actualizar() {
        $query = "CALL sp_perfiles_actualizar(:id_perfil, :nombre_perfil)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_perfil", $this->id_perfil);
        $stmt->bindParam(":nombre_perfil", $this->nombre_perfil);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function eliminar() {
        $query = "CALL sp_perfiles_eliminar(:id_perfil)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_perfil", $this->id_perfil);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
