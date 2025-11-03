SET NAMES 'utf8';

USE crmbeta;

-- Drop existing tables and procedures (optional, for a clean setup)
DROP TABLE IF EXISTS `reportes_plantillas`;
DROP PROCEDURE IF EXISTS `sp_reporte_oportunidades`;
DROP PROCEDURE IF EXISTS `sp_reporte_actividades`;
DROP PROCEDURE IF EXISTS `sp_reporte_clientes`;
-- ... (add other drop statements if needed) ...

-- Main schema creation (paste your existing table creation scripts here)
-- ...

-- Stored Procedures
DELIMITER $$

CREATE PROCEDURE `sp_reporte_oportunidades` (IN p_columnas TEXT)
BEGIN
    SET @sql = CONCAT('SELECT ', p_columnas, ' FROM oportunidades o JOIN clientes c ON o.id_cliente = c.id_cliente LEFT JOIN actividades a ON o.id_oportunidad = a.id_oportunidad LEFT JOIN usuarios u ON a.id_usuario = u.id_usuario');
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END$$

CREATE PROCEDURE `sp_reporte_actividades` (IN p_columnas TEXT)
BEGIN
    SET @sql = CONCAT('SELECT ', p_columnas, ' FROM actividades a JOIN usuarios u ON a.id_usuario = u.id_usuario LEFT JOIN clientes c ON a.id_cliente = c.id_cliente LEFT JOIN oportunidades o ON a.id_oportunidad = o.id_oportunidad');
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END$$

CREATE PROCEDURE `sp_reporte_clientes` (IN p_columnas TEXT)
BEGIN
    SET @sql = CONCAT('SELECT ', p_columnas, ' FROM clientes c LEFT JOIN tipos_documento_identidad td ON c.id_tipo_documento = td.id_tipo_documento LEFT JOIN ubigeos ub ON c.id_ubigeo = ub.id_ubigeo');
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END$$

DELIMITER ;

-- reportes_plantillas table
CREATE TABLE `reportes_plantillas` (
  `id_plantilla` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_plantilla` varchar(100) NOT NULL,
  `tipo_reporte` varchar(50) NOT NULL,
  `columnas` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_plantilla`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
