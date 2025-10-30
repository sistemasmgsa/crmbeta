<?php

class Cliente {
    private $conn;
    private $table_name = "clientes";

    public $id_cliente;
    public $nombre_cliente;
    public $direccion_cliente;
    public $telefono_cliente;
    public $website_cliente;
    public $id_tipo_documento;
    public $numero_documento;
    public $id_ubigeo;
    public $correo_electronico;
    public $observaciones;
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

    public function listar_filtrado($nombre = null, $documento = null) {
        $query = "CALL sp_clientes_listar_filtrado(:nombre, :documento)";
        $stmt = $this->conn->prepare($query);
        
        // Para que si está vacío envíe NULL
        $stmt->bindValue(':nombre', !empty($nombre) ? $nombre : null);
        $stmt->bindValue(':documento', !empty($documento) ? $documento : null);
        
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
    $query = "CALL sp_clientes_crear(:nombre, :direccion, :telefono, :website, :id_tipo_documento, :numero_documento, :id_ubigeo, :correo_electronico, :observaciones)";
    $stmt = $this->conn->prepare($query);

    // Limpiar datos
    $this->nombre_cliente = htmlspecialchars(strip_tags($this->nombre_cliente));
    $this->direccion_cliente = htmlspecialchars(strip_tags($this->direccion_cliente));
    $this->telefono_cliente = htmlspecialchars(strip_tags($this->telefono_cliente));
    $this->website_cliente = htmlspecialchars(strip_tags($this->website_cliente));
    $this->id_tipo_documento = htmlspecialchars(strip_tags($this->id_tipo_documento));
    $this->numero_documento = htmlspecialchars(strip_tags($this->numero_documento));
    $this->id_ubigeo = htmlspecialchars(strip_tags($this->id_ubigeo));
    $this->correo_electronico = htmlspecialchars(strip_tags($this->correo_electronico));
    $this->observaciones = htmlspecialchars(strip_tags($this->observaciones));

    // Enlazar parámetros
    $stmt->bindParam(":nombre", $this->nombre_cliente);
    $stmt->bindParam(":direccion", $this->direccion_cliente);
    $stmt->bindParam(":telefono", $this->telefono_cliente);
    $stmt->bindParam(":website", $this->website_cliente);
    $stmt->bindParam(":id_tipo_documento", $this->id_tipo_documento);
    $stmt->bindParam(":numero_documento", $this->numero_documento);
    $stmt->bindParam(":id_ubigeo", $this->id_ubigeo);
    $stmt->bindParam(":correo_electronico", $this->correo_electronico);
    $stmt->bindParam(":observaciones", $this->observaciones);

    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    return $resultado; // Devuelve {resultado, mensaje}
}


function actualizar() {
    $query = "CALL sp_clientes_actualizar(:id, :nombre, :direccion, :telefono, :website, :id_tipo_documento, :numero_documento, :id_ubigeo, :correo_electronico, :observaciones)";
    $stmt = $this->conn->prepare($query);

    // Limpiar datos
    $this->id_cliente = htmlspecialchars(strip_tags($this->id_cliente));
    $this->nombre_cliente = htmlspecialchars(strip_tags($this->nombre_cliente));
    $this->direccion_cliente = htmlspecialchars(strip_tags($this->direccion_cliente));
    $this->telefono_cliente = htmlspecialchars(strip_tags($this->telefono_cliente));
    $this->website_cliente = htmlspecialchars(strip_tags($this->website_cliente));
    $this->id_tipo_documento = htmlspecialchars(strip_tags($this->id_tipo_documento));
    $this->numero_documento = htmlspecialchars(strip_tags($this->numero_documento));
    $this->id_ubigeo = htmlspecialchars(strip_tags($this->id_ubigeo));
    $this->correo_electronico = htmlspecialchars(strip_tags($this->correo_electronico));
    $this->observaciones = htmlspecialchars(strip_tags($this->observaciones));

    // Enlazar parámetros
    $stmt->bindParam(":id", $this->id_cliente);
    $stmt->bindParam(":nombre", $this->nombre_cliente);
    $stmt->bindParam(":direccion", $this->direccion_cliente);
    $stmt->bindParam(":telefono", $this->telefono_cliente);
    $stmt->bindParam(":website", $this->website_cliente);
    $stmt->bindParam(":id_tipo_documento", $this->id_tipo_documento);
    $stmt->bindParam(":numero_documento", $this->numero_documento);
    $stmt->bindParam(":id_ubigeo", $this->id_ubigeo);
    $stmt->bindParam(":correo_electronico", $this->correo_electronico);
    $stmt->bindParam(":observaciones", $this->observaciones);

    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    return $resultado;
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
