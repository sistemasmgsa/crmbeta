-- Tipos de Documento de Identidad
CREATE TABLE tipos_documento_identidad (
    id_tipo_documento INT AUTO_INCREMENT PRIMARY KEY,
    nombre_documento VARCHAR(50) NOT NULL,
    estado INT DEFAULT 1
);

-- Ubigeos
CREATE TABLE ubigeos (
    id_ubigeo INT AUTO_INCREMENT PRIMARY KEY,
    departamento VARCHAR(100) NOT NULL,
    provincia VARCHAR(100) NOT NULL,
    distrito VARCHAR(100) NOT NULL,
    estado INT DEFAULT 1
);

-- Modificación de la tabla Clientes
ALTER TABLE clientes
ADD COLUMN id_tipo_documento INT,
ADD COLUMN numero_documento VARCHAR(20),
ADD COLUMN id_ubigeo INT,
ADD COLUMN correo_electronico VARCHAR(100),
ADD COLUMN observaciones TEXT,
ADD FOREIGN KEY (id_tipo_documento) REFERENCES tipos_documento_identidad(id_tipo_documento),
ADD FOREIGN KEY (id_ubigeo) REFERENCES ubigeos(id_ubigeo);

-- Stored Procedures para Tipos de Documento de Identidad
DELIMITER //

CREATE PROCEDURE sp_tipos_documento_listar()
BEGIN
    SELECT * FROM tipos_documento_identidad WHERE estado = 1;
END //

CREATE PROCEDURE sp_tipos_documento_obtener(IN p_id_tipo_documento INT)
BEGIN
    SELECT * FROM tipos_documento_identidad WHERE id_tipo_documento = p_id_tipo_documento;
END //

CREATE PROCEDURE sp_tipos_documento_crear(IN p_nombre_documento VARCHAR(50))
BEGIN
    INSERT INTO tipos_documento_identidad (nombre_documento) VALUES (p_nombre_documento);
END //

CREATE PROCEDURE sp_tipos_documento_actualizar(IN p_id_tipo_documento INT, IN p_nombre_documento VARCHAR(50))
BEGIN
    UPDATE tipos_documento_identidad SET nombre_documento = p_nombre_documento WHERE id_tipo_documento = p_id_tipo_documento;
END //

CREATE PROCEDURE sp_tipos_documento_eliminar(IN p_id_tipo_documento INT)
BEGIN
    UPDATE tipos_documento_identidad SET estado = 0 WHERE id_tipo_documento = p_id_tipo_documento;
END //

-- Stored Procedures para Ubigeos

CREATE PROCEDURE sp_ubigeos_listar()
BEGIN
    SELECT * FROM ubigeos WHERE estado = 1;
END //

CREATE PROCEDURE sp_ubigeos_obtener(IN p_id_ubigeo INT)
BEGIN
    SELECT * FROM ubigeos WHERE id_ubigeo = p_id_ubigeo;
END //

CREATE PROCEDURE sp_ubigeos_crear(IN p_departamento VARCHAR(100), IN p_provincia VARCHAR(100), IN p_distrito VARCHAR(100))
BEGIN
    INSERT INTO ubigeos (departamento, provincia, distrito) VALUES (p_departamento, p_provincia, p_distrito);
END //

CREATE PROCEDURE sp_ubigeos_actualizar(IN p_id_ubigeo INT, IN p_departamento VARCHAR(100), IN p_provincia VARCHAR(100), IN p_distrito VARCHAR(100))
BEGIN
    UPDATE ubigeos SET departamento = p_departamento, provincia = p_provincia, distrito = p_distrito WHERE id_ubigeo = p_id_ubigeo;
END //

CREATE PROCEDURE sp_ubigeos_eliminar(IN p_id_ubigeo INT)
BEGIN
    UPDATE ubigeos SET estado = 0 WHERE id_ubigeo = p_id_ubigeo;
END //

-- Stored Procedures para Clientes (actualizados)

DROP PROCEDURE IF EXISTS sp_clientes_crear;
CREATE PROCEDURE sp_clientes_crear(
    IN p_nombre_cliente VARCHAR(255),
    IN p_direccion_cliente VARCHAR(255),
    IN p_telefono_cliente VARCHAR(20),
    IN p_website_cliente VARCHAR(100),
    IN p_id_tipo_documento INT,
    IN p_numero_documento VARCHAR(20),
    IN p_id_ubigeo INT,
    IN p_correo_electronico VARCHAR(100),
    IN p_observaciones TEXT
)
BEGIN
    INSERT INTO clientes (nombre_cliente, direccion_cliente, telefono_cliente, website_cliente, id_tipo_documento, numero_documento, id_ubigeo, correo_electronico, observaciones)
    VALUES (p_nombre_cliente, p_direccion_cliente, p_telefono_cliente, p_website_cliente, p_id_tipo_documento, p_numero_documento, p_id_ubigeo, p_correo_electronico, p_observaciones);
END //

DROP PROCEDURE IF EXISTS sp_clientes_actualizar;
CREATE PROCEDURE sp_clientes_actualizar(
    IN p_id_cliente INT,
    IN p_nombre_cliente VARCHAR(255),
    IN p_direccion_cliente VARCHAR(255),
    IN p_telefono_cliente VARCHAR(20),
    IN p_website_cliente VARCHAR(100),
    IN p_id_tipo_documento INT,
    IN p_numero_documento VARCHAR(20),
    IN p_id_ubigeo INT,
    IN p_correo_electronico VARCHAR(100),
    IN p_observaciones TEXT
)
BEGIN
    UPDATE clientes
    SET nombre_cliente = p_nombre_cliente,
        direccion_cliente = p_direccion_cliente,
        telefono_cliente = p_telefono_cliente,
        website_cliente = p_website_cliente,
        id_tipo_documento = p_id_tipo_documento,
        numero_documento = p_numero_documento,
        id_ubigeo = p_id_ubigeo,
        correo_electronico = p_correo_electronico,
        observaciones = p_observaciones
    WHERE id_cliente = p_id_cliente;
END //

DELIMITER ;

-- Datos iniciales
INSERT INTO tipos_documento_identidad (nombre_documento) VALUES ('DNI'), ('RUC');
