
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
