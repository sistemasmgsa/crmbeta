<?php

class Cliente {
    private $conn;
    private $table_name = "clientes";

    public $id_cliente;
    public $nombre_cliente;
    public $direccion_cliente;
    public $telefono_cliente;
    public $website_cliente;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    function listar() {
        $query = "CALL sp_clientes_listar()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function obtener() {
        $query = "CALL sp_clientes_obtener(:id_cliente)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_cliente", $this->id_cliente);
        $stmt->execute();
        return $stmt;
    }

    function crear() {
        $query = "CALL sp_clientes_crear(:nombre, :direccion, :telefono, :website)";
        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre_cliente = htmlspecialchars(strip_tags($this->nombre_cliente));
        $this->direccion_cliente = htmlspecialchars(strip_tags($this->direccion_cliente));
        $this->telefono_cliente = htmlspecialchars(strip_tags($this->telefono_cliente));
        $this->website_cliente = htmlspecialchars(strip_tags($this->website_cliente));

        // Enlazar parámetros
        $stmt->bindParam(":nombre", $this->nombre_cliente);
        $stmt->bindParam(":direccion", $this->direccion_cliente);
        $stmt->bindParam(":telefono", $this->telefono_cliente);
        $stmt->bindParam(":website", $this->website_cliente);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function actualizar() {
        $query = "CALL sp_clientes_actualizar(:id, :nombre, :direccion, :telefono, :website)";
        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->id_cliente = htmlspecialchars(strip_tags($this->id_cliente));
        $this->nombre_cliente = htmlspecialchars(strip_tags($this->nombre_cliente));
        $this->direccion_cliente = htmlspecialchars(strip_tags($this->direccion_cliente));
        $this->telefono_cliente = htmlspecialchars(strip_tags($this->telefono_cliente));
        $this->website_cliente = htmlspecialchars(strip_tags($this->website_cliente));

        // Enlazar parámetros
        $stmt->bindParam(":id", $this->id_cliente);
        $stmt->bindParam(":nombre", $this->nombre_cliente);
        $stmt->bindParam(":direccion", $this->direccion_cliente);
        $stmt->bindParam(":telefono", $this->telefono_cliente);
        $stmt->bindParam(":website", $this->website_cliente);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function eliminar() {
        $query = "CALL sp_clientes_eliminar(:id_cliente)";
        $stmt = $this->conn->prepare($query);
        $this->id_cliente = htmlspecialchars(strip_tags($this->id_cliente));
        $stmt->bindParam(":id_cliente", $this->id_cliente);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
