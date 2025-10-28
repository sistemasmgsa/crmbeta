DELIMITER //

DROP PROCEDURE IF EXISTS sp_clientes_obtener//
CREATE PROCEDURE `sp_clientes_obtener`(IN `in_id_cliente` INT)
BEGIN
	SELECT c.*, u.departamento, u.provincia, u.distrito
    FROM clientes c
    LEFT JOIN ubigeos u ON c.id_ubigeo = u.id_ubigeo
    WHERE c.id_cliente = in_id_cliente;
END//

DELIMITER ;
