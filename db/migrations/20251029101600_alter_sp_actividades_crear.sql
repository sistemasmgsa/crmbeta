DELIMITER $$

CREATE
DEFINER = 'evento'@'%'
PROCEDURE sp_actividades_crear (IN `in_id_cliente` int,
IN `in_id_contacto` int,
IN `in_id_oportunidad` int,
IN `in_id_usuario` int,
IN `in_tipo_actividad` varchar(20),
IN `in_asunto` varchar(255),
IN `in_descripcion` text,
IN `in_fecha_actividad` datetime)
BEGIN
  INSERT INTO actividades (id_cliente, id_contacto, id_oportunidad, id_usuario, tipo_actividad, asunto, descripcion, fecha_actividad)
    VALUES (in_id_cliente, IF(in_id_contacto = 0, NULL, in_id_contacto), in_id_oportunidad, in_id_usuario, in_tipo_actividad, in_asunto, in_descripcion, in_fecha_actividad);
END
$$

DELIMITER ;
