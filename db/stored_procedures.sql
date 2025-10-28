-- Procedimientos almacenados para la tabla `perfiles`

DELIMITER $$
CREATE PROCEDURE `sp_perfiles_listar`()
BEGIN
	SELECT * FROM perfiles;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_perfiles_obtener`(IN `in_id_perfil` INT)
BEGIN
	SELECT * FROM perfiles WHERE id_perfil = in_id_perfil;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_perfiles_crear`(IN `in_nombre_perfil` VARCHAR(50))
BEGIN
	INSERT INTO perfiles (nombre_perfil) VALUES (in_nombre_perfil);
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_perfiles_actualizar`(IN `in_id_perfil` INT, IN `in_nombre_perfil` VARCHAR(50))
BEGIN
	UPDATE perfiles SET nombre_perfil = in_nombre_perfil WHERE id_perfil = in_id_perfil;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_perfiles_eliminar`(IN `in_id_perfil` INT)
BEGIN
	DELETE FROM perfiles WHERE id_perfil = in_id_perfil;
END$$
DELIMITER ;

-- Procedimientos almacenados para la tabla `usuarios`

DELIMITER $$
CREATE PROCEDURE `sp_usuarios_listar`()
BEGIN
	SELECT u.*, p.nombre_perfil FROM usuarios u INNER JOIN perfiles p ON u.id_perfil = p.id_perfil;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_usuarios_obtener`(IN `in_id_usuario` INT)
BEGIN
	SELECT * FROM usuarios WHERE id_usuario = in_id_usuario;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_usuarios_crear`(IN `in_nombre_usuario` VARCHAR(50), IN `in_apellido_usuario` VARCHAR(50), IN `in_correo_usuario` VARCHAR(100), IN `in_clave_usuario` VARCHAR(255), IN `in_id_perfil` INT)
BEGIN
	INSERT INTO usuarios (nombre_usuario, apellido_usuario, correo_usuario, clave_usuario, id_perfil) VALUES (in_nombre_usuario, in_apellido_usuario, in_correo_usuario, in_clave_usuario, in_id_perfil);
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_usuarios_actualizar`(IN `in_id_usuario` INT, IN `in_nombre_usuario` VARCHAR(50), IN `in_apellido_usuario` VARCHAR(50), IN `in_correo_usuario` VARCHAR(100), IN `in_clave_usuario` VARCHAR(255), IN `in_id_perfil` INT)
BEGIN
	UPDATE usuarios SET
		nombre_usuario = in_nombre_usuario,
		apellido_usuario = in_apellido_usuario,
		correo_usuario = in_correo_usuario,
		clave_usuario = in_clave_usuario,
		id_perfil = in_id_perfil
	WHERE id_usuario = in_id_usuario;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_usuarios_eliminar`(IN `in_id_usuario` INT)
BEGIN
	DELETE FROM usuarios WHERE id_usuario = in_id_usuario;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_usuarios_login`(IN `in_correo_usuario` VARCHAR(100))
BEGIN
	SELECT * FROM usuarios WHERE correo_usuario = in_correo_usuario;
END$$
DELIMITER ;

-- Procedimientos almacenados para la tabla `clientes`

DELIMITER $$
CREATE PROCEDURE `sp_clientes_listar`()
BEGIN
	SELECT * FROM clientes WHERE estado = 1 ORDER BY nombre_cliente ASC;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_clientes_obtener`(IN `in_id_cliente` INT)
BEGIN
	SELECT * FROM clientes WHERE id_cliente = in_id_cliente;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_clientes_crear`(
    IN `in_nombre_cliente` VARCHAR(100),
    IN `in_direccion_cliente` VARCHAR(255),
    IN `in_telefono_cliente` VARCHAR(20),
    IN `in_website_cliente` VARCHAR(100)
)
BEGIN
	INSERT INTO clientes (nombre_cliente, direccion_cliente, telefono_cliente, website_cliente)
    VALUES (in_nombre_cliente, in_direccion_cliente, in_telefono_cliente, in_website_cliente);
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_clientes_actualizar`(
    IN `in_id_cliente` INT,
    IN `in_nombre_cliente` VARCHAR(100),
    IN `in_direccion_cliente` VARCHAR(255),
    IN `in_telefono_cliente` VARCHAR(20),
    IN `in_website_cliente` VARCHAR(100)
)
BEGIN
	UPDATE clientes SET
		nombre_cliente = in_nombre_cliente,
		direccion_cliente = in_direccion_cliente,
		telefono_cliente = in_telefono_cliente,
		website_cliente = in_website_cliente
	WHERE id_cliente = in_id_cliente;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_clientes_eliminar`(IN `in_id_cliente` INT)
BEGIN
	UPDATE clientes SET estado = 0 WHERE id_cliente = in_id_cliente;
END$$
DELIMITER ;

-- Procedimientos almacenados para la tabla `contactos`

DELIMITER $$
CREATE PROCEDURE `sp_contactos_listar_por_cliente`(IN `in_id_cliente` INT)
BEGIN
	SELECT * FROM contactos WHERE id_cliente = in_id_cliente AND estado = 1 ORDER BY nombre_contacto ASC;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_contactos_obtener`(IN `in_id_contacto` INT)
BEGIN
	SELECT * FROM contactos WHERE id_contacto = in_id_contacto;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_contactos_crear`(
    IN `in_id_cliente` INT,
    IN `in_nombre_contacto` VARCHAR(100),
    IN `in_cargo_contacto` VARCHAR(100),
    IN `in_correo_contacto` VARCHAR(100),
    IN `in_telefono_contacto` VARCHAR(20)
)
BEGIN
	INSERT INTO contactos (id_cliente, nombre_contacto, cargo_contacto, correo_contacto, telefono_contacto)
    VALUES (in_id_cliente, in_nombre_contacto, in_cargo_contacto, in_correo_contacto, in_telefono_contacto);
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_contactos_actualizar`(
    IN `in_id_contacto` INT,
    IN `in_nombre_contacto` VARCHAR(100),
    IN `in_cargo_contacto` VARCHAR(100),
    IN `in_correo_contacto` VARCHAR(100),
    IN `in_telefono_contacto` VARCHAR(20)
)
BEGIN
	UPDATE contactos SET
		nombre_contacto = in_nombre_contacto,
		cargo_contacto = in_cargo_contacto,
		correo_contacto = in_correo_contacto,
		telefono_contacto = in_telefono_contacto
	WHERE id_contacto = in_id_contacto;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_contactos_eliminar`(IN `in_id_contacto` INT)
BEGIN
	UPDATE contactos SET estado = 0 WHERE id_contacto = in_id_contacto;
END$$
DELIMITER ;

-- Procedimientos almacenados para la tabla `oportunidades`

DELIMITER $$
CREATE PROCEDURE `sp_oportunidades_listar`()
BEGIN
	SELECT o.*, c.nombre_cliente FROM oportunidades o
    INNER JOIN clientes c ON o.id_cliente = c.id_cliente
    WHERE o.estado = 1 ORDER BY o.fecha_creacion DESC;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_oportunidades_obtener`(IN `in_id_oportunidad` INT)
BEGIN
	SELECT * FROM oportunidades WHERE id_oportunidad = in_id_oportunidad;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_oportunidades_crear`(
    IN `in_id_cliente` INT,
    IN `in_nombre_oportunidad` VARCHAR(100),
    IN `in_valor_estimado` DECIMAL(10,2),
    IN `in_fecha_cierre` DATE,
    IN `in_etapa` VARCHAR(20)
)
BEGIN
	INSERT INTO oportunidades (id_cliente, nombre_oportunidad, valor_estimado, fecha_cierre, etapa)
    VALUES (in_id_cliente, in_nombre_oportunidad, in_valor_estimado, in_fecha_cierre, in_etapa);
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_oportunidades_actualizar`(
    IN `in_id_oportunidad` INT,
    IN `in_id_cliente` INT,
    IN `in_nombre_oportunidad` VARCHAR(100),
    IN `in_valor_estimado` DECIMAL(10,2),
    IN `in_fecha_cierre` DATE,
    IN `in_etapa` VARCHAR(20)
)
BEGIN
	UPDATE oportunidades SET
		id_cliente = in_id_cliente,
		nombre_oportunidad = in_nombre_oportunidad,
		valor_estimado = in_valor_estimado,
		fecha_cierre = in_fecha_cierre,
		etapa = in_etapa
	WHERE id_oportunidad = in_id_oportunidad;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_oportunidades_eliminar`(IN `in_id_oportunidad` INT)
BEGIN
	UPDATE oportunidades SET estado = 0 WHERE id_oportunidad = in_id_oportunidad;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_oportunidades_actualizar_etapa`(
    IN `in_id_oportunidad` INT,
    IN `in_etapa` VARCHAR(20)
)
BEGIN
	UPDATE oportunidades SET etapa = in_etapa WHERE id_oportunidad = in_id_oportunidad;
END$$
DELIMITER ;

-- Procedimientos almacenados para la tabla `actividades`

DELIMITER $$
CREATE PROCEDURE `sp_actividades_listar_por_cliente`(IN `in_id_cliente` INT)
BEGIN
	SELECT a.*, u.nombre_usuario FROM actividades a
    INNER JOIN usuarios u ON a.id_usuario = u.id_usuario
    WHERE a.id_cliente = in_id_cliente
    ORDER BY a.fecha_actividad DESC;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_actividades_crear`(
    IN `in_id_cliente` INT,
    IN `in_id_contacto` INT,
    IN `in_id_oportunidad` INT,
    IN `in_id_usuario` INT,
    IN `in_tipo_actividad` VARCHAR(20),
    IN `in_asunto` VARCHAR(255),
    IN `in_descripcion` TEXT,
    IN `in_fecha_actividad` DATETIME
)
BEGIN
	INSERT INTO actividades (id_cliente, id_contacto, id_oportunidad, id_usuario, tipo_actividad, asunto, descripcion, fecha_actividad)
    VALUES (in_id_cliente, in_id_contacto, in_id_oportunidad, in_id_usuario, in_tipo_actividad, in_asunto, in_descripcion, in_fecha_actividad);
END$$
DELIMITER ;

-- Procedimiento almacenado para estadísticas del dashboard

DELIMITER $$
CREATE PROCEDURE `sp_dashboard_estadisticas`()
BEGIN
    SELECT
        (SELECT COUNT(*) FROM clientes WHERE estado = 1) AS total_clientes,
        (SELECT COUNT(*) FROM oportunidades WHERE estado = 1 AND etapa NOT IN ('Ganada', 'Perdida')) AS oportunidades_activas,
        (SELECT COUNT(*) FROM actividades WHERE fecha_actividad >= CURDATE()) AS actividades_pendientes;
END$$
DELIMITER ;
