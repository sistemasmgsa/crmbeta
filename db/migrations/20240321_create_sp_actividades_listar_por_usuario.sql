DELIMITER $$

CREATE PROCEDURE `sp_actividades_listar_por_usuario`(IN `in_id_usuario` INT)
BEGIN
    SELECT
        a.id_actividad,
        a.asunto,
        a.descripcion,
        a.fecha_actividad,
        a.tipo_actividad,
        c.nombre_cliente,
        c.id_cliente
    FROM
        actividades a
    LEFT JOIN
        clientes c ON a.id_cliente = c.id_cliente
    WHERE
        (in_id_usuario IS NULL OR a.id_usuario = in_id_usuario);
END$$

DELIMITER ;
