-- Procedimientos almacenados para la tabla `perfiles`

DELIMITER $$
CREATE PROCEDURE `sp_perfiles_listar`()
BEGIN
	SELECT * FROM perfiles;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_perfiles_obtener`(IN `in_id_perfil` INT)
BEGIN
	SELECT * FROM perfiles WHERE id_perfil = in_id_perfil;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_perfiles_crear`(IN `in_nombre_perfil` VARCHAR(50))
BEGIN
	INSERT INTO perfiles (nombre_perfil) VALUES (in_nombre_perfil);
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_perfiles_actualizar`(IN `in_id_perfil` INT, IN `in_nombre_perfil` VARCHAR(50))
BEGIN
	UPDATE perfiles SET nombre_perfil = in_nombre_perfil WHERE id_perfil = in_id_perfil;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_perfiles_eliminar`(IN `in_id_perfil` INT)
BEGIN
	DELETE FROM perfiles WHERE id_perfil = in_id_perfil;
END$$
DELIMITER ;

-- Procedimientos almacenados para la tabla `usuarios`

DELIMITER $$
CREATE PROCEDURE `sp_usuarios_listar`()
BEGIN
	SELECT u.*, p.nombre_perfil FROM usuarios u INNER JOIN perfiles p ON u.id_perfil = p.id_perfil;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_usuarios_obtener`(IN `in_id_usuario` INT)
BEGIN
	SELECT * FROM usuarios WHERE id_usuario = in_id_usuario;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_usuarios_crear`(IN `in_nombre_usuario` VARCHAR(50), IN `in_apellido_usuario` VARCHAR(50), IN `in_correo_usuario` VARCHAR(100), IN `in_clave_usuario` VARCHAR(255), IN `in_id_perfil` INT)
BEGIN
	INSERT INTO usuarios (nombre_usuario, apellido_usuario, correo_usuario, clave_usuario, id_perfil) VALUES (in_nombre_usuario, in_apellido_usuario, in_correo_usuario, in_clave_usuario, in_id_perfil);
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_usuarios_actualizar`(IN `in_id_usuario` INT, IN `in_nombre_usuario` VARCHAR(50), IN `in_apellido_usuario` VARCHAR(50), IN `in_correo_usuario` VARCHAR(100), IN `in_clave_usuario` VARCHAR(255), IN `in_id_perfil` INT)
BEGIN
	UPDATE usuarios SET
		nombre_usuario = in_nombre_usuario,
		apellido_usuario = in_apellido_usuario,
		correo_usuario = in_correo_usuario,
		clave_usuario = in_clave_usuario,
		id_perfil = in_id_perfil
	WHERE id_usuario = in_id_usuario;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_usuarios_eliminar`(IN `in_id_usuario` INT)
BEGIN
	DELETE FROM usuarios WHERE id_usuario = in_id_usuario;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_usuarios_login`(IN `in_correo_usuario` VARCHAR(100))
BEGIN
	SELECT * FROM usuarios WHERE correo_usuario = in_correo_usuario;
END$$
DELIMITER ;
