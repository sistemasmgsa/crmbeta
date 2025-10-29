DELIMITER $$

CREATE
    DEFINER = 'evento'@'%'
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
DELIMITER ;
