DROP PROCEDURE IF EXISTS sp_oportunidades_listar_por_cliente;

DELIMITER $$

CREATE
DEFINER = 'evento'@'%'
PROCEDURE sp_oportunidades_listar_por_cliente (IN `in_id_cliente` int)
BEGIN
  SELECT
    o.*,
    c.nombre_cliente
  FROM oportunidades o
    INNER JOIN clientes c
      ON o.id_cliente = c.id_cliente
  WHERE o.id_cliente = in_id_cliente AND o.estado = 1
  ORDER BY o.fecha_creacion DESC;
END
$$

DELIMITER ;
