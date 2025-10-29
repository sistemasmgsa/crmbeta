DELIMITER $$
CREATE DEFINER=`evento`@`%` PROCEDURE `usp_oportunidades_eliminar`(IN `in_id_oportunidad` INT)
BEGIN
	UPDATE oportunidades SET estado = 0 WHERE id_oportunidad = in_id_oportunidad;
END$$
DELIMITER ;
