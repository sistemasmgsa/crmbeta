<?php

class Contacto {
    private $conn;
    private $table_name = "contactos";

    public $id_contacto;
    public $id_cliente;
    public $nombre_contacto;
    public $cargo_contacto;
    public $correo_contacto;
    public $telefono_contacto;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    function listarPorCliente() {
        $query = "CALL sp_contactos_listar_por_cliente(:id_cliente)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_cliente", $this->id_cliente);
        $stmt->execute();
        return $stmt;
    }

    function obtener() {
        $query = "CALL sp_contactos_obtener(:id_contacto)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_contacto", $this->id_contacto);
        $stmt->execute();
        return $stmt;
    }

    function crear() {
        $query = "CALL sp_contactos_crear(:id_cliente, :nombre, :cargo, :correo, :telefono)";
        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->id_cliente = htmlspecialchars(strip_tags($this->id_cliente));
        $this->nombre_contacto = htmlspecialchars(strip_tags($this->nombre_contacto));
        $this->cargo_contacto = htmlspecialchars(strip_tags($this->cargo_contacto));
        $this->correo_contacto = htmlspecialchars(strip_tags($this->correo_contacto));
        $this->telefono_contacto = htmlspecialchars(strip_tags($this->telefono_contacto));

        // Enlazar parámetros
        $stmt->bindParam(":id_cliente", $this->id_cliente);
        $stmt->bindParam(":nombre", $this->nombre_contacto);
        $stmt->bindParam(":cargo", $this->cargo_contacto);
        $stmt->bindParam(":correo", $this->correo_contacto);
        $stmt->bindParam(":telefono", $this->telefono_contacto);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function actualizar() {
        $query = "CALL sp_contactos_actualizar(:id_contacto, :nombre, :cargo, :correo, :telefono)";
        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->id_contacto = htmlspecialchars(strip_tags($this->id_contacto));
        $this->nombre_contacto = htmlspecialchars(strip_tags($this->nombre_contacto));
        $this->cargo_contacto = htmlspecialchars(strip_tags($this->cargo_contacto));
        $this->correo_contacto = htmlspecialchars(strip_tags($this->correo_contacto));
        $this->telefono_contacto = htmlspecialchars(strip_tags($this->telefono_contacto));

        // Enlazar parámetros
        $stmt->bindParam(":id_contacto", $this->id_contacto);
        $stmt->bindParam(":nombre", $this->nombre_contacto);
        $stmt->bindParam(":cargo", $this->cargo_contacto);
        $stmt->bindParam(":correo", $this->correo_contacto);
        $stmt->bindParam(":telefono", $this->telefono_contacto);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function eliminar() {
        $query = "CALL sp_contactos_eliminar(:id_contacto)";
        $stmt = $this->conn->prepare($query);
        $this->id_contacto = htmlspecialchars(strip_tags($this->id_contacto));
        $stmt->bindParam(":id_contacto", $this->id_contacto);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
