<?php

class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public $id_usuario;
    public $nombre_usuario;
    public $apellido_usuario;
    public $correo_usuario;
    public $clave_usuario;
    public $id_perfil;
    public $nombre_perfil;

    public function __construct($db) {
        $this->conn = $db;
    }

    function listar() {
        $query = "CALL sp_usuarios_listar()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function obtener() {
        $query = "CALL sp_usuarios_obtener(:id_usuario)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_usuario", $this->id_usuario);
        $stmt->execute();
        return $stmt;
    }

    function crear() {
        $query = "CALL sp_usuarios_crear(:nombre_usuario, :apellido_usuario, :correo_usuario, :clave_usuario, :id_perfil)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre_usuario", $this->nombre_usuario);
        $stmt->bindParam(":apellido_usuario", $this->apellido_usuario);
        $stmt->bindParam(":correo_usuario", $this->correo_usuario);
        $stmt->bindParam(":clave_usuario", $this->clave_usuario);
        $stmt->bindParam(":id_perfil", $this->id_perfil);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function actualizar() {
        $query = "CALL sp_usuarios_actualizar(:id_usuario, :nombre_usuario, :apellido_usuario, :correo_usuario, :clave_usuario, :id_perfil)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_usuario", $this->id_usuario);
        $stmt->bindParam(":nombre_usuario", $this->nombre_usuario);
        $stmt->bindParam(":apellido_usuario", $this->apellido_usuario);
        $stmt->bindParam(":correo_usuario", $this->correo_usuario);
        $stmt->bindParam(":clave_usuario", $this->clave_usuario);
        $stmt->bindParam(":id_perfil", $this->id_perfil);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function eliminar() {
        $query = "CALL sp_usuarios_eliminar(:id_usuario)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_usuario", $this->id_usuario);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function login() {
        $query = "CALL sp_usuarios_login(:correo_usuario)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":correo_usuario", $this->correo_usuario);
        $stmt->execute();
        return $stmt;
    }
}
