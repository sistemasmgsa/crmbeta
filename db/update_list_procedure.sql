DELIMITER //

DROP PROCEDURE IF EXISTS sp_clientes_listar;
CREATE PROCEDURE sp_clientes_listar()
BEGIN
    SELECT
        c.id_cliente,
        c.nombre_cliente,
        c.telefono_cliente,
        c.website_cliente,
        td.nombre_documento,
        c.numero_documento,
        CONCAT(u.departamento, ', ', u.provincia, ', ', u.distrito) AS ubigeo,
        c.correo_electronico
    FROM
        clientes c
    LEFT JOIN
        tipos_documento_identidad td ON c.id_tipo_documento = td.id_tipo_documento
    LEFT JOIN
        ubigeos u ON c.id_ubigeo = u.id_ubigeo
    WHERE
        c.estado = 1;
END //

DELIMITER ;
