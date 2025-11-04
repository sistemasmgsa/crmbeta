




















SET NAMES 'utf8';




USE crmbeta;




DROP PROCEDURE IF EXISTS sp_actividades_crear;




DROP PROCEDURE IF EXISTS sp_actividades_listar_por_cliente;




DROP PROCEDURE IF EXISTS sp_dashboard_estadisticas;




DROP TABLE IF EXISTS actividades;




DROP PROCEDURE IF EXISTS sp_usuarios_actualizar;




DROP PROCEDURE IF EXISTS sp_usuarios_crear;




DROP PROCEDURE IF EXISTS sp_usuarios_eliminar;




DROP PROCEDURE IF EXISTS sp_usuarios_listar;




DROP PROCEDURE IF EXISTS sp_usuarios_login;




DROP PROCEDURE IF EXISTS sp_usuarios_obtener;




DROP TABLE IF EXISTS usuarios;




DROP PROCEDURE IF EXISTS sp_perfiles_actualizar;




DROP PROCEDURE IF EXISTS sp_perfiles_crear;




DROP PROCEDURE IF EXISTS sp_perfiles_eliminar;




DROP PROCEDURE IF EXISTS sp_perfiles_listar;




DROP PROCEDURE IF EXISTS sp_perfiles_obtener;




DROP TABLE IF EXISTS perfiles;




DROP PROCEDURE IF EXISTS sp_contactos_actualizar;




DROP PROCEDURE IF EXISTS sp_contactos_crear;




DROP PROCEDURE IF EXISTS sp_contactos_eliminar;




DROP PROCEDURE IF EXISTS sp_contactos_listar_por_cliente;




DROP PROCEDURE IF EXISTS sp_contactos_obtener;




DROP TABLE IF EXISTS contactos;




DROP PROCEDURE IF EXISTS sp_oportunidades_actualizar;




DROP PROCEDURE IF EXISTS sp_oportunidades_actualizar_etapa;




DROP PROCEDURE IF EXISTS sp_oportunidades_crear;




DROP PROCEDURE IF EXISTS sp_oportunidades_eliminar;




DROP PROCEDURE IF EXISTS sp_oportunidades_listar;




DROP PROCEDURE IF EXISTS sp_oportunidades_obtener;




DROP TABLE IF EXISTS oportunidades;




DROP PROCEDURE IF EXISTS sp_clientes_actualizar;




DROP PROCEDURE IF EXISTS sp_clientes_crear;




DROP PROCEDURE IF EXISTS sp_clientes_eliminar;




DROP PROCEDURE IF EXISTS sp_clientes_listar;




DROP PROCEDURE IF EXISTS sp_clientes_obtener;




DROP TABLE IF EXISTS clientes;




DROP PROCEDURE IF EXISTS sp_tipos_documento_actualizar;




DROP PROCEDURE IF EXISTS sp_tipos_documento_crear;




DROP PROCEDURE IF EXISTS sp_tipos_documento_eliminar;




DROP PROCEDURE IF EXISTS sp_tipos_documento_listar;




DROP PROCEDURE IF EXISTS sp_tipos_documento_obtener;




DROP TABLE IF EXISTS tipos_documento_identidad;




DROP PROCEDURE IF EXISTS sp_ubigeos_actualizar;




DROP PROCEDURE IF EXISTS sp_ubigeos_crear;




DROP PROCEDURE IF EXISTS sp_ubigeos_eliminar;




DROP PROCEDURE IF EXISTS sp_ubigeos_listar;




DROP PROCEDURE IF EXISTS sp_ubigeos_obtener;




DROP TABLE IF EXISTS ubigeos;




USE crmbeta;




CREATE TABLE ubigeos (
  id_ubigeo int NOT NULL AUTO_INCREMENT,
  departamento varchar(100) NOT NULL,
  provincia varchar(100) NOT NULL,
  distrito varchar(100) NOT NULL,
  estado int DEFAULT 1,
  PRIMARY KEY (id_ubigeo)
)
ENGINE = INNODB,
AUTO_INCREMENT = 3,
AVG_ROW_LENGTH = 16384,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci,
ROW_FORMAT = DYNAMIC;






CREATE

PROCEDURE sp_ubigeos_obtener (IN p_id_ubigeo int)
BEGIN
  SELECT
    *
  FROM ubigeos
  WHERE id_ubigeo = p_id_ubigeo;
END
$$




CREATE

PROCEDURE sp_ubigeos_listar ()
BEGIN
  SELECT
    *
  FROM ubigeos
  WHERE estado = 1;
END
$$




CREATE

PROCEDURE sp_ubigeos_eliminar (IN p_id_ubigeo int)
BEGIN
  UPDATE ubigeos
  SET estado = 0
  WHERE id_ubigeo = p_id_ubigeo;
END
$$




CREATE

PROCEDURE sp_ubigeos_crear (IN p_departamento varchar(100), IN p_provincia varchar(100), IN p_distrito varchar(100))
BEGIN
  INSERT INTO ubigeos (departamento, provincia, distrito)
    VALUES (p_departamento, p_provincia, p_distrito);
END
$$




CREATE

PROCEDURE sp_ubigeos_actualizar (IN p_id_ubigeo int, IN p_departamento varchar(100), IN p_provincia varchar(100), IN p_distrito varchar(100))
BEGIN
  UPDATE ubigeos
  SET departamento = p_departamento,
      provincia = p_provincia,
      distrito = p_distrito
  WHERE id_ubigeo = p_id_ubigeo;
END
$$






CREATE TABLE tipos_documento_identidad (
  id_tipo_documento int NOT NULL AUTO_INCREMENT,
  nombre_documento varchar(50) NOT NULL,
  estado int DEFAULT 1,
  PRIMARY KEY (id_tipo_documento)
)
ENGINE = INNODB,
AUTO_INCREMENT = 5,
AVG_ROW_LENGTH = 5461,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci,
ROW_FORMAT = DYNAMIC;






CREATE

PROCEDURE sp_tipos_documento_obtener (IN p_id_tipo_documento int)
BEGIN
  SELECT
    *
  FROM tipos_documento_identidad
  WHERE id_tipo_documento = p_id_tipo_documento;
END
$$




CREATE

PROCEDURE sp_tipos_documento_listar ()
BEGIN
  SELECT
    *
  FROM tipos_documento_identidad
  WHERE estado = 1;
END
$$




CREATE

PROCEDURE sp_tipos_documento_eliminar (IN p_id_tipo_documento int)
BEGIN
  UPDATE tipos_documento_identidad
  SET estado = 0
  WHERE id_tipo_documento = p_id_tipo_documento;
END
$$




CREATE

PROCEDURE sp_tipos_documento_crear (IN p_nombre_documento varchar(50))
BEGIN
  INSERT INTO tipos_documento_identidad (nombre_documento)
    VALUES (p_nombre_documento);
END
$$




CREATE

PROCEDURE sp_tipos_documento_actualizar (IN p_id_tipo_documento int, IN p_nombre_documento varchar(50))
BEGIN
  UPDATE tipos_documento_identidad
  SET nombre_documento = p_nombre_documento
  WHERE id_tipo_documento = p_id_tipo_documento;
END
$$






CREATE TABLE clientes (
  id_cliente int NOT NULL AUTO_INCREMENT,
  nombre_cliente varchar(100) NOT NULL,
  direccion_cliente varchar(255) DEFAULT NULL,
  telefono_cliente varchar(20) DEFAULT NULL,
  website_cliente varchar(100) DEFAULT NULL,
  fecha_creacion datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  estado tinyint(1) NOT NULL DEFAULT 1,
  id_tipo_documento int DEFAULT NULL,
  numero_documento varchar(20) DEFAULT NULL,
  id_ubigeo int DEFAULT NULL,
  correo_electronico varchar(100) DEFAULT NULL,
  observaciones text DEFAULT NULL,
  PRIMARY KEY (id_cliente)
)
ENGINE = INNODB,
AUTO_INCREMENT = 5,
AVG_ROW_LENGTH = 5461,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci,
ROW_FORMAT = DYNAMIC;




ALTER TABLE clientes
ADD CONSTRAINT clientes_ibfk_1 FOREIGN KEY (id_tipo_documento)
REFERENCES tipos_documento_identidad (id_tipo_documento);




ALTER TABLE clientes
ADD CONSTRAINT clientes_ibfk_2 FOREIGN KEY (id_ubigeo)
REFERENCES ubigeos (id_ubigeo);






CREATE

PROCEDURE sp_clientes_obtener (IN `in_id_cliente` int)
BEGIN
  SELECT
    c.*,
    u.departamento,
    u.provincia,
    u.distrito
  FROM clientes c
    LEFT JOIN ubigeos u
      ON c.id_ubigeo = u.id_ubigeo
  WHERE c.id_cliente = in_id_cliente;
END
$$




CREATE

PROCEDURE sp_clientes_listar ()
BEGIN
  SELECT
    c.id_cliente,
    c.nombre_cliente,
    c.telefono_cliente,
    c.website_cliente,
    td.nombre_documento,
    c.numero_documento,
    CONCAT(u.departamento, ', ', u.provincia, ', ', u.distrito) AS ubigeo,
    c.correo_electronico,
    c.direccion_cliente
  FROM clientes c
    LEFT JOIN tipos_documento_identidad td
      ON c.id_tipo_documento = td.id_tipo_documento
    LEFT JOIN ubigeos u
      ON c.id_ubigeo = u.id_ubigeo
  WHERE c.estado = 1;
END
$$




CREATE

PROCEDURE sp_clientes_eliminar (IN `in_id_cliente` int)
BEGIN
  UPDATE clientes
  SET estado = 0
  WHERE id_cliente = in_id_cliente;
END
$$




CREATE

PROCEDURE sp_clientes_crear (IN p_nombre_cliente varchar(255),
IN p_direccion_cliente varchar(255),
IN p_telefono_cliente varchar(20),
IN p_website_cliente varchar(100),
IN p_id_tipo_documento int,
IN p_numero_documento varchar(20),
IN p_id_ubigeo int,
IN p_correo_electronico varchar(100),
IN p_observaciones text)
BEGIN
  INSERT INTO clientes (nombre_cliente, direccion_cliente, telefono_cliente, website_cliente, id_tipo_documento, numero_documento, id_ubigeo, correo_electronico, observaciones)
    VALUES (p_nombre_cliente, p_direccion_cliente, p_telefono_cliente, p_website_cliente, p_id_tipo_documento, p_numero_documento, p_id_ubigeo, p_correo_electronico, p_observaciones);
END
$$




CREATE

PROCEDURE sp_clientes_actualizar (IN p_id_cliente int,
IN p_nombre_cliente varchar(255),
IN p_direccion_cliente varchar(255),
IN p_telefono_cliente varchar(20),
IN p_website_cliente varchar(100),
IN p_id_tipo_documento int,
IN p_numero_documento varchar(20),
IN p_id_ubigeo int,
IN p_correo_electronico varchar(100),
IN p_observaciones text)
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
END
$$






CREATE TABLE etapas (
  id_etapa int NOT NULL AUTO_INCREMENT,
  nombre_etapa varchar(50) NOT NULL,
  orden int NOT NULL,
  estado tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id_etapa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO etapas (id_etapa, nombre_etapa, orden, estado) VALUES
(1, 'Calificación', 1, 1),
(2, 'Propuesta', 2, 1),
(3, 'Negociación', 3, 1),
(4, 'Ganada', 4, 1),
(5, 'Perdida', 5, 1);

CREATE TABLE oportunidades (
  id_oportunidad int NOT NULL AUTO_INCREMENT,
  id_cliente int NOT NULL,
  nombre_oportunidad varchar(100) NOT NULL,
  valor_estimado decimal(10, 2) DEFAULT NULL,
  fecha_cierre date DEFAULT NULL,
  etapa enum ('Calificación', 'Propuesta', 'Negociación', 'Ganada', 'Perdida') NOT NULL DEFAULT 'Calificación',
  fecha_creacion datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  estado tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id_oportunidad)
)
ENGINE = INNODB,
AUTO_INCREMENT = 4,
AVG_ROW_LENGTH = 5461,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci,
ROW_FORMAT = DYNAMIC;




ALTER TABLE oportunidades
ADD CONSTRAINT oportunidades_ibfk_1 FOREIGN KEY (id_cliente)
REFERENCES clientes (id_cliente) ON DELETE CASCADE ON UPDATE CASCADE;






CREATE

PROCEDURE sp_oportunidades_obtener (IN `in_id_oportunidad` int)
BEGIN
  SELECT
    *
  FROM oportunidades
  WHERE id_oportunidad = in_id_oportunidad;
END
$$




CREATE

PROCEDURE sp_oportunidades_listar ()
BEGIN
  SELECT
    o.*,
    c.nombre_cliente
  FROM oportunidades o
    INNER JOIN clientes c
      ON o.id_cliente = c.id_cliente
  WHERE o.estado = 1
  ORDER BY o.fecha_creacion DESC;
END
$$




CREATE

PROCEDURE sp_oportunidades_eliminar (IN `in_id_oportunidad` int)
BEGIN
  UPDATE oportunidades
  SET estado = 0
  WHERE id_oportunidad = in_id_oportunidad;
END
$$




CREATE

PROCEDURE sp_oportunidades_crear (IN `in_id_cliente` int,
IN `in_nombre_oportunidad` varchar(100),
IN `in_valor_estimado` decimal(10, 2),
IN `in_fecha_cierre` date,
IN `in_etapa` varchar(20))
BEGIN
  INSERT INTO oportunidades (id_cliente, nombre_oportunidad, valor_estimado, fecha_cierre, etapa)
    VALUES (in_id_cliente, in_nombre_oportunidad, in_valor_estimado, in_fecha_cierre, in_etapa);
END
$$




CREATE

PROCEDURE sp_oportunidades_actualizar_etapa (IN `in_id_oportunidad` int,
IN `in_etapa` varchar(20))
BEGIN
  UPDATE oportunidades
  SET etapa = in_etapa
  WHERE id_oportunidad = in_id_oportunidad;
END
$$




CREATE

PROCEDURE sp_oportunidades_actualizar (IN `in_id_oportunidad` int,
IN `in_id_cliente` int,
IN `in_nombre_oportunidad` varchar(100),
IN `in_valor_estimado` decimal(10, 2),
IN `in_fecha_cierre` date,
IN `in_etapa` varchar(20))
BEGIN
  UPDATE oportunidades
  SET id_cliente = in_id_cliente,
      nombre_oportunidad = in_nombre_oportunidad,
      valor_estimado = in_valor_estimado,
      fecha_cierre = in_fecha_cierre,
      etapa = in_etapa
  WHERE id_oportunidad = in_id_oportunidad;
END
$$






CREATE TABLE contactos (
  id_contacto int NOT NULL AUTO_INCREMENT,
  id_cliente int NOT NULL,
  nombre_contacto varchar(100) NOT NULL,
  cargo_contacto varchar(100) DEFAULT NULL,
  correo_contacto varchar(100) DEFAULT NULL,
  telefono_contacto varchar(20) DEFAULT NULL,
  fecha_creacion datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  estado tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id_contacto)
)
ENGINE = INNODB,
AUTO_INCREMENT = 3,
AVG_ROW_LENGTH = 16384,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci,
ROW_FORMAT = DYNAMIC;




ALTER TABLE contactos
ADD CONSTRAINT contactos_ibfk_1 FOREIGN KEY (id_cliente)
REFERENCES clientes (id_cliente) ON DELETE CASCADE ON UPDATE CASCADE;






CREATE

PROCEDURE sp_contactos_obtener (IN `in_id_contacto` int)
BEGIN
  SELECT
    *
  FROM contactos
  WHERE id_contacto = in_id_contacto;
END
$$




CREATE

PROCEDURE sp_contactos_listar_por_cliente (IN `in_id_cliente` int)
BEGIN
  SELECT
    *
  FROM contactos
  WHERE id_cliente = in_id_cliente
  AND estado = 1
  ORDER BY nombre_contacto ASC;
END
$$




CREATE

PROCEDURE sp_contactos_eliminar (IN `in_id_contacto` int)
BEGIN
  UPDATE contactos
  SET estado = 0
  WHERE id_contacto = in_id_contacto;
END
$$




CREATE

PROCEDURE sp_contactos_crear (IN `in_id_cliente` int,
IN `in_nombre_contacto` varchar(100),
IN `in_cargo_contacto` varchar(100),
IN `in_correo_contacto` varchar(100),
IN `in_telefono_contacto` varchar(20))
BEGIN
  INSERT INTO contactos (id_cliente, nombre_contacto, cargo_contacto, correo_contacto, telefono_contacto)
    VALUES (in_id_cliente, in_nombre_contacto, in_cargo_contacto, in_correo_contacto, in_telefono_contacto);
END
$$




CREATE

PROCEDURE sp_contactos_actualizar (IN `in_id_contacto` int,
IN `in_nombre_contacto` varchar(100),
IN `in_cargo_contacto` varchar(100),
IN `in_correo_contacto` varchar(100),
IN `in_telefono_contacto` varchar(20))
BEGIN
  UPDATE contactos
  SET nombre_contacto = in_nombre_contacto,
      cargo_contacto = in_cargo_contacto,
      correo_contacto = in_correo_contacto,
      telefono_contacto = in_telefono_contacto
  WHERE id_contacto = in_id_contacto;
END
$$






CREATE TABLE perfiles (
  id_perfil int NOT NULL AUTO_INCREMENT,
  nombre_perfil varchar(50) NOT NULL,
  estado tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id_perfil)
)
ENGINE = INNODB,
AUTO_INCREMENT = 4,
AVG_ROW_LENGTH = 5461,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci,
ROW_FORMAT = DYNAMIC;






CREATE

PROCEDURE sp_perfiles_obtener (IN `in_id_perfil` int)
BEGIN
  SELECT
    *
  FROM perfiles
  WHERE id_perfil = in_id_perfil;
END
$$




CREATE

PROCEDURE sp_perfiles_listar ()
BEGIN
  SELECT
    *
  FROM perfiles;
END
$$




CREATE

PROCEDURE sp_perfiles_eliminar (IN `in_id_perfil` int)
BEGIN
  DELETE
    FROM perfiles
  WHERE id_perfil = in_id_perfil;
END
$$




CREATE

PROCEDURE sp_perfiles_crear (IN `in_nombre_perfil` varchar(50))
BEGIN
  INSERT INTO perfiles (nombre_perfil)
    VALUES (in_nombre_perfil);
END
$$




CREATE

PROCEDURE sp_perfiles_actualizar (IN `in_id_perfil` int, IN `in_nombre_perfil` varchar(50))
BEGIN
  UPDATE perfiles
  SET nombre_perfil = in_nombre_perfil
  WHERE id_perfil = in_id_perfil;
END
$$






CREATE TABLE usuarios (
  id_usuario int NOT NULL AUTO_INCREMENT,
  nombre_usuario varchar(50) NOT NULL,
  apellido_usuario varchar(50) NOT NULL,
  correo_usuario varchar(100) NOT NULL,
  clave_usuario varchar(255) NOT NULL,
  id_perfil int NOT NULL,
  estado tinyint(1) NOT NULL DEFAULT 1,
  codigo_verificacion varchar(255) DEFAULT NULL,
  codigo_expiracion datetime DEFAULT NULL,
  PRIMARY KEY (id_usuario)
)
ENGINE = INNODB,
AUTO_INCREMENT = 5,
AVG_ROW_LENGTH = 5461,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci,
ROW_FORMAT = DYNAMIC;




ALTER TABLE usuarios
ADD CONSTRAINT usuarios_ibfk_1 FOREIGN KEY (id_perfil)
REFERENCES perfiles (id_perfil) ON DELETE CASCADE ON UPDATE CASCADE;






CREATE

PROCEDURE sp_usuarios_obtener (IN `in_id_usuario` int)
BEGIN
  SELECT
    *
  FROM usuarios
  WHERE id_usuario = in_id_usuario;
END
$$




CREATE

PROCEDURE sp_usuarios_login (IN `in_correo_usuario` varchar(100))
BEGIN
  SELECT
    *
  FROM usuarios
  WHERE correo_usuario COLLATE utf8mb4_general_ci
  = in_correo_usuario COLLATE utf8mb4_general_ci;
END
$$




CREATE

PROCEDURE sp_usuarios_listar ()
BEGIN
  SELECT
    u.*,
    p.nombre_perfil
  FROM usuarios u
    INNER JOIN perfiles p
      ON u.id_perfil = p.id_perfil;
END
$$




CREATE

PROCEDURE sp_usuarios_eliminar (IN `in_id_usuario` int)
BEGIN
  DELETE
    FROM usuarios
  WHERE id_usuario = in_id_usuario;
END
$$




CREATE

PROCEDURE sp_usuarios_crear (IN `in_nombre_usuario` varchar(50), IN `in_apellido_usuario` varchar(50), IN `in_correo_usuario` varchar(100), IN `in_clave_usuario` varchar(255), IN `in_id_perfil` int)
BEGIN
  INSERT INTO usuarios (nombre_usuario, apellido_usuario, correo_usuario, clave_usuario, id_perfil)
    VALUES (in_nombre_usuario, in_apellido_usuario, in_correo_usuario, in_clave_usuario, in_id_perfil);
END
$$




CREATE

PROCEDURE sp_usuarios_actualizar (IN `in_id_usuario` int, IN `in_nombre_usuario` varchar(50), IN `in_apellido_usuario` varchar(50), IN `in_correo_usuario` varchar(100), IN `in_clave_usuario` varchar(255), IN `in_id_perfil` int)
BEGIN
  UPDATE usuarios
  SET nombre_usuario = in_nombre_usuario,
      apellido_usuario = in_apellido_usuario,
      correo_usuario = in_correo_usuario,
      clave_usuario = in_clave_usuario,
      id_perfil = in_id_perfil
  WHERE id_usuario = in_id_usuario;
END
$$






CREATE TABLE actividades (
  id_actividad int NOT NULL AUTO_INCREMENT,
  id_cliente int DEFAULT NULL,
  id_contacto int DEFAULT NULL,
  id_oportunidad int DEFAULT NULL,
  id_usuario int NOT NULL,
  tipo_actividad enum ('Llamada', 'Reunión', 'Correo', 'Tarea') NOT NULL,
  asunto varchar(255) NOT NULL,
  descripcion text DEFAULT NULL,
  fecha_actividad datetime NOT NULL,
  fecha_creacion datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_actividad)
)
ENGINE = INNODB,
AUTO_INCREMENT = 9,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci,
ROW_FORMAT = DYNAMIC;




ALTER TABLE actividades
ADD CONSTRAINT actividades_ibfk_1 FOREIGN KEY (id_cliente)
REFERENCES clientes (id_cliente) ON DELETE SET NULL;




ALTER TABLE actividades
ADD CONSTRAINT actividades_ibfk_2 FOREIGN KEY (id_contacto)
REFERENCES contactos (id_contacto) ON DELETE SET NULL;




ALTER TABLE actividades
ADD CONSTRAINT actividades_ibfk_3 FOREIGN KEY (id_oportunidad)
REFERENCES oportunidades (id_oportunidad) ON DELETE SET NULL;




ALTER TABLE actividades
ADD CONSTRAINT actividades_ibfk_4 FOREIGN KEY (id_usuario)
REFERENCES usuarios (id_usuario) ON DELETE CASCADE;






CREATE

PROCEDURE sp_dashboard_estadisticas ()
BEGIN
  SELECT
    (SELECT
        COUNT(*)
      FROM clientes
      WHERE estado = 1) AS total_clientes,
    (SELECT
        COUNT(*)
      FROM oportunidades
      WHERE estado = 1
      AND etapa NOT IN ('Ganada', 'Perdida')) AS oportunidades_activas,
    (SELECT
        COUNT(*)
      FROM actividades
      WHERE fecha_actividad >= CURDATE()) AS actividades_pendientes;
END
$$




CREATE

PROCEDURE sp_actividades_listar_por_cliente (IN `in_id_cliente` int)
BEGIN
  SELECT
    a.*,
    u.nombre_usuario
  FROM actividades a
    INNER JOIN usuarios u
      ON a.id_usuario = u.id_usuario
  WHERE a.id_cliente = in_id_cliente
  ORDER BY a.fecha_actividad DESC;
END
$$




CREATE

PROCEDURE sp_actividades_crear (IN `in_id_cliente` int,
IN `in_id_contacto` int,
IN `in_id_oportunidad` int,
IN `in_id_usuario` int,
IN `in_tipo_actividad` varchar(20),
IN `in_asunto` varchar(255),
IN `in_descripcion` text,
IN `in_fecha_actividad` datetime)
BEGIN
  DECLARE v_id_oportunidad int;

  -- Si el parámetro llega NULL o 0, calculamos el siguiente número
  IF in_id_oportunidad IS NULL
    OR in_id_oportunidad = 0 THEN
    SELECT
      IFNULL(MAX(id_oportunidad), 0) + 1 INTO v_id_oportunidad
    FROM actividades;
  ELSE
    SET v_id_oportunidad = in_id_oportunidad;
  END IF;

  INSERT INTO actividades (id_cliente,
  id_contacto,
  id_oportunidad,
  id_usuario,
  tipo_actividad,
  asunto,
  descripcion,
  fecha_actividad)
    VALUES (in_id_cliente, in_id_contacto, v_id_oportunidad, in_id_usuario, in_tipo_actividad, in_asunto, in_descripcion, in_fecha_actividad);
END
$$






INSERT INTO ubigeos VALUES
(1, 'Lima', 'Lima', 'Lima', 1),
(2, 'd', 'd', 'd', 0);




INSERT INTO tipos_documento_identidad VALUES
(1, 'DNI', 1),
(2, 'RUC', 1),
(3, 'CARNET', 1),
(4, 'i', 0);




INSERT INTO perfiles VALUES
(1, 'Administrador', 1),
(2, 'Usuario', 1),
(3, 'VENDEDOR', 1);




INSERT INTO clientes VALUES
(1, 'FALABELLA.COM S.A.C.', 'AV. PASEO DE LA REPUBLICA - Nro: 3220  - URBANIZACION SANTA ANA  - SAN ISIDRO', '123213213', '', '2025-10-28 14:21:59', 1, 2, '20547836473', 1, '', ''),
(2, 'PACLIFE SA', 'AV LARCO 930 OF 302 MIRAFLORES', '4471788', '', '2025-10-28 14:26:13', 1, NULL, NULL, NULL, NULL, NULL),
(3, 'MIGUEL', 'Av Jose larco 930', '4471788', '', '2025-10-28 15:40:13', 0, 1, '777777777777', 1, '', ''),
(4, 'ADVISOR GLOBAL SYSTEMS SOCIEDAD ANONIMA CERRADA', 'AV. LARCO - Nro: 930  - INT.: 302  - MIRAFLORES', '12345678', '', '2025-10-28 16:54:38', 1, 2, '20517585794', 1, 'sistemas@mgsa.com.pe', 'Holaa');




INSERT INTO usuarios (id_usuario, nombre_usuario, apellido_usuario, correo_usuario, clave_usuario, id_perfil, estado) VALUES
(1, 'Admin', 'User', 'admin@crm.com', '$2y$10$HoyyaTnauVkSCKsK8NOyDOkCsVPedppXAc7vVGs7F9LO2e1EgWIPC', 1, 1),
(2, 'Santiago', 'Prueba', 'santiago@crm.com', '$2y$10$XizLkd9gHRDLm6tDaGJZFeHkMoRIC9rlnH2RnjJqzWKLZB1djdtWG', 3, 1),
(4, 'sistemas', 'sistemas', 'sistemas@crm.com', '$2y$10$C9o2j5cOi1jNuHmgtCfvTex4AVpaeop4giGxYjrPKSRtgU601peJi', 1, 1);




INSERT INTO oportunidades VALUES
(1, 1, 'SERVICIO DE SERVIDORES', 9999.00, '2025-10-28', 'Negociación', '2025-10-28 14:22:54', 1),
(2, 1, 'VENTA DE ADVISOR', 1111.00, '2025-10-30', 'Calificación', '2025-10-28 14:23:22', 1),
(3, 2, 'CONTRATO RENOVACION MENSUAL PACLIFE', 500.00, '2025-10-31', 'Propuesta', '2025-10-28 14:26:50', 0);




INSERT INTO contactos VALUES
(1, 1, 'PRUEBA', '1', 'sistemas@mgsa.com.pe', '1', '2025-10-28 14:43:29', 0),
(2, 4, 'PRUEBA', 'VENDEDOR', 'prueba@mgsa.com.pe', '945123456', '2025-10-29 12:41:11', 1);




INSERT INTO actividades VALUES
(7, 1, NULL, 1, 4, 'Reunión', 'DEMOSTRACION', 'demo', '2025-11-02 10:00:00', '2025-10-29 14:01:37'),
(8, 1, NULL, 2, 4, 'Llamada', 'SOPORTE', 'SOPORTE', '2025-11-03 13:00:00', '2025-10-29 14:02:18');


CREATE

    PROCEDURE `sp_actividades_pendientes`()
    BEGIN
        SELECT
            id_actividad,
            asunto,
            descripcion,
            fecha_actividad,
            tipo_actividad
        FROM
            actividades
        WHERE
            fecha_actividad >= CURDATE();
    END;
$$

DELIMITER $$

CREATE PROCEDURE `sp_dashboard_anios`()
BEGIN
    SELECT DISTINCT YEAR(fecha_creacion) AS anio
    FROM oportunidades
    ORDER BY anio DESC;
END$$

CREATE PROCEDURE `sp_dashboard_etapas`()
BEGIN
    SELECT id_etapa, nombre_etapa
    FROM etapas
    WHERE estado = 1
    ORDER BY orden;
END$$

CREATE PROCEDURE `sp_dashboard_usuarios`()
BEGIN
    SELECT id_usuario, nombre_usuario
    FROM usuarios
    WHERE estado = 1
    ORDER BY nombre_usuario;
END$$

CREATE PROCEDURE `sp_dashboard_funnel`(
    IN p_anio INT,
    IN p_mes INT,
    IN p_id_etapa INT,
    IN p_id_usuario INT
)
BEGIN
    SELECT
        e.nombre_etapa,
        SUM(o.valor_estimado) AS valor_estimado
    FROM oportunidades o
    JOIN etapas e ON o.id_etapa = e.id_etapa
    WHERE
        YEAR(o.fecha_creacion) = p_anio
        AND MONTH(o.fecha_creacion) = p_mes
        AND (p_id_etapa IS NULL OR o.id_etapa = p_id_etapa)
        AND (p_id_usuario IS NULL OR o.id_usuario = p_id_usuario)
    GROUP BY e.nombre_etapa
    ORDER BY e.orden;
END$$

CREATE PROCEDURE `sp_dashboard_barras`(
    IN p_anio INT,
    IN p_mes INT,
    IN p_id_etapa INT,
    IN p_id_usuario INT
)
BEGIN
    SELECT
        e.nombre_etapa,
        MONTHNAME(o.fecha_cierre) AS mes,
        SUM(o.valor_estimado) AS total_ventas
    FROM oportunidades o
    JOIN etapas e ON o.id_etapa = e.id_etapa
    WHERE
        YEAR(o.fecha_cierre) = p_anio
        AND (p_mes IS NULL OR MONTH(o.fecha_cierre) = p_mes)
        AND (p_id_etapa IS NULL OR o.id_etapa = p_id_etapa)
        AND (p_id_usuario IS NULL OR o.id_usuario = p_id_usuario)
        AND o.id_etapa = (SELECT id_etapa FROM etapas WHERE nombre_etapa = 'Ganada')
    GROUP BY e.nombre_etapa, mes
    ORDER BY MONTH(o.fecha_cierre);
END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE `sp_reporte_oportunidades`(IN p_columnas TEXT)
BEGIN
    SET @sql = CONCAT('SELECT ', p_columnas, ' FROM oportunidades o JOIN clientes c ON o.id_cliente = c.id_cliente LEFT JOIN actividades a ON o.id_oportunidad = a.id_oportunidad LEFT JOIN usuarios u ON a.id_usuario = u.id_usuario');
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END$$

CREATE PROCEDURE `sp_reporte_actividades`(IN p_columnas TEXT)
BEGIN
    SET @sql = CONCAT('SELECT ', p_columnas, ' FROM actividades a JOIN usuarios u ON a.id_usuario = u.id_usuario LEFT JOIN clientes c ON a.id_cliente = c.id_cliente LEFT JOIN oportunidades o ON a.id_oportunidad = o.id_oportunidad');
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END$$

CREATE PROCEDURE `sp_reporte_clientes`(IN p_columnas TEXT)
BEGIN
    SET @sql = CONCAT('SELECT ', p_columnas, ' FROM clientes c LEFT JOIN tipos_documento_identidad td ON c.id_tipo_documento = td.id_tipo_documento LEFT JOIN ubigeos ub ON c.id_ubigeo = ub.id_ubigeo');
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END$$

DELIMITER ;

CREATE TABLE `reportes_plantillas` (
  `id_plantilla` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_plantilla` varchar(100) NOT NULL,
  `tipo_reporte` varchar(50) NOT NULL,
  `columnas` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_plantilla`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DELIMITER $$

CREATE PROCEDURE `sp_usuarios_generar_codigo`(IN `in_id_usuario` INT)
BEGIN
    DECLARE codigo VARCHAR(6);
    SET codigo = LPAD(FLOOR(RAND() * 1000000), 6, '0');

    UPDATE usuarios
    SET
        codigo_verificacion = codigo,
        codigo_expiracion = DATE_ADD(NOW(), INTERVAL 15 MINUTE)
    WHERE id_usuario = in_id_usuario;

    SELECT codigo;
END$$

CREATE PROCEDURE `sp_usuarios_verificar_codigo`(IN `in_id_usuario` INT, IN `in_codigo` VARCHAR(6))
BEGIN
    SELECT *
    FROM usuarios
    WHERE id_usuario = in_id_usuario
      AND codigo_verificacion = in_codigo
      AND codigo_expiracion > NOW();
END$$

CREATE PROCEDURE `sp_usuarios_invalidar_codigo`(IN `in_id_usuario` INT)
BEGIN
    UPDATE usuarios
    SET
        codigo_verificacion = NULL,
        codigo_expiracion = NULL
    WHERE id_usuario = in_id_usuario;
END$$

DELIMITER ;
